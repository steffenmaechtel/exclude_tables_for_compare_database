<?php
/**
 * @author Steffen Maechtel <info@steffen-maechtel.de>
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Database\\Schema\\ConnectionMigrator'] = array(
    'className' => 'SteffenMaechtel\\ExcludeTablesForCompareDatabase\\Xclass\\FilterConnectionMigrator'
);
