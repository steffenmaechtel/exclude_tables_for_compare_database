<?php
namespace SteffenMaechtel\ExcludeTablesForCompareDatabase\Xclass;

/**
 * @author Steffen Maechtel <info@steffen-maechtel.de>
 */
use SteffenMaechtel\ExcludeTablesForCompareDatabase\Configuration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Service\SqlSchemaMigrationService;

class FilterSqlSchemaMigrationService extends SqlSchemaMigrationService
{

    /**
     * @overload
     */
    public function getUpdateSuggestions($diffArr, $keyList = 'extra,diff')
    {
        $configuration = GeneralUtility::makeInstance(Configuration::class);

        $excludeTables = $configuration->getExcludeTables();

        if (count($excludeTables) === 0) {
            if ($configuration->isDebug()) {
                echo '<div style="background: pink; border: 1px solid red; padding: 10px; margin: 10px;">';
                echo '<h1>Debug output for extension: exclude_tables_for_compare_database</h1>';
                echo '<p>Configuration (EMPTY! Not tables defined):</p>';
                echo '<pre style="padding: 10px; margin: 10px;">';
                print_r($excludeTables);
                echo '</pre>';
                echo '</div>';
            }
            return parent::getUpdateSuggestions($diffArr, $keyList);
        }

        if ($configuration->isDebug()) {
            $excludeTablesDebugInformation = [];
        }

        foreach ($diffArr['extra'] as $tableName => $table) {
            foreach ($excludeTables as $excludeTable) {
                if (fnmatch($excludeTable, $tableName)) {
                    unset($diffArr['extra'][$tableName]);
                    if ($configuration->isDebug()) {
                        $excludeTablesDebugInformation[$excludeTable][] = $tableName;
                    }
                }
            }
        }

        if ($configuration->isDebug()) {
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

        return parent::getUpdateSuggestions($diffArr, $keyList);
    }
}
