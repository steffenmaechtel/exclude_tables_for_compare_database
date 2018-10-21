<?php
declare(strict_types = 1);
namespace SteffenMaechtel\ExcludeTablesForCompareDatabase\Xclass;

/**
 * @author Steffen Maechtel <info@steffen-maechtel.de>
 */
use Doctrine\DBAL\Schema\SchemaDiff;
use SteffenMaechtel\ExcludeTablesForCompareDatabase\Configuration;
use TYPO3\CMS\Core\Database\Schema\ConnectionMigrator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FilterConnectionMigrator extends ConnectionMigrator
{

    /**
     * @overload
     */
    protected function buildSchemaDiff(bool $renameUnused = true): SchemaDiff
    {
        $schemaDiff = parent::buildSchemaDiff($renameUnused);

        $configuration = GeneralUtility::makeInstance(Configuration::class);

        $excludeTables = $configuration->getExcludeTables();

        if (count($excludeTables) === 0) {
            if ($configuration->isDebug()) {
                ob_clean();
                echo '<div style="background: pink; border: 1px solid red; padding: 10px; margin: 10px;">';
                echo '<h1>Debug output for extension: exclude_tables_for_compare_database</h1>';
                echo '<p>Configuration (EMPTY! Not tables defined):</p>';
                echo '<pre style="padding: 10px; margin: 10px;">';
                print_r($excludeTables);
                echo '</pre>';
                echo '</div>';
            }
            return $schemaDiff;
        }

        if ($configuration->isDebug()) {
            $excludeTablesDebugInformation = [];
        }

        foreach (['newTables', 'changedTables', 'removedTables'] as $property) {
            foreach ($schemaDiff->{$property} as $tableName => $table) {
                foreach ($excludeTables as $excludeTable) {
                    if (fnmatch($excludeTable, $tableName)) {
                        unset($schemaDiff->{$property}[$tableName]);
                        if ($configuration->isDebug()) {
                            $excludeTablesDebugInformation[$excludeTable][] = $tableName;
                        }
                    }
                }
            }
        }

        if ($configuration->isDebug()) {
            ob_clean();
            echo '<div style="background: pink; border: 1px solid red; padding: 10px; margin: 10px;">';
            echo '<h1>Debug output for extension: exclude_tables_for_compare_database</h1>';
            echo '<p>Configuration:</p>';
            echo '<pre style="padding: 10px; margin: 10px;">';
            print_r($excludeTables);
            echo '</pre>';
            echo '<p>Excluded tables grouped by matching filter:</p>';
            echo '<pre style="padding: 10px; margin: 10px;">';
            print_r($excludeTablesDebugInformation);
            echo '</pre>';
            echo '</div>';
        }

        return $schemaDiff;
    }
}
