<?php
/**
 * PHP_BS_GRID constants
 *
 * php datagrid with jquery, jquery-ui and bootstrap frontend
 * https://github.com/pontikis/php_bs_grid
 *
 * @author     Christos Pontikis http://pontikis.net
 * @copyright  Christos Pontikis
 * @license    MIT http://opensource.org/licenses/MIT
 * @version    0.9.5 (02 Jul 2017)
 *
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
define('C_PHP_BS_GRID_EXPORT_EXCEL_NO', 1);
define('C_PHP_BS_GRID_EXPORT_EXCEL_YES', 2);

// text criteria operators
define('C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH', 3);
define('C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS', 4);
define('C_PHP_BS_GRID_CRITERIA_TEXT_IS_NULL', 5);

// number criteria operators
define('C_PHP_BS_GRID_CRITERIA_NUMBER_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_LESS_THAN', 3);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_LESS_THAN_OR_EQUAL_TO', 4);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_GREATER_THAN', 5);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_GREATER_THAN_OR_EQUAL_TO', 6);
define('C_PHP_BS_GRID_CRITERIA_NUMBER_IS_NULL', 7);

// date criteria operators
define('C_PHP_BS_GRID_CRITERIA_DATE_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_DATE_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN', 3);
define('C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN_OR_EQUAL_TO', 4);
define('C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN', 5);
define('C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN_OR_EQUAL_TO', 6);
define('C_PHP_BS_GRID_CRITERIA_DATE_IS_NULL', 7);

// lookup criteria operators
define('C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE', 1);
define('C_PHP_BS_GRID_CRITERIA_LOOKUP_EQUAL', 2);
define('C_PHP_BS_GRID_CRITERIA_LOOKUP_NOT_EQUAL', 3);
define('C_PHP_BS_GRID_CRITERIA_LOOKUP_IS_NULL', 4);

// autocomplete criteria operators
define('C_PHP_BS_GRID_CRITERIA_AUTOCOMPLETE_EQUAL', 1);
define('C_PHP_BS_GRID_CRITERIA_AUTOCOMPLETE_IS_NULL', 2);

define('C_PHP_BS_GRID_CRITERIA_AUTOCOMPLETE_DISPLAY_IS_NULL_YES', 1);
define('C_PHP_BS_GRID_CRITERIA_AUTOCOMPLETE_DISPLAY_IS_NULL_NO', 2);

// autocomplete criteria operators
define('C_PHP_BS_GRID_CRITERIA_MULTISELECT_CHECKBOX_ONE_OR_MORE_OF', 1);
define('C_PHP_BS_GRID_CRITERIA_MULTISELECT_CHECKBOX_IS_NULL', 2);

define('C_PHP_BS_GRID_CRITERIA_MULTISELECT_CHECKBOX_DISPLAY_IS_NULL_YES', 1);
define('C_PHP_BS_GRID_CRITERIA_MULTISELECT_CHECKBOX_DISPLAY_IS_NULL_NO', 2);

// misc
define('C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER', '?');