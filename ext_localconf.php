<?php
/**
 * @author Steffen Maechtel <info@steffen-maechtel.de>
 */

if (version_compare(TYPO3_version, '8.0.0', '>=')) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Database\\Schema\\ConnectionMigrator'] = array(
        'className' => 'SteffenMaechtel\\ExcludeTablesForCompareDatabase\\Xclass\\FilterConnectionMigrator'
    );
} else if (version_compare(TYPO3_version, '6.2.0', '>=') && version_compare(TYPO3_version, '7.6.999', '<')) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Install\\Service\\SqlSchemaMigrationService'] = array(
        'className' => 'SteffenMaechtel\\ExcludeTablesForCompareDatabase\\Xclass\\FilterSqlSchemaMigrationService'
    );
}