<?php
/**
 * @author Steffen Maechtel <info@steffen-maechtel.de>
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Exclude tables for compare database',
    'description' => 'Make it possible to define a list of tables, which are excluded in install tool function "compare database"',
    'category' => 'be',
    'version' => '0.0.2',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'author' => 'Steffen Maechtel',
    'author_email' => 'info@steffen-maechtel.de',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-8.7.999',
            'php' => '5.5.0-7.2.999'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
   'psr-4' => [
      'SteffenMaechtel\\ExcludeTablesForCompareDatabase\\' => 'Classes'
   ]
],
];
