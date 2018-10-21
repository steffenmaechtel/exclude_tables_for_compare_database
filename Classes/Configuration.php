<?php
namespace SteffenMaechtel\ExcludeTablesForCompareDatabase;

/**
 * @author Steffen Maechtel <info@steffen-maechtel.de>
 */
class Configuration
{

    /**
     * @return array
     */
    public function getExcludeTables()
    {
        $configuration = $this->getConfiguration();

        if (empty($configuration) || isset($configuration['excludeTables']) === false) {
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

    /**
     * @return bool
     */
    public function isDebug()
    {
        /**
         * currenty not working for typo3 9.x (ajax response)
         */
        if (version_compare(TYPO3_version, '9.0.0', '>=')) {
            return false;
        }

        $configuration = $this->getConfiguration();

        if ($configuration['debug']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    protected function getConfiguration()
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['exclude_tables_for_compare_database']) === false) {
            return [];
        }

        $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['exclude_tables_for_compare_database']);

        return $configuration;
    }
}
