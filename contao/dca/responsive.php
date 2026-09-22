<?php

use Contao\System;

System::loadLanguageFile('responsive');

$GLOBALS['TL_DCA']['rowCols']['fields']['responsiveRowCols'] = [
    'default' => ['xs' => 1],
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'],
    'inputType' => 'responsive',
    'responsiveInputType' => 'iconedSelect',
    'eval' => ['tl_class' => "clr"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getRowCols'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols']['options'],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['responsiveInputType'] = 'iconedSelect';
$GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['reference'] = &$GLOBALS['TL_LANG']['responsive']['responsiveOrder']['options'];
$GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['options'] = ['0',1,2,3,4,5,6,'first','last'];
unset($GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['eval']['rgxp']);

$GLOBALS['TL_DCA']['container']['fields']['responsiveGutter'] = [
    'default' => ['xs' => 'default'],
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutter'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'iconedSelect',
    'options_callback' => [$GLOBALS['responsive']['config'], 'getGutterSizeKeys'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options'],
    // Leaving the gutter unset is a legitimate state: `.row` already carries
    // --bs-gutter-x, so no class means the framework default applies. Offer the blank
    // option so the base breakpoint can express that, instead of being forced onto a
    // concrete value it never had. NOT offered for container padding, where an unset
    // value has no fallback left to fall back to.
    'eval' => ['tl_class' => 'clr w50', 'includeBlankOption' => true],
    'sql' => 'blob NULL',
];

$GLOBALS['TL_DCA']['container']['fields']['responsiveRowGap'] = [
    'default' => ['xs' => 'default'],
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowGap'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'iconedSelect',
    'options_callback' => [$GLOBALS['responsive']['config'], 'getRowGapKeys'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowGap']['options'],
    // Row gap has no predecessor at all - a project upgrading into it spaces its
    // elements some other way - so "unset" must stay expressible and must survive a save.
    'eval' => ['tl_class' => 'w50', 'includeBlankOption' => true],
    'sql' => 'blob NULL',
];

/* Container's own outer L/R padding, independent of the inter-column gutter
 * (responsiveGutter). Emits .cx-* utilities (opt-in, fluid-gated); see
 * grid-overrides.scss.
 *
 * IMPORTANT: this field is intentionally NOT part of the generic `container`
 * field group. The `container` group auto-merges into content elements and
 * form fields (see contao-responsive-base tl_content.php / tl_form_field.php),
 * which must NOT carry a container-padding option. It lives under its own
 * `containerPadding` bucket and is added explicitly only where wanted:
 *   - tl_article  (this bundle's tl_article.php)
 *   - tl_layout   header/footer (this bundle's tl_layout.php) */
$GLOBALS['TL_DCA']['containerPadding']['fields']['responsiveContainerPaddingX'] = [
    'default' => ['xs' => 'default'],
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'iconedSelect',
    'options_callback' => [$GLOBALS['responsive']['config'], 'getContainerPaddingXKeys'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX']['options'],
    'eval' => ['tl_class' => 'clr w50'],
    'sql' => 'blob NULL',
];

/* Vertical content spacing. The responsive-base bundle ships these four fields
 * (article top/bottom + element-group top/bottom) with a shared `spacings`
 * reference, so the `default` option label is the same for all four. We override
 * each field to point at its own per-partial options array (populated by the
 * bundle's language file), so each field shows only the value relevant to its
 * partial — the same pattern the gutter / container-padding-x header/footer
 * fields use. */
$GLOBALS['TL_DCA']['space']['fields']['responsiveSpacingTop'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveSpacingTop'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'iconedSelect',
    'eval' => ['tl_class' => "w50 clr"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getSpacings'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveSpacingTop']['options'],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA']['space']['fields']['responsiveSpacingBottom'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveSpacingBottom'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'iconedSelect',
    'eval' => ['tl_class' => "w50"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getSpacings'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveSpacingBottom']['options'],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA']['elementGroupSpace']['fields']['responsiveGroupSpacingTop'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveSpacingTop'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'iconedSelect',
    'eval' => ['tl_class' => "w50 clr"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getSpacings'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveGroupSpacingTop']['options'],
    'sql' => "blob NULL",
];

$GLOBALS['TL_DCA']['elementGroupSpace']['fields']['responsiveGroupSpacingBottom'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveSpacingBottom'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'iconedSelect',
    'eval' => ['tl_class' => "w50"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getSpacings'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveGroupSpacingBottom']['options'],
    'sql' => "blob NULL",
];
