<?php
namespace SteffenMaechtel\ExcludeTablesForCompareDatabase\Xclass;

use TYPO3\CMS\Install\Service\SqlSchemaMigrationService;

class FilterSqlSchemaMigrationService extends SqlSchemaMigrationService
{
    /**
     * 
     * @overload
     */
    public function getUpdateSuggestions($diffArr, $keyList = 'extra,diff') {

        $excludeTables = $this->getExcludeTables();

        if (count($excludeTables) === 0) {
            return parent::getUpdateSuggestions($diffArr, $keyList);
        }

        foreach ($diffArr['extra'] as $tableName => $table) {
            foreach ($excludeTables as $excludeTable) {
                if (fnmatch($excludeTable, $tableName)) {
                    unset($diffArr['extra'][$tableName]);
                }
            }
        }
        
        return parent::getUpdateSuggestions($diffArr, $keyList);
    }
    
    /**
     * @return array
     */
    protected function getExcludeTables()
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
