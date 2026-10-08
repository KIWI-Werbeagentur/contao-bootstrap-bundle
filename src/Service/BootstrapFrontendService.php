<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Contao\Controller;
use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\GridStyles;
use Kiwi\Contao\ResponsiveBaseBundle\Configuration\ResponsiveConfiguration;
use Kiwi\Contao\ResponsiveBaseBundle\Service\ResponsiveFrontendService;

class BootstrapFrontendService extends ResponsiveFrontendService
{
    /**
     * @param string|null $strData
     * @param mixed $varData
     * @return list<string>
     */
    public function getColClasses($strData, $varData = []): array
    {
        if(self::propExists($varData, 'ptable')) {
            Controller::loadDataContainer(self::getProp($varData, 'ptable'));

            if(!($GLOBALS['TL_DCA'][self::getProp($varData, 'ptable')]['fields']['responsiveRowCols'] ?? false)) {
                return $this->getResponsiveClasses($strData, 'varColClasses');
            }
        }
        if (self::propExists($varData, 'responsiveOverwriteRowCols') ? self::getProp($varData, 'responsiveOverwriteRowCols') : true) {
            return $this->getResponsiveClasses($strData, 'varColClasses');
        }
        return [];
    }

    /**
     * @param string|null $strData
     * @param mixed $varData
     * @return list<string>
     */
    public function getOffsetClasses($strData, $varData = []): array
    {
        if(self::propExists($varData, 'ptable')) {
            Controller::loadDataContainer(self::getProp($varData, 'ptable'));

            if(!($GLOBALS['TL_DCA'][self::getProp($varData, 'ptable')]['fields']['responsiveRowCols'] ?? false)) {
                return $this->getResponsiveClasses($strData, 'varOffsetClasses');
            }
        }
        if (self::propExists($varData, 'responsiveOverwriteRowCols') ? self::getProp($varData, 'responsiveOverwriteRowCols') : true) {
            return $this->getResponsiveClasses($strData, 'varOffsetClasses');
        }
        return [];
    }

    /**
     * @return list<string>
     */
    public function getRowColsClasses(string|null $strData): array
    {
        return $this->getResponsiveClasses($strData, 'varRowColsClasses');
    }

    /**
     * Bootstrap responsive horizontal-gutter utilities (gx-* per breakpoint). Vertical gutters are handled separately by the bundle.
     * Map is fully enumerated in {@see \Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration}.
     *
     * @param string $partial which gutter section the value belongs to (main/header/footer);
     *
     * @return list<string>
     *                         fills the {{partial}} placeholder so the `default` option resolves
     *                         to var(--kiwi-gutter-default-<partial>).
     */
    public function getGutterClasses(?string $strData, string $partial = 'main'): array
    {
        return $this->getResponsiveClasses($strData, 'varGutterClasses', ['partial' => $partial]);
    }

    /**
     * Bootstrap responsive row-gap utilities (row-gap-* per breakpoint). Output lands on the same
     * tag as `.row` via {@see self::getAllInnerContainerClasses()}.
     *
     * @return list<string>
     */
    public function getRowGapClasses(?string $strData): array
    {
        return $this->getResponsiveClasses($strData, 'varRowGapClasses');
    }

    /**
     * Container's own outer L/R padding utilities (.cx-* per breakpoint). Decoupled from the
     * inter-column gutter, so a container can have its own outer breathing room
     * independent of how its child columns are spaced.
     *
     * Map is fully enumerated in {@see \Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration}.
     *
     * @return list<string>
     */
    public function getContainerPaddingXClasses(mixed $varData, string $table = 'tl_article', string $strField = 'responsiveContainerPaddingX', bool $skipPaletteCheck = false): array
    {
        $type = self::getProp($varData, 'type') ?: null;
        if (!$this->isFieldInPalette($strField, $type, $table, $skipPaletteCheck)) {
            return [];
        }

        // Which section this field belongs to fills the {{partial}} placeholder, so the
        // dynamic `default` option resolves to var(--kiwi-container-padding-x-default-<partial>).
        $partial = match (true) {
            str_ends_with($strField, 'Header') => 'header',
            str_ends_with($strField, 'Footer') => 'footer',
            default => 'main',
        };

        return $this->getResponsiveClasses(self::getProp($varData, $strField), 'varContainerPaddingXClasses', ['partial' => $partial]);
    }

    /**
     * Bootstrap responsive vertical-spacing utilities (`pt-…`/`pb-…` per breakpoint).
     *
     * The vertical-spacing subsystem registers four partials (articleTop,
     * articleBottom, groupTop, groupBottom) so each DCA field resolves to its own
     * --kiwi-vertical-spacing-default-<partial> CSS variable. Article is the default
     * context: a bare (value, direction) call — the mod_article wrapper and
     * getSpacingTop/BottomClasses — resolves to the article partial. Element groups
     * call {@see self::getGroupSpacingClasses()} for the group partials.
     *
     * @return list<string>
     */
    public function getSpacingClasses(string|null $strData, string $strDirection = ""): array
    {
        return $this->resolveSpacingClasses(
            $strData,
            $strDirection,
            $strDirection === 'b' ? 'articleBottom' : 'articleTop',
        );
    }

