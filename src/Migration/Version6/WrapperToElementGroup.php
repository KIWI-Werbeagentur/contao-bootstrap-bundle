<?php

namespace Kiwi\Contao\BootstrapBundle\Migration\Version6;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;

class WrapperToElementGroup extends AbstractMigration
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getName(): string
    {
        return 'BootstrapBundle: convert Wrapper elements to ElementGroup elements';
    }

    public function shouldRun(): bool
    {
        if (version_compare(ContaoCoreBundle::getVersion(), "5", "<")) {
            return false;
        }

        if (!empty($_ENV['BOOTSTRAP_NO_WRAPPER_MIGRATION'])) {
            return false;
        }

        $sm = $this->connection->createSchemaManager();

        if (!$sm->tablesExist('tl_content')) {
            return false;
        }

        $count = $this->connection->executeQuery("SELECT COUNT(`id`) FROM `tl_content` WHERE `invisible` = 0 AND (`type` = 'wrapperStart' OR `type` = 'WrapperStop')")->fetchOne();

        return (bool)$count;
    }

    public function run(): MigrationResult
    {
        /* get things to migrate */
        $arrParents = $this->connection->executeQuery("SELECT DISTINCT pid, ptable FROM `tl_content` WHERE `invisible` = 0 AND (`type` = 'wrapperStart' OR `type` = 'wrapperStop')")->fetchAllAssociative();

        $arrProblems = [];
        $arrNestedWrapperInfo = [];
        // get all children and build a graph of their wrapper structures (consider invisible status)
        foreach ($arrParents as $arrParent) {
            $result = $this->connection->prepare("SELECT id, type, pid, ptable, sorting, invisible, cssID, start, stop FROM `tl_content` WHERE `pid` = :pid AND `ptable` = :ptable ORDER BY sorting")->executeQuery(['pid' => $arrParent['pid'], 'ptable' => $arrParent['ptable']]);
            $arrChildren = $result->fetchAllAssociative();

            $nesting = 0;
            $tmp = [];
            foreach ($arrChildren as $arrChild) {
                if ('wrapperStart' === $arrChild['type'] && !$arrChild['invisible'] && ('' === $arrChild['start'] || time() >= $arrChild['start']) && ('' === $arrChild['stop'] || time() < $arrChild['stop'])) {
                    if ($nesting > 0) {
                        $tmp[$nesting][count($tmp[$nesting]) - 1]['elements'][] = $arrChild;
                    }
                    $nesting++;
                    $tmp[$nesting][] = [
                        'start' => $arrChild,
                        'elements' => [],
                    ];
                } elseif ('wrapperStop' === $arrChild['type'] && !$arrChild['invisible'] && ('' === $arrChild['start'] || time() >= $arrChild['start']) && ('' === $arrChild['stop'] || time() < $arrChild['stop'])) {
                    if (0 === $nesting) {
                        // unmatched stop element
                        $tmp = [];
                        break;
                    }
                    $tmp[$nesting][count($tmp[$nesting]) - 1]['stop'] = $arrChild;
                    $nesting--;
                } elseif ($nesting > 0) {
                    $tmp[$nesting][count($tmp[$nesting]) - 1]['elements'][] = $arrChild;
                }
            }
            if (!empty($tmp[1]) && empty($tmp[1][count($tmp[1]) - 1]['stop'])) {
                $tmp = [];
            }
            if ($tmp) {
                foreach ($tmp as $nesting => $nestedWrappers) {
                    foreach ($nestedWrappers as $wrapper) {
                        $arrNestedWrapperInfo[$nesting][] = $wrapper;
                    }
                }
            } else {
                // irgendwas ist hier falsch
                $arrProblems[] = $arrParent['ptable'] . '-' . $arrParent['pid'];
            }
        }

        /* actually migrate */
        $numMigrated = 0;
        // loop over actual wrappers, from deepest to shallowest nesting
        $nesting = count($arrNestedWrapperInfo);
        while ($nesting > 0) {
            foreach ($arrNestedWrapperInfo[$nesting] as $wrapper) {
                // change wrapper start to element group
                $cssId = StringUtil::deserialize($wrapper['start']['cssID'], true) ?: ['', ''];
                $cssId[1] = trim('ce_wrapper ce_wrapperStart ' . $cssId[1]);
                $this->connection->prepare("UPDATE `tl_content` set `type` = 'element_group', `cssID` = :cssId WHERE `id` = :id")->executeQuery(['cssId' => serialize($cssId), 'id' => $wrapper['start']['id']]);

                // update the children to be actual children of the new element group
                if ($wrapper['elements']) {
                    $arrIds = array_map(fn($el) => $el['id'], $wrapper['elements']);
                    $this->connection->prepare("UPDATE `tl_content` set `ptable` = 'tl_content', pid = :pid WHERE `id` IN (" . join(',', $arrIds) . ")")->executeQuery(['pid' => $wrapper['start']['id']]);
                }

                // delete the wrapper stop
                $this->connection->prepare("DELETE FROM `tl_content` WHERE id = :id")->executeQuery(['id' => $wrapper['stop']['id']]);

                $numMigrated++;
            }

            $nesting--;
        }

        $message = 'Migrated ' . $numMigrated . ' Wrapper elements.';

        if ($arrProblems) {
            $message .= ' Some Wrapper elements could not be migrated automatically. Please review them manually, they are children of the following data structures: ' . join(', ', $arrProblems);

            return $this->createResult(false, $message);
        }

        return $this->createResult(true, $message);
    }
}
