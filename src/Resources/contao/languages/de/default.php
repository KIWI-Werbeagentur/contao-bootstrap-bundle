<?php

//$GLOBALS['TL_LANG']['MSC']['pickFileButton'] = 'Datei auswählen';
//$GLOBALS['TL_LANG']['MSC']['pickFileButtonMultiple'] = 'Dateien auswählen';
$GLOBALS['TL_LANG']['MSC']['deleteColorConfirm'] = 'Soll das Element ID %s wirklich gelöscht werden? Das Löschen von Farbdefinitionen, die von einem Layout verwendet werden, führt zu Fehlern und nicht erreichbaren Seiten im Frontend!';

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_legend'] = 'Bootstrap';
$GLOBALS['TL_LANG']['bootstrap']['flex_legend'] = 'Flexbox';

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_preview'] = ['Spaltenvorschau', 'Hier sehen Sie eine Visualisierung der Spaltenbreite dieses Elements auf unterschiedlichen Geräten.'];
$GLOBALS['TL_LANG']['bootstrap']['bootstrap_preview_wrapper'] = ['Spaltenvorschau (Inhaltselemente)', 'Hier sehen Sie eine Visualisierung der Inhaltselemente dieses Elements auf unterschiedlichen Geräten. (Wird ggf. für einzelne Elemente überschrieben)'];
$GLOBALS['TL_LANG']['bootstrap']['bootstrap_preview_error'] = 'Keine Vorschau verfügbar. Eventuell ist Bootstrap im umschließenden Element deaktiviert?';

$GLOBALS['TL_LANG']['bootstrap']['col'] = ['Spaltenbreite', 'Geben Sie hier an, welche Spaltenbreite dieses Element je Gerät ausfüllen soll.'];
$GLOBALS['TL_LANG']['bootstrap']['bootstrap_default_columns'] = ['Standard-Spaltenkonfiguration (Inhaltselemente)', 'Geben Sie je Gerät an, wie die Inhaltselemente dieses Elements angezeigt werden sollen. (Kann für einzelne Elemente überschrieben werden)'];

$GLOBALS['TL_LANG']['bootstrap']['addBootstrap'] = ['Bootstrap-Grid verwenden', 'Aktivieren Sie diese Checkbox, um dem Element Bootstrap-Klassen hinzuzufügen.'];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_width'] = ['Container-Breite', 'Wählen Sie hier die Breite des Containers.'];
$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_width_reference'] = [
    'container' => 'Begrenzte Breite (.container)',
    'container-fluid' => 'Gesamte Breite (.container-fluid)',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_breakpoint'] = ['Container-Breakpoint', 'Wählen Sie, ab welcher Gerätebreite der Container begrenzt werden soll.'];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_breakpoint_options'] = [
    'sm' => 'ab Smartphone (horizontal)',
    'md' => 'ab Tablet',
    'lg' => 'ab Laptop',
    'xl' => 'ab PC',
    'xxl' => 'ab TV',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_devices'] = [
    "xs"    => "Smartphone (hoch)",
    "sm"    => "Smartphone (quer)",
    "md"    => "Tablet",
    "lg"    => "Laptop",
    "xl"    => "PC",
    "xxl"   => "TV",
];

