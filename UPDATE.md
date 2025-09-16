## 6.4.*

Unterstützt Contao 5 und Twig-Templates

Mit dieser Version werden in Contao 5 Wrapper zu Elementgruppen migriert. Dies kann durch eine .env-Variable unterbunden werden.

### Potentielle Layoutänderungen
- Bei einigen verschachtelten `.container*`-Klassen wird nicht mehr fälschlicherweise das horizontale Padding auf 0 gesetzt.
- `.ce_wrapperStart` erhält jetzt immer `.row`, die flex-Klassen und Hintergrund-Ausrichtungs-Klassen, unabhängig von der Option "Als Container benutzen".

## 4.2.*

Version 4.2 fügt an einigen Stellen `.col`s, `.container` und `.row`s hinzu um Fehler und Inkonsistenzen im Markup zu beseitigen. Dies sollte i.d.R. keine Auswirkungen aufs Layout haben, kann aber ggf. Probleme bereiten, wenn in eigenem CSS oder Templates Workarounds umgesetzt wurden.

### Änderungen am Layout
 * `#main` hat jetzt immer eine `.col-*`-Klasse, auch wenn es keine Seitenspalten gibt
 * `#main > .inside` ist jetzt immer eine `.row`
 * `#header`, `#container` und `#footer` bekommen jetzt immer eine Container-Klasse, es gibt keine "leere" Option mehr
 * Daraus folgend gibt es immer die Elemente `#header > .inside > .row`, `#container > .row` und `#footer > .inside > .row`

 ### Änderungen am Artikel
  * `.mod_article` bekommt jetzt immer eine Container-Klasse, es gibt keine "leere" Option mehr
  * Daraus folgend gibt es jetzt immer ein Element `.mod_article > .row`
  * Das bedeutet für die Option "Komplettes Browserfenster - Content feste Breite" ist das Markup jetzt `.mod_article.container-fluid > .row > .inside.container > .row` statt `.mod_article > .inside.container > .row`
