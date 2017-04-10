<?php
/**
 * Example file for php_bs_grid v0.9.0
 */
require_once '/path/to/dacapo.class.php';
require_once '/path/to/php_bs_grid.class.php';
require_once '/path/to/php_bs_grid/constants.php';
require_once '/path/to/PHPExcel.php';

$ds = new dacapo($db_settings);
$objPHPExcel = new PHPExcel();

/*
 * php_bs_grid parameters ******************************************************
 */
$a_columns = array(
	'lastname' => array(
		'header' => 'Last Name',
		'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
		'th_class' => 'text-nowrap',
		'td_class' => '',
		'select_sql' => 'd.lastname',
		'sort_simple' => true,
	),
	'firstname' => array(
		'header' => 'First Name',
		'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
		'th_class' => 'text-nowrap',
		'td_class' => '',
		'select_sql' => 'd.firstname',
		'sort_simple' => true,
	),
	'gender' => array(
		'header' => 'Gender',
		'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
		'th_class' => '',
		'td_class' => '',
		'select_sql' => 'd.gender',
		'sort_simple' => false,
	),
	'date_inserted' => array(
		'header' => 'Date inserted',
		'display' => C_PHP_BS_GRID_COLUMNS_MORE,
		'th_class' => 'text-nowrap',
		'td_class' => '',
		'select_sql' => 'd.date_inserted',
		'sort_simple' => true,
	)
);

$a_advanced_sorting_options = array(
	1 => array(
		'text' => 'Please, select...',
		'sql' => ''
	),
	2 => array(
		'text' => 'lastname - firstname',
		'sql' => 'ORDER BY lastname ASC, firstname ASC'
	),
	3 => array(
		'text' => 'firstname - lastname',
		'sql' => 'ORDER BY firstname ASC, lastname ASC'
	)
);

$a_strings = array(
	'columns_default' => 'Display more data',
	'columns_more' => 'Display less data',
	'addnew_record' => 'Add new record',
	'advanced_sorting' => 'Advanced sorting',
	'export_excel' => 'Export to Excel',
	'first_page' => 'First page',
	'previous_page' => 'Previous page',
	'next_page' => 'Next page',
	'last_page' => 'Last page',
	'go_to_page' => 'Go to page',
	'rows_per_page' => 'Rows per page',
	'total_rows' => 'Total rows',
	'Page' => 'Page',
	'from' => 'from',
	'rows' => 'rows',
	'no_rows_returned' => 'No rows returned',
	'criteria' => 'Criteria',
	'apply_criteria' => 'Apply criteria',
	'clear_criteria' => 'Clear criteria',
);

/*
 * INITIALIZE php_bs_grid ******************************************************
 */
$a_dg_params = array(
	'dg_columns' => $a_columns,
	'dg_select_count_column' => 'c.id',
	'dg_select_from_sql' => 'FROM contacts c ' .
		'INNER JOIN  demographics d ON c.demographics_id = d.id',
	'dg_show_columns_switcher' => true,
	'dg_show_addnew_record' => true,
	'dg_rows_per_page_options' => array(10, 20, 30, 50, 100),
	'dg_columns_to_display_options' => array(C_PHP_BS_GRID_COLUMNS_DEFAULT, C_PHP_BS_GRID_COLUMNS_MORE),
	'dg_columns_default_icon' => 'glyphicon glyphicon-resize-full',
	'dg_columns_more_icon' => 'glyphicon glyphicon-resize-small',
	'dg_col_sortable_class' => 'col-sortable',
	'dg_sort_asc_indicator' => '<span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span> ',
	'dg_sort_desc_indicator' => '<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span> ',
	'dg_advanced_sorting_options' => $a_advanced_sorting_options,
	'dg_allow_export_excel' => true,
	'dg_export_excel_options' => array(C_PHP_BS_GRID_EXPORT_EXCEL_NO, C_PHP_BS_GRID_EXPORT_EXCEL_YES),
	'dg_export_excel_basename' => 'contacts',
	'dg_main_template_path' => '/path/to/php_bs_grid/template.php',
	'dg_criteria_template_path' => '/path/to/php_bs_grid/template_criteria.php',
	'dg_form_action' => urlencode('/form/action.php'),
	'dg_strings' => $a_strings
);

$dg = new php_bs_grid($ds, $objPHPExcel, $a_dg_params);

/**
 * Define Criteria *************************************************************
 */
