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
}
