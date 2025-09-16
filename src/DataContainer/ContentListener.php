<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\Database;
use Contao\DataContainer;

class ContentListener
{
    /**
     * Get the wrapper ID, if this element is inside a wrapper object ($id=0, if not inside wrapper)
     * @param int $id ID of this element
     */
    public function updateLevel(int $id)
    {
        $article_id = Database::getInstance()->prepare("SELECT pid FROM tl_content WHERE id=?")->execute($id)->pid;
        $articleElements = Database::getInstance()->prepare("SELECT * FROM tl_content WHERE pid=? ORDER BY sorting")->execute($article_id)->fetchAllAssoc();
        $level = 0;
        $wrapper_id[$level] = 0;
        foreach ($articleElements as $element) {
            if (strpos($element['type'], "Stop") !== false && !$element['invisible'] && $level !== 0) {
                $level--;
            }
            $set['wrapper_id'] = $wrapper_id[$level];
            $set['level'] = $level;
            Database::getInstance()->prepare("UPDATE tl_content %s WHERE id=?")->set($set)->execute($element['id']);
            if (strpos($element['type'], "Start") !== false && !$element['invisible']) {
                $level++;
                $wrapper_id[$level] = $element['id'];
            }
        }
    }

    /**
     * Executed when a new record is created.
     * @param string $table Table
     * @param int $id Insert ID
     * @param array $fields Fields of the new record
     * @param DataContainer $dc Data Container object
     */
    public function onCreateUpdateLevel(string $table, int $id, array $fields, DataContainer $dc)
    {
        $this->updateLevel($id);
    }

    /**
     * Executed when a back end form is submitted after the record has been updated in the database. Allows you to e.g. modify the record afterwards (used to calculate intervals in the calendar extension).
     * @param DataContainer $dc Data Container object
     */
    public function onSubmitUpdateLevel(DataContainer $dc)
    {
        $this->updateLevel($dc->id);
    }

    /**
     * Executed before a record is removed from the database.
     * @param DataContainer $dc Data Container object
     * @param int $id The ID of the tl_undo database record
     */
    public function onDeleteUpdateLevel(DataContainer $dc, int $undo_id)
    {
        $article_id = Database::getInstance()->prepare("SELECT pid FROM tl_content WHERE id=?")->execute($dc->id)->pid;
        $articleElements = Database::getInstance()->prepare("SELECT * FROM tl_content WHERE pid=? ORDER BY sorting")->execute($article_id)->fetchAllAssoc();
        $level = 0;
        $wrapper_id[$level] = 0;
        foreach ($articleElements as $element) {
            if ($element['id'] !== $dc->id) {
                if (strpos($element['type'], "Stop") !== false && $level !== 0) {
                    $level--;
                }
                $set['wrapper_id'] = $wrapper_id[$level];
                $set['level'] = $level;
                Database::getInstance()->prepare("UPDATE tl_content %s WHERE id=?")->set($set)->execute($element['id']);
                if (strpos($element['type'], "Start") !== false) {
                    $level++;
                    $wrapper_id[$level] = $element['id'];
                }
            }
        }
    }

    /**
     * Is executed after a record has been moved to a new position.
     * @param DataContainer $dc Data Container object
     */
    public function onCutUpdateLevel(DataContainer $dc)
    {
        $this->updateLevel($dc->id);
    }

    /**
     * Executed after a record has been duplicated.
     * @param string $id Insert ID
     * @param DataContainer $dc Data Container object
     */
    public function onCopyUpdateLevel(string $id, DataContainer $dc)
    {
        $this->updateLevel($id);
    }

    /**
     * Executed after a deleted record has been restored from the “undo” table.
     * @param string $table Table
     * @param array $data Record data
     * @param DataContainer $dc Data Container object
     */
    public function onUndoUpdateLevel(string $table, array $data, DataContainer $dc)
    {
        $this->updateLevel($data['id']);
    }
}