// define operators
$a_criteria_operators_lastname = array(
	C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE => 'Please select...',
	C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL => 'equal',
	C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH => 'starts with',
	C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS => 'contains'
);

$a_criteria_operators_firstname = array(
	C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE => 'Please select...',
	C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL => 'equal',
	C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH => 'starts with',
	C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS => 'contains'
);

$a_criteria_operators_gender = array(
	C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE => 'Please select...',
	C_PHP_BS_GRID_CRITERIA_LOOKUP_EQUAL => 'to be',
	C_PHP_BS_GRID_CRITERIA_LOOKUP_IS_NULL => 'not given'
);

$a_criteria_operators_date_start = array(
	C_PHP_BS_GRID_CRITERIA_DATE_IGNORE => 'Please select...',
	C_PHP_BS_GRID_CRITERIA_DATE_EQUAL => 'equal',
	C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN_OR_EQUAL_TO => 'greater than or equal to'
);

$a_criteria_operators_date_end = array(
	C_PHP_BS_GRID_CRITERIA_DATE_IGNORE => 'Please select...',
	C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN_OR_EQUAL_TO => 'less than or equal to'
);

/**
 * get POST vars for CRITERIA ...
 */
$criteria_operator_lastname = C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE;
if(isset($_POST['criteria_operator_lastname'])) {
	$criteria_lastname = $_POST['criteria_operator_lastname'];
}

$criteria_lastname = null;
if($criteria_operator_lastname != C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE) {
	if(isset($_POST['criteria_lastname'])) {
		$criteria_lastname = htmlspecialchars($_POST['criteria_lastname']);
	}
}

// and so on ...

// criteria html params
$a_criteria_params_html_lastname = array(
	'wrapper_div_class' => 'form-group form-inline',

	'label' => 'Lastname',
	'label_id' => '',
	'label_class' => '',

	'dropdown_id' => 'criteria_operator_lastname',
	'dropdown_name' => 'criteria_operator_lastname',
	'dropdown_class' => 'form-control',
	'dropdown_options' => $a_criteria_operators_lastname,
	'dropdown_value' => $criteria_operator_lastname,

	'input_id' => 'criteria_lastname',
	'input_name' => 'criteria_lastname',
	'input_class' => 'form-control',
	'maxlength' => 100,
	'autocomplete' => true,
	'input_value' => $criteria_lastname,
);

$a_criteria_params_html_firstname = array(
	'wrapper_div_class' => 'form-group form-inline',

	'label' => 'Firstname',
	'label_id' => '',
	'label_class' => '',

	'dropdown_id' => 'criteria_operator_firstname',
	'dropdown_name' => 'criteria_operator_firstname',
	'dropdown_class' => 'form-control',
	'dropdown_options' => $a_criteria_operators_firstname,
	'dropdown_value' => $criteria_operator_firstname,

	'input_id' => 'criteria_firstname',
	'input_name' => 'criteria_firstname',
	'input_class' => 'form-control',
	'maxlength' => 100,
	'autocomplete' => true,
	'input_value' => $criteria_firstname,
);

$a_criteria_params_html_gender = array(
	'wrapper_div_class' => 'form-group form-inline',

	'label' => 'Gender',
	'label_id' => '',
	'label_class' => '',

	'dropdown_id' => 'criteria_operator_gender',
	'dropdown_name' => 'criteria_operator_gender',
	'dropdown_class' => 'form-control',
	'dropdown_options' => $a_criteria_operators_gender,
	'dropdown_value' => $criteria_operator_gender,

	'dropdown_lookup_id' => 'criteria_gender',
	'dropdown_lookup_name' => 'criteria_gender',
	'dropdown_lookup_class' => 'form-control',
	'dropdown_lookup_options' => $genders,
	'dropdown_lookup_value' => $criteria_gender,
);

$a_criteria_params_html_date_start = array(
	'wrapper_div_class' => 'form-group form-inline',

	'label' => 'Date start',
	'label_id' => '',
	'label_class' => '',

	'dropdown_id' => 'criteria_operator_date_start',
	'dropdown_name' => 'criteria_operator_date_start',
	'dropdown_class' => 'form-control',
	'dropdown_options' => $a_criteria_operators_date_start,
	'dropdown_value' => $criteria_operator_date_start,

	'input_id' => 'criteria_date_start',
	'input_name' => 'criteria_date_start',
	'input_class' => 'form-control',
	'maxlength' => 100,
	'autocomplete' => true,
	'input_value' => $criteria_date_start,
);