    /**
     * Element-group vertical spacing — resolves to the group partials
     * (groupTop / groupBottom) instead of the article default.
     *
     * @return list<string>
     */
    public function getGroupSpacingClasses(string|null $strData, string $strDirection = ""): array
    {
        return $this->resolveSpacingClasses(
            $strData,
            $strDirection,
            $strDirection === 'b' ? 'groupBottom' : 'groupTop',
        );
    }
    /**
     * @return list<string>
     */
    private function resolveSpacingClasses(string|null $strData, string $strDirection, string $partial): array
    {
        // When this partial's configured default is `noop`, a field resolving to the
        // `default` option must also render nothing — exclude it alongside the noop
        // sentinel, so a default-left field on a noop partial produces no class.
        $excludeValues = [ResponsiveConfiguration::SPACING_NO_OP];
        if (BootstrapConfiguration::defaultIsNoOp('vertical-spacing', $partial)) {
            $excludeValues[] = GridStyles::GENERIC_DEFAULT;
        }

        return $this->getResponsiveClasses($strData, 'varSpacingClasses', [
            'direction' => $strDirection,
            'partial' => $partial,
            'excludeValues' => $excludeValues,
        ]);
    }

    /**
     * Convenience wrapper for the article top spacing DCA field.
     *
     * @return list<string>
     */
    public function getSpacingTopClasses(string|null $strData): array
    {
        return $this->getSpacingClasses($strData, 't');
    }

    /**
     * Convenience wrapper for the article bottom spacing DCA field.
     *
     * @return list<string>
     */
    public function getSpacingBottomClasses(string|null $strData): array
    {
        return $this->getSpacingClasses($strData, 'b');
    }

    /**
     * @param mixed $varData
     * @param array<string, string> $arrFields
     * @return list<string>
     */
    public function getAllContainerClasses($varData, array $arrFields = [], string $table = 'tl_article', bool $skipPaletteCheck = false): array
    {
        $arrSpecs = [
            ['containerPaddingX', 'responsiveContainerPaddingX',  'getContainerPaddingXClasses'],
        ];

        $type = self::getProp($varData, 'type') ?: null;

        $arrBootstrapClasses = [];
        foreach ($arrSpecs as [$strKey, $strDefaultField, $strMethod]) {
            $strField = $arrFields[$strKey] ?? $strDefaultField;
            if (!$this->isFieldInPalette($strField, $type, $table, $skipPaletteCheck)) {
                continue;
            }
            $arrBootstrapClasses = array_merge($arrBootstrapClasses, $this->$strMethod($varData, $table, $strField, $skipPaletteCheck));
        }

        return array_merge(parent::getAllContainerClasses($varData, $arrFields, $table, $skipPaletteCheck), $arrBootstrapClasses);
    }

    /**
     * @param mixed $varData
     * @param array<string, string> $arrFields
     * @return list<string>
     */
    public function getAllInnerContainerClasses($varData, array $arrFields = [], string $table = 'tl_content', bool $skipPaletteCheck = false): array
    {
        // Checked here as well: the fields below live in the same subpalette as the parent's, so
        // they must not render either while the selector is off - and the parent call would not
        // stop the row/gutter classes added afterwards.
        if ($this->hasChildrenSettingsDisabled($varData, $table, $skipPaletteCheck)) {
            return [];
        }

        $arrSpecs = [
            ['rowCols', 'responsiveRowCols', 'getRowColsClasses'],
            ['gutter',  'responsiveGutter',  'getGutterClasses'],
            ['rowGap',  'responsiveRowGap',  'getRowGapClasses'],
        ];

        $type = self::getProp($varData, 'type') ?: null;

        $arrBootstrapClasses = [];
        foreach ($arrSpecs as [$strKey, $strDefaultField, $strMethod]) {
            $strField = $arrFields[$strKey] ?? $strDefaultField;
            if (!$this->isFieldInPalette($strField, $type, $table, $skipPaletteCheck)) {
                continue;
            }
            $arrBootstrapClasses = array_merge($arrBootstrapClasses, $this->$strMethod(self::getProp($varData, $strField)));
        }

        $arrParentClasses = parent::getAllInnerContainerClasses($varData, $arrFields, $table, $skipPaletteCheck);

        // The structural `row` class enables the Bootstrap flex layout that makes the col-*,
        // flex-direction, justify-content, etc. utilities produced above and by the parent
        // take effect. Emit it only when at least one of those is actually being applied -
        // otherwise we would slap `.row` onto wrappers of element types that have no
        // responsive container settings at all.
        if ($arrBootstrapClasses || $arrParentClasses) {
            return array_merge(['row'], $arrBootstrapClasses, $arrParentClasses);
        }

        return [];
    }
}
