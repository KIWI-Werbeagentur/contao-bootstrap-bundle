<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\GridStyles;

/**
 * Back-fills `responsiveContainerPaddingX` on records that predate the container-padding-x
 * subsystem, so they keep the horizontal padding they used to render.
 *
 * Until that subsystem existed a container's own L/R padding came from Bootstrap's base
 * `.container*` rule (`padding-inline: calc(var(--bs-gutter-x) * .5)` → 12px), and `.row`
 * carried the matching negative margins. Both were deliberately dropped in favour of the
 * opt-in `.cx-*` utilities plus `column-gap`. The replacement only applies where the element
 * carries a `.cx-*` class, and that class is only rendered when the DCA field holds a value -
 * but the field's `'default' => ['xs' => 'default']` seeds newly inserted records only, so
 * every row predating the upgrade keeps its `blob NULL` and renders with no padding at all.
 *
 * What the correct value is depends on where the padding used to end up:
 *
 *   - A **bounded** container inset its content by 12px: the container padded, the row pulled
 *     back by the same amount, and the columns inside re-added it. `default` (space-3, 0.75rem)
 *     reproduces that.
 *   - A **fluid** container is meant to render its content flush to the viewport, and in most
 *     projects it did: full-bleed content (images, cards, sliders, custom elements) either sat
 *     directly in the row or cancelled the column padding itself. `default` would newly indent
 *     it - a hero image would gain a 12px gutter it never had. Those get `space-0`; an element
 *     mixing flush and inset parts is expected to pad its inset parts itself.
 *   - The exception is a fluid container holding nothing but plain text elements or forms
 *     (see TEXT_TYPES). Those cannot pad themselves: their 12px inset came solely from the
 *     column padding Bootstrap gave every direct child of a `.row`, which no longer exists.
 *     Without `default` their text and input fields would touch the viewport edge. Only
 *     visible children count; element groups and wrappers are not looked into, so a fluid
 *     container holding one counts as mixed and stays flush.
 *
 * Fluidity is only decided where the record actually carries a container size of its own: the
 * article's `responsiveContainerSize`, and `responsiveContainer` on content elements and form
 * fields, which carry the padding field only in container mode and then are a container with
 * an inner row just like an article. Everything else keeps `default`:
 *
 *   - the layout's header/footer sections are fluid but nest an inner wrapper between the
 *     container and the row, so their content was inset by 12px like a bounded container.
 *
 * It also runs under KIWI_BOOTSTRAP_LEGACY_GRID (see PreserveLegacyGridMode): the stock grid emits
 * no .cx-* rules, so the values have no effect there, but they are in place once the project
 * switches to the marginless grid.
 *
 * Only NULL cells are touched, so an explicit editor choice - including an explicit `space-0` -
 * is never overwritten, and the migration is idempotent.
 *
 * Deliberately limited to container padding. `responsiveRowGap` is NOT back-filled: row gap has
 * no predecessor at all, and projects upgrading into it already solve vertical spacing some
 * other way. `responsiveGutter` is NOT back-filled either: an unset gutter still falls back
 * through the `.row` class, so NULL renders correctly on its own.
 */
class BackfillContainerPaddingX extends AbstractMigration
{
    /**
     * Columns holding a container-padding-x value. Tables carrying them are discovered via the
     * schema manager, so projects adding the `containerPadding` palette fragment to further
     * tables are covered too.
     */
    private const RELEVANT_COLUMNS = [
        'responsiveContainerPaddingX',
        'responsiveContainerPaddingXHeader',
        'responsiveContainerPaddingXFooter',
    ];

    /**
     * Columns a record's own container size lives in, first match per table wins: articles use
     * `responsiveContainerSize`, content elements and form fields `responsiveContainer`.
     * Header/footer sizes are deliberately absent - see the class docblock.
     */
    private const SIZE_COLUMNS = ['responsiveContainerSize', 'responsiveContainer'];

