<?php
declare(strict_types = 1);
namespace SteffenMaechtel\ExcludeTablesForCompareDatabase\Xclass;

/**
 * @author Steffen Maechtel <info@steffen-maechtel.de>
 */

use Doctrine\DBAL\Schema\SchemaDiff;
use TYPO3\CMS\Core\Database\Schema\ConnectionMigrator;

class FilterConnectionMigrator extends ConnectionMigrator
{

    /**
     * @overload
     */
    protected function buildSchemaDiff(bool $renameUnused = true): SchemaDiff
    {
        $schemaDiff = parent::buildSchemaDiff($renameUnused);

        $excludeTables = $this->getExcludeTables();

        if (count($excludeTables) === 0) {
            return $schemaDiff;
        }

        foreach (['newTables', 'changedTables', 'removedTables'] as $property) {
            foreach ($schemaDiff->{$property} as $tableName => $table) {
                foreach ($excludeTables as $excludeTable) {
                    if (fnmatch($excludeTable, $tableName)) {
                        unset($schemaDiff->{$property}[$tableName]);
                    }
                }
            }
        }

        return $schemaDiff;
    }

    /**
     * @return array
     */
    protected function getExcludeTables(): array
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['exclude_tables_for_compare_database']) === false) {
            return [];
        }

        $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['exclude_tables_for_compare_database']);

        if (isset($configuration['excludeTables']) === false) {
            return [];
        }
        
        $excludeTables = [];
        $excludeTablesRaw = explode(',', $configuration['excludeTables']);
        
        foreach ($excludeTablesRaw as $excludeTableRaw) {
            $excludeTable = trim($excludeTableRaw);
            if ($excludeTable === '') {
                continue;
            }
            
            $excludeTables[] = $excludeTable;
        }
        
        return $excludeTables;
    }
}