$GLOBALS['TL_LANG']['bootstrap']['col_options'] = [
    12 => '12/12 Spalten (ganze Breite)',
    11 => '11/12 Spalten',
    10 => '10/12 Spalten',
    9  => '9/12 Spalten (drei Viertel Breite)',
    8  => '8/12 Spalten (zwei Drittel Breite)',
    7  => '7/12 Spalten',
    6  => '6/12 Spalten (halbe Breite)',
    5  => '5/12 Spalten',
    4  => '4/12 Spalten (ein Drittel Breite)',
    3  => '3/12 Spalten (ein Viertel Breite)',
    2  => '2/12 Spalten',
    1  => '1/12 Spalten',
];
$GLOBALS['TL_LANG']['bootstrap']['row_col_options'] = [
    1  => '1 pro Zeile (ganze Breite)',
    2  => '2 pro Zeile (halbe Breite)',
    3  => '3 pro Zeile (ein Drittel Breite)',
    4  => '4 pro Zeile (ein Viertel Breite)',
    5  => '5 pro Zeile',
    6  => '6 pro Zeile',
    7  => '7 pro Zeile',
    8  => '8 pro Zeile',
    9  => '9 pro Zeile',
    10 => '10 pro Zeile',
    11 => '11 pro Zeile',
    12 => '12 pro Zeile',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_headline_class'] = ['Überschrift-Stil', 'Im Stil einer anderen Überschrift-Klasse anzeigen'];
$GLOBALS['TL_LANG']['bootstrap']['bootstrap_headline_class_options'] = [
    'display-1' => 'Display 1',
    'display-2' => 'Display 2',
    'display-3' => 'Display 3',
    'display-4' => 'Display 4',
    'display-5' => 'Display 5',
    'display-6' => 'Display 6',
    'h1' => 'h1',
    'h2' => 'h2',
    'h3' => 'h3',
    'h4' => 'h4',
    'h5' => 'h5',
    'h6' => 'h6',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_direction'] = ['Flex-Richtung', 'Hier definieren Sie die Flussrichtung der Elemente innerhalb des Umschlagelements je Gerät.'];
$GLOBALS['TL_LANG']['bootstrap']['flex_direction_options'] = [
    'row' => 'Horizontal (row) &rarr;',
    'column' => 'Vertikal (column) &darr;',
    'row-reverse' => 'Horizontal umgekehrt (row-reverse) &larr;',
    'column-reverse' => 'Vertikal umgekehrt (column-reverse) &uarr;',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_wrap'] = ['Flex-Umbruch', 'Hier definieren Sie das Zeilenumbruchverhalten der Elemente innerhalb des Umschlagelements je Gerät.'];
$GLOBALS['TL_LANG']['bootstrap']['flex_wrap_options'] = [
    'wrap' => 'Automatisch (wrap)',
    'nowrap' => 'Kein Umbruch (nowrap)',
    'wrap-reverse' => 'Umgekehrt (wrap-reverse)',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_justify_content'] = ["Ausrichtung Hauptachse (justify-content)", "Hier definieren Sie die Ausrichtung bzw. Verteilung der Elemente innerhalb des Umschlagelements auf der Hauptachse je Gerät (&harr; bei 'row', &varr; bei 'column')."];
$GLOBALS['TL_LANG']['bootstrap']['flex_justify_content_options'] = [
    'start' => 'Anfang',
    'end' => 'Ende',
    'center' => 'Zentriert',
    'between' => 'Verteilt',
    'around' => 'Verteilt (mit Abstand)',
    'evenly' => 'Verteilt (gleichmäßig)',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_align_items'] = ["Ausrichtung Gegenachse (align-items)", "Hier definieren Sie die Ausrichtung bzw. Verteilung der Elemente innerhalb des Umschlagelements auf der Gegenachse je Gerät (&varr; bei 'row', &harr; bei 'column')."];
$GLOBALS['TL_LANG']['bootstrap']['flex_align_items_options'] = [
    'stretch' => 'Gestreckt',
    'start' => 'Anfang',
    'end' => 'Ende',
    'center' => 'Zentriert',
    'baseline' => 'Auf der Textlinie',
];

$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_align_content'] = ["Ausrichtung Zeilen/Spalten (align-content)", "Hier definieren Sie die Ausrichtung bzw. Verteilung der Zeilen ('row') bzw. Spalten ('column') innerhalb des Umschlagelements je Gerät."];
$GLOBALS['TL_LANG']['bootstrap']['flex_align_content_options'] = [
    'start' => 'Anfang',
    'end' => 'Ende',
    'center' => 'Zentriert',
    'between' => 'Verteilt',
    'around' => 'Verteilt (mit Abstand)',
    'stretch' => 'Gestreckt',
];
