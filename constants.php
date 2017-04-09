<?php
/**
 * PHP_BS_GRID constants
 */

// pagination
define('C_PHP_BS_GRID_DEFAULT_PAGE_NUM', 1);
define('C_PHP_BS_GRID_DEFAULT_ROWS_PER_PAGE', 10);

// show more or less (default) columns
define('C_PHP_BS_GRID_COLUMNS_DEFAULT', 1);
define('C_PHP_BS_GRID_COLUMNS_MORE', 2);

// advanced sorting
define('C_PHP_BS_GRID_SHORT_ADVANCED_IGNORE', 1);
define('C_PHP_BS_GRID_SHORT_ADVANCED_DEFAULT', 2);

// export CSV
define('C_PHP_BS_GRID_EXPORT_CSV_NONE', 1);
define('C_PHP_BS_GRID_EXPORT_CSV_ENCODING_LOCAL', 2);
define('C_PHP_BS_GRID_EXPORT_CSV_ENCODING_UTF8', 3);

// text criteria operators
define('C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH', 3);
define('C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS', 4);
define('C_PHP_BS_GRID_CRITERIA_TEXT_IS_NULL', 5);

// lookup criteria operators
define('C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_LOOKUP_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_LOOKUP_IS_NULL', 3);

// number criteria operators - TODO not yet implemented
define('C_PHP_BS_GRID_CRITERIA_NUMBER_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_IN', 3);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_LESS_THAN_OR_EQUAL_TO', 4);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_GREATER_THAN_OR_EQUAL_TO', 5);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_IS_NULL', 6);

// date criteria operators
define('C_PHP_BS_GRID_CRITERIA_DATE_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_DATE_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN_OR_EQUAL_TO', 3);
define('C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN_OR_EQUAL_TO', 4);
define('C_PHP_BS_GRID_CRITERIA_DATE_IS_NULL', 5);