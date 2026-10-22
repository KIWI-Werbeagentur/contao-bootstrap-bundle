<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Kiwi\Contao\ResponsiveBaseBundle\Migration\DelegatedModuleColumnsMigration as BaseMigration;

/**
 * Adds this bundle's column gate to the delegated-module repair - see {@see BaseMigration}
 * for how the rendered module is resolved and why the repair is needed.
 *
 * On tl_content `responsiveOverwriteRowCols` is not an independent field but the selector
 * whose subpalette holds responsiveCols and responsiveOffsets:
 *
 *     $GLOBALS['TL_DCA']['tl_content']['subpalettes']['responsiveOverwriteRowCols']
 *         = 'responsiveCols,responsiveOffsets';
 *
 * so on an install with this bundle the flag and the values are one setting. Restoring the
 * values without the flag leaves them neither rendered - the element defers to its parent's
 * "elements per row" instead - nor editable, the subpalette being collapsed; setting the flag
 * without the values reveals whatever stale content was there. They have to be written
 * together, which is why this is a subclass replacing the parent's service rather than a
 * second migration running beside it.
 *
 * Overwriting an empty flag is safe because it never decided what these elements rendered.
 * The checkbox was visible and editable in the backend, but before responsive-base 1.1 a
 * delegated module took its column classes from its own tl_module row: the include
 * element's flag, cols and offsets never reached the output. Since 1.1 the include element
 * is the source, and an empty flag now suppresses its columns in favour of the parent's
 * "elements per row" - the regression this migration repairs. Writing the flag together
 * with the module's values reproduces what was rendered before, which is the only intent
 * that existing content ever expressed.
 */
class DelegatedModuleColumnsMigration extends BaseMigration
{
    public function getName(): string
    {
        return 'Kiwi Bootstrap: restore delegated module column settings on their include elements';
    }

    protected function getValuesToWrite(array $arrResolved): array
    {
        return parent::getValuesToWrite($arrResolved) + ['responsiveOverwriteRowCols' => '1'];
    }

    protected function getRequiredContentColumns(): array
    {
        return [...parent::getRequiredContentColumns(), 'responsiveOverwriteRowCols'];
    }
}
