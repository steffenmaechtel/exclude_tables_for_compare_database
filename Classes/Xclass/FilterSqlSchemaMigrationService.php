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
}
