# KIWI.Contao BootstrapBundle

## Branches & Versionen

Version 6 mit Bootstrap 5 wird aufgrund tiefgreifender Designänderungen voraussichtlich inkompatibel sein, eine Upgrade-Möglichkeit von vorherigen Versionen ist aktuell nicht vorgesehen. Ab 6.4 ist diese Version sowohl mit Contao 4.13 als auch 5.3 kompatibel.

Update von 4.x auf 5.2, sowie von 3.0.x auf 3.1.x beim Contao-Update von 4.4 auf 4.9 ist problemlos möglich, die Versionen sind funktional identisch. Gleiches gilt für das Update von 5.2 auf 5.3 beim Contao-Update von 4.9 auf 4.13.

Update von 3.x auf 4.x/5.x ist nicht zu empfehlen, aufgrund massiver Änderungen an der Datenbankstruktur.

Update von 2.x auf 3.x sollte möglich sein, evtl. Template-Anpassungen notwendig um neue Funktionen zu nutzen.

| Contao | Bootstrap | Bundle   | Branch               |
|--------|----|----------|----------------------|
| 5.3    | 5  | `^6.4`   | v6.4 (in dev)        |
| 4.13   | 5  | `^6.0`   | v6                   |
| Contao | Bootstrap | Bundle   | Branch               |
| 4.13   | 4  | `^5.3`   | legacy-v5.3-c413-bs4 |
| 4.9    | 4  | `~5.2.*` | legacy-v5.2-c49-bs4  | 
| 4.4    | 4  | `^4.0`   | legacy-c44-bs4       |
| Contao | Bootstrap | Bundle   | Branch               |
| 4.13   | 3  | `~3.2.*` | legacy-v3.2-c413     |
| 4.9    | 3  | `~3.1.*` | legacy-v3.1-c49      |
| 4.4    | 3  | `~3.0.*` | legacy-v3            |
| 4.4    | 3  | `^2.0`   | legacy-v2            |

## Features & Funktionen

### Erweiterung von Core und externen Erweiterungen
- Container-Klassen zu Header, Footer und Hauptbereich hinzufügen
- Mehrspaltigkeit des Hauptbereichs per Bootstrap-Grid
- Container-Klassen zu Artikeln hinzufügen
- Automatisch generierte Ordnerstruktur für Themes und Layouts
- Templates mit Container-Klassen für eigene Layout-Bereiche
- Pagination-Template
- Automatische Einbindung von Bootstrap

- Grid-Klassen zu allen Modulen (außer HTML) hinzufügen
  - Grid-Klassen zu den einzelnen Items von News- und Event-Liste hinzufügen

- Grid-Klassen zu allen Elementen (außer HTML und Kiwislider*) hinzufügen
- Textausrichtungs-Klassen zu allen Elementen (außer Zeilenumbruch) hinzufügen
- Hyperlinks als Buttons darstellen
- Container-Klassen zum Wrapper hinzufügen
- Ausdehnung von Hintergrundbildern im Wrapper steuern

- Grid-Klassen zu allen Formular-Elementen (außer HTML, Fieldset Ende und Hidden) hinzufügen
- Hilfetext zu allen Formular-Elementen (außer HTML, Erklärung, Fieldsets, Hidden und Submit) hinzufügen
- Text (z.B. für Währungen, Maßangaben etc.) zu Text-, Textarea und Select-Elementen hinzufügen
- Möglichkeit Checkboxen und Radios horizontal anzuordnen
- Bootstrap's Custom Styles für Radio, Checkbox, Select und File
- Submit als Button darstellen

### Eigene Elemente / Module
- Headline (bis v5.x)
  - Möglichkeit Überschriften für SEO-Zwecke nicht als `h*` Tag zu rendern
  - Möglichkeit `.display-*` Klassen einzufügen
- Bootstrap Zeilenumbruch
- Bootstrap Navbar
  - Pflegbares Logo, mit Startseite verlinkt
  - Navigationsmodul einbindbar
  - Zusätzlicher Content (html / insertTags)
  - Konfigurierbarer Umbruch normale / Burgernavi


## Installation

#### Composer-Repository
Ggf. muss das [KIWI.Satis](https://satis.kiwi.de) noch [als Repository eingerichtet](https://satis.kiwi.de) und [die Zugangsdaten hinterlegt](https://satis.kiwi.de) werden.

#### Installation per SSH
Das Bundle per composer installieren:
```sh
composer require kiwi/contao-bootstrap-bundle
```

#### Installation per Contao Manager
Das Bundle in der gewünschten Version manuell in die `composer.json` des Projektes eintragen:
```json
{
    "require": {
        "kiwi/contao-bootstrap-bundle": "^6.0"
    }
}
```
Mit dem Contao Manager installieren ("Pakete aktualisieren").

## Konfiguration

Wenn aus Kompatibilitätsgründen mit z.B. lokalen Erweiterungen die Wrapper nicht zu Elementgruppen migriert werden sollen, kann dies über eine .env-Variable gesteuert werden.
```dotenv
BOOTSTRAP_NO_WRAPPER_MIGRATION=1
```

## Bootstrap in Contao nutzen
Bootstrap wird standardmäßig automatisch eingebunden, über einen Eintrag in der CSS-Framework-Liste im Layout.

Für jedes Theme wird ein Ordner `files/themes/<THEME-ALIAS>` mit folgenden Dateien erstellt:
- `custom-variables.scss` Bootstrap-Variablen für alle Layouts des Themes
- `styles.scss` eigenes CSS für alle Layouts des Themes

Für jedes Layout wird ein Ordner `files/themes/<THEME-ALIAS>/<LAYOUT-ALIAS>` mit folgenden Dateien erstellt:
- `custom-variables.scss` Bootstrap-Variablen für das Layout
- `custom-bootstrap.scss` Einbindung von Bootstrap- und Kiwi-eigenen Komponenten
- `styles.scss` eigenes CSS für das Layout