$a_criteria_params_html_date_end = array(
	'wrapper_div_class' => 'form-group form-inline',

	'label' => 'Date end',
	'label_id' => 'criteria_date_end_label',
	'label_class' => '',

	'dropdown_id' => 'criteria_operator_date_end',
	'dropdown_name' => 'criteria_operator_date_end',
	'dropdown_class' => 'form-control',
	'dropdown_options' => $a_criteria_operators_date_end,
	'dropdown_value' => $criteria_operator_date_end,

	'input_id' => 'criteria_date_end',
	'input_name' => 'criteria_date_end',
	'input_class' => 'form-control',
	'maxlength' => 100,
	'autocomplete' => true,
	'input_value' => $criteria_date_end,
);

// criteria
$a_criteria = array(
	'lastname' => array(
		'type' => 'text',
		'sql_column' => 'd.lastname',
		'sql_comparison_operator' => $criteria_operator_lastname,
		'column_value' => ci_ai($criteria_lastname),
		'params_html' => $a_criteria_params_html_lastname
	),
	'firstname' => array(
		'type' => 'text',
		'sql_column' => 'd.firstname',
		'sql_comparison_operator' => $criteria_operator_firstname,
		'column_value' => ci_ai($criteria_firstname),
		'params_html' => $a_criteria_params_html_firstname
	),
	'gender' => array(
		'type' => 'lookup',
		'sql_column' => 'd.gender',
		'sql_comparison_operator' => $criteria_operator_gender,
		'column_value' => $criteria_gender,
		'params_html' => $a_criteria_params_html_gender
	),
	'date_start' => array(
		'type' => 'date_start',
		'sql_column' => 'c.date_inserted',
		'sql_comparison_operator' => $criteria_operator_date_start,
		'column_value' => $criteria_date_start,
		'params_html' => $a_criteria_params_html_date_start
	),
	'date_end' => array(
		'type' => 'date_end',
		'sql_column' => 'c.date_inserted',
		'sql_comparison_operator' => $criteria_operator_date_end,
		'column_value' => $criteria_date_end,
		'params_html' => $a_criteria_params_html_date_end
	)
);

/**
 * Set criteria
 */
$dg->setCriteria($a_criteria);

/*
 * retrieve db data ************************************************************
 * This applies query criteria (WHERE SQL)
 * counts rows returned
 * and retrieves current page data (to display)
 * or all rows returned (for Excel export)
 */
$res = $dg->retrieveDbData();
if($res === false) {
	trigger_error($dg->getError(), E_USER_ERROR);
}
$db_data = $dg->getDbData();

/*
 * custom format retrieve db data (OPTIONAL) ***********************************
 */

$dg->setDbDataFormatted($db_data);

/*
 * Excel export ****************************************************************
 */
if($dg->allowExportExcel()) {
	if($dg->getExportExcel() === C_PHP_BS_GRID_EXPORT_EXCEL_YES) {
		if($dg->exportExcel() === false) {
			trigger_error($dg->getError(), E_USER_ERROR);
		} else {
			exit;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Page title</title>
    <meta name="description"
          content="description">
    <meta name="author"
          content="author">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <link id="bootstrap_css"
          rel="stylesheet"
          type="text/css"
          href="/lib/front_end/bootstrap_v3.3.7/bootstrap.css">
    <link id="jquery_ui_css"
          rel="stylesheet"
          type="text/css"
          href="/lib/front_end/jquery-ui_v1.12.1/css/smoothness/jquery-ui.min.css">
    <link rel="stylesheet"
          type="text/css"
          href="/lib/front_end/php_bs_grid/php_bs_grid.css">

    <script src="/lib/front_end/jquery_v3.1.1/jquery.min.js"
            type="text/javascript"></script>
    <script src="/lib/front_end/bootstrap_v3.3.7/bootstrap.min.js"
            type="text/javascript"></script>
    <script src="/lib/front_end/jquery-ui_v1.12.1/js/jquery-ui.min.js"
            type="text/javascript"></script>
    <script src="/lib/front_end/jquery-ui-i18n/datepicker/datepicker-en.js"
            type="text/javascript"></script>
    <script src="/lib/front_end/php_bs_grid/jquery.php_bs_grid.js"
            type="text/javascript"></script>

</head>

<body>

<div class="container">

    <h1>Page title</h1>

	<?php include_once $dg->getMainTemplatePath() ?>

</div>

</body>
</html>