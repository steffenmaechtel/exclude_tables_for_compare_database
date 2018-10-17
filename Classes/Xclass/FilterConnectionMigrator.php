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

        if (is_array($GLOBALS['TYPO3_CONF_VARS']['excludeTables']) === false) {
            return;
        }
        
            /*$ignoreTables = [
            'tx_*',
            'tx_realurl_urldata',
            '___*'
        ];*/

        foreach (['newTables', 'changedTables', 'removedTables'] as $property) {
            foreach ($schemaDiff->{$property} as $tableName => $table) {
                foreach ($ignoreTables as $ignore) {
                    if (fnmatch($ignore, $tableName)) {
                        unset($schemaDiff->{$property}[$tableName]);
                    }
                }
            }
        }

        return $schemaDiff;
    }
}