    private const FLUID_SIZE = 'container-fluid';

    /**
     * Content element types that cannot inset themselves. A fluid container whose visible children
     * are all of these types keeps the 12px inset its content used to get from the column padding.
     */
    private const TEXT_TYPES = [
        'text', 'headline', 'list', 'table', 'hyperlink', 'toplink', 'markdown', 'code',
        'description_list', 'accordionSingle', 'accordionStart', 'accordionStop', 'form',
    ];

    /**
     * Tables whose fluid containers hold content elements: articles, and content elements acting
     * as a container for nested ones (Contao 5). Maps the table to the `ptable` of its children.
     */
    private const PARENT_TABLES = ['tl_article' => 'tl_article', 'tl_content' => 'tl_content'];

    public function __construct(private readonly Connection $connection)
    {
    }

    public function getName(): string
    {
        return 'Kiwi Bootstrap: back-fill the container padding for rows predating the container-padding-x subsystem';
    }

    public function shouldRun(): bool
    {
        return $this->findTargets() !== [];
    }

    public function run(): MigrationResult
    {
        $strDefault = serialize(['xs' => GridStyles::GENERIC_DEFAULT]);
        $strNone = serialize(['xs' => 'space-0']);

        $arrTouched = [];
        $intTotal = 0;

        foreach ($this->findTargets() as $strTable => $arrColumns) {
            $strSizeColumn = $this->findSizeColumn($strTable);

            foreach ($arrColumns as $strColumn) {
                // Fluid containers first: their content is meant to sit flush, so they must not gain
                // the padding the bounded ones get. Only the record's own size column decides it.
                if (null !== $strSizeColumn && 'responsiveContainerPaddingX' === $strColumn) {
                    // ...except those holding nothing but text elements or forms, which keep their inset
                    $arrTextOnly = $this->findTextOnlyFluidIds($strTable, $strColumn, $strSizeColumn);

                    if ($arrTextOnly !== []) {
                        $this->connection->executeStatement(
                            sprintf(
                                'UPDATE %s SET %s = ? WHERE %s IS NULL AND id IN (?)',
                                $this->connection->quoteIdentifier($strTable),
                                $this->connection->quoteIdentifier($strColumn),
                                $this->connection->quoteIdentifier($strColumn),
                            ),
                            [$strDefault, $arrTextOnly],
                            [ParameterType::STRING, ArrayParameterType::INTEGER],
                        );

                        $arrTouched[] = sprintf('%s.%s fluid text-only→default (%d: ids %s)', $strTable, $strColumn, \count($arrTextOnly), implode(', ', $arrTextOnly));
                        $intTotal += \count($arrTextOnly);
                    }

                    $intFluid = $this->write($strTable, $strColumn, $strNone, $strSizeColumn, self::FLUID_SIZE);

                    if ($intFluid > 0) {
                        $arrTouched[] = sprintf('%s.%s fluid→space-0 (%d)', $strTable, $strColumn, $intFluid);
                        $intTotal += $intFluid;
                    }
                }

                $intRest = $this->write($strTable, $strColumn, $strDefault);

                if ($intRest > 0) {
                    $arrTouched[] = sprintf('%s.%s →default (%d)', $strTable, $strColumn, $intRest);
                    $intTotal += $intRest;
                }
            }
        }

        return $this->createResult(
            true,
            sprintf(
                'Back-filled the container padding into %d row(s): %s. These rows predate the '
                . 'container-padding-x subsystem and would otherwise render without any horizontal '
                . 'container padding; fluid containers get "space-0" because their content is meant '
                . 'to sit flush, except those holding only text elements or forms, which keep their '
                . 'inset ("default") - check the listed ids. Row gap and gutter are intentionally '
                . 'left untouched.',
                $intTotal,
                $arrTouched === [] ? 'none' : implode(', ', $arrTouched),
            ),
        );
    }

    private function write(string $strTable, string $strColumn, string $strValue, ?string $strSizeColumn = null, ?string $strSize = null): int
    {
        $strSql = sprintf(
            'UPDATE %s SET %s = ? WHERE %s IS NULL',
            $this->connection->quoteIdentifier($strTable),
            $this->connection->quoteIdentifier($strColumn),
            $this->connection->quoteIdentifier($strColumn),
        );

        $arrParams = [$strValue];

        if (null !== $strSizeColumn && null !== $strSize) {
            $strSql .= sprintf(' AND %s = ?', $this->connection->quoteIdentifier($strSizeColumn));
            $arrParams[] = $strSize;
        }

        return (int) $this->connection->executeStatement($strSql, $arrParams);
    }

    /**
     * Ids of fluid containers in $strTable with a NULL padding value whose visible child content
     * elements exist and are all TEXT_TYPES. Empty containers do not qualify.
     *
     * @return list<int>
     */
    private function findTextOnlyFluidIds(string $strTable, string $strColumn, string $strSizeColumn): array
    {
        if (!isset(self::PARENT_TABLES[$strTable])) {
            return [];
        }

        $strVisible = "c.ptable = ? AND c.pid = p.id AND c.invisible != 1 AND (c.start = '' OR c.start <= ?) AND (c.stop = '' OR c.stop > ?)";
        $strNow = (string) time();

        return array_map('intval', $this->connection->fetchFirstColumn(
            sprintf(
                'SELECT p.id FROM %1$s p WHERE p.%2$s IS NULL AND p.%3$s = ?'
                . ' AND EXISTS (SELECT 1 FROM tl_content c WHERE %4$s)'
                . ' AND NOT EXISTS (SELECT 1 FROM tl_content c WHERE %4$s AND c.type NOT IN (?))',
                $this->connection->quoteIdentifier($strTable),
                $this->connection->quoteIdentifier($strColumn),
                $this->connection->quoteIdentifier($strSizeColumn),
                $strVisible,
            ),
            [self::FLUID_SIZE, self::PARENT_TABLES[$strTable], $strNow, $strNow, self::PARENT_TABLES[$strTable], $strNow, $strNow, self::TEXT_TYPES],
            [ParameterType::STRING, ParameterType::STRING, ParameterType::STRING, ParameterType::STRING, ParameterType::STRING, ParameterType::STRING, ParameterType::STRING, ArrayParameterType::STRING],
        ));
    }

    /**
     * Tables and columns that still hold at least one NULL container-padding value.
     *
     * @return array<string, list<string>>
     */
    private function findTargets(): array
    {
        $schemaManager = $this->connection->createSchemaManager();
        $arrTargets = [];

        foreach ($schemaManager->listTableNames() as $strTable) {
            $arrColumnNames = array_map(
                static fn ($col) => $col->getName(),
                $schemaManager->listTableColumns($strTable),
            );

            foreach (array_intersect(self::RELEVANT_COLUMNS, $arrColumnNames) as $strColumn) {
                $blnHasNull = (bool) $this->connection->fetchOne(
                    sprintf(
                        'SELECT TRUE FROM %s WHERE %s IS NULL LIMIT 1',
                        $this->connection->quoteIdentifier($strTable),
                        $this->connection->quoteIdentifier($strColumn),
                    ),
                );

                if ($blnHasNull) {
                    $arrTargets[$strTable][] = $strColumn;
                }
            }
        }

        return $arrTargets;
    }

    private function findSizeColumn(string $strTable): ?string
    {
        $arrColumnNames = array_map(
            static fn ($col) => $col->getName(),
            $this->connection->createSchemaManager()->listTableColumns($strTable),
        );

        foreach (self::SIZE_COLUMNS as $strColumn) {
            if (\in_array($strColumn, $arrColumnNames, true)) {
                return $strColumn;
            }
        }

        return null;
    }
}
