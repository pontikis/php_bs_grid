<?php
session_start();

/**
 * Example file for php_bs_grid
 */
require_once '/path/to/util_functions.php';
require_once '/path/to/dacapo.class.php';
require_once '/path/to/php_bs_grid.class.php';
require_once '/path/to/php_bs_grid/constants.php';
require_once '/path/to/PHPExcel.php';

/*
 * php_bs_grid parameters ******************************************************
 */
$dg_name = 'php_bs_grid_example';
$a_dg_params = array();
$ds = new dacapo($db_settings); // $db_settings see dacapo documentation
$objPHPExcel = new PHPExcel();

$dg_params_general = 'define';
$dg_params_sql = 'define';
$dg_params_grid = 'define';
$dg_params_criteria = 'define';

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION[$dg_name])) {
	$a_dg_params = $_SESSION[$dg_name];
	$dg_params_general = 'ignore';
	$dg_params_sql = 'ignore';
	$dg_params_grid = 'ignore';
	$dg_params_criteria = 'ignore';
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION[$dg_name])) {

	$dg_params_general = 'pull_from_session';
	$dg_params_sql = 'pull_from_session';

	$criteria_changed = (isset($_POST['criteria_before']) &&
	isset($_POST['criteria_after']) &&
	($_POST['criteria_before'] != $_POST['criteria_after']) ? true : false);
	if($criteria_changed) {
		$dg_params_grid = 'pull_from_session';
		$dg_params_criteria = 'define';
	} else {
		$dg_params_grid = 'define';
		$dg_params_criteria = 'pull_from_session';
	}

}

// params general (7) ----------------------------------------------------------
if($dg_params_general == 'define') {
	$a_dg_params['dg_name'] = $dg_name;
	$a_dg_params['dg_form_action'] = $foo;
	$a_dg_params['dg_ajax_validate_form_url'] = '/url/to/ajax_validate_form.php';
	$a_dg_params['dg_ajax_reset_all_url'] = '/url/to/ajax_reset_all.php';
	$a_dg_params['dg_strings'] = array(
		'reset_all' => 'Reset',
		'columns_default' => 'Display more data',
		'columns_more' => 'Display less data',
		'addnew_record' => '',
		'advanced_sorting' => 'Advanced sorting',
		'export_excel' => 'Export to Excel',
		'first_page' => 'First',
		'previous_page' => 'Previous',
		'next_page' => 'Next',
		'last_page' => 'Last',
		'go_to_page' => 'Go to page',
		'rows_per_page' => 'Rows per page',
		'total_rows' => 'Total rows',
		'Page' => 'Page',
		'from' => 'from',
		'rows' => 'rows',
		'no_rows_returned' => 'No rows returned',
		'criteria' => 'Criteria',
		'apply_criteria' => 'Apply criteria',
		'reset_criteria' => 'Reset criteria',
		'clear_criteria' => 'Clear criteria',
		'msg_criteria_not_changed' => 'There are no changes to criteria',
		'msg_apply_or_reset_criteria' => 'There are changes to criteria. Please, apply or reset criteria to continue'
	);
	$a_dg_params['dg_bs_modal_id'] = $foo;
	$a_dg_params['dg_bs_modal_content_id'] = $foo;
} elseif($dg_params_general == 'pull_from_session') {
	$a_dg_params['dg_name'] = $_SESSION[$dg_name]['dg_name'];
	$a_dg_params['dg_form_action'] = $_SESSION[$dg_name]['dg_form_action'];
	$a_dg_params['dg_ajax_validate_form_url'] = $_SESSION[$dg_name]['dg_ajax_validate_form_url'];
	$a_dg_params['dg_ajax_reset_all_url'] = $_SESSION[$dg_name]['dg_ajax_reset_all_url'];
	$a_dg_params['dg_strings'] = $_SESSION[$dg_name]['dg_strings'];
	$a_dg_params['dg_bs_modal_id'] = $_SESSION[$dg_name]['dg_bs_modal_id'];
	$a_dg_params['dg_bs_modal_content_id'] = $_SESSION[$dg_name]['dg_bs_modal_content_id'];
}

// params sql (6) --------------------------------------------------------------
if($dg_params_sql == 'define') {
	$a_dg_params['dg_columns'] = array(
		'id' => array(
			'header' => 'Code',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => 'text-nowrap',
			'td_class' => '',
			'select_sql' => 't.id',
			'sort_simple' => true
		),
		'task_type_id' => array(
			'header' => 'Type',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => 'text-nowrap',
			'td_class' => '',
			'select_sql' => 'task_type_id',
			'sort_simple' => true,
		),
		'status_id' => array(
			'header' => 'Status',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => 'text-nowrap',
			'td_class' => '',
			'select_sql' => 'status_id',
			'sort_simple' => true,
		),
		'description' => array(
			'header' => 'Description',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => 'text-nowrap',
			'td_class' => '',
			'select_sql' => 'description',
			'sort_simple' => true,
		),
		'patients_id' => array(
			'display' => C_PHP_BS_GRID_COLUMNS_MORE,
			'select_sql' => 'patients_id',
			'is_hidden' => true,
		),
		'patientfullname' => array(
			'header' => 'Patient',
			'display' => C_PHP_BS_GRID_COLUMNS_MORE,
			'th_class' => 'text-nowrap',
			'td_class' => '',
			'select_sql' => 'CONCAT(d.lastname, \' \', d.firstname) as patientfullname',
			'sort_simple' => true,
		),
		'physician_id' => array(
			'header' => 'Physician',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => 'text-nowrap',
			'td_class' => '',
			'select_sql' => 'physician_id',
			'sort_simple' => false,
		),
		'date_start' => array(
			'header' => 'To be started',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => 'text-nowrap',
			'td_class' => 'text-nowrap',
			'select_sql' => 'date_start',
			'sort_simple' => true,
			'sort_simple_default' => true,
			'sort_simple_default_order' => 'DESC'
		),
		'date_end' => array(
			'header' => 'To be completed',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => 'text-nowrap',
			'td_class' => 'text-nowrap',
			'select_sql' => 'date_end',
			'sort_simple' => true,
		),
		'notes' => array(
			'header' => 'Notes',
			'display' => C_PHP_BS_GRID_COLUMNS_DEFAULT,
			'th_class' => '',
			'td_class' => '',
			'select_sql' => 't.notes',
			'sort_simple' => false,
		),
		'users_id' => array(
			'header' => 'Inserted from',
			'display' => C_PHP_BS_GRID_COLUMNS_MORE,
			'th_class' => 'text-nowrap',
			'td_class' => 'text-nowrap',
			'select_sql' => 'users_id',
			'sort_simple' => false,
		),
		'date_inserted' => array(
			'header' => 'Inserted at',
			'display' => C_PHP_BS_GRID_COLUMNS_MORE,
			'th_class' => 'text-nowrap',
			'td_class' => 'text-nowrap',
			'select_sql' => 'date_inserted',
			'sort_simple' => true,
		)
	);
	$a_dg_params['dg_select_count_column'] = 'id';
	$a_dg_params['dg_select_from_sql'] = 'FROM  crm_tasks t';
	$a_dg_params['dg_select_from_sql_more'] = 'FROM  crm_tasks t ' .
		'LEFT JOIN patients p ON t.patients_id = p.id ' .
		'LEFT JOIN  demographics d ON p.demographics_id = d.id';
	$a_dg_params['dg_fixed_where'] = array(
		'(task_type_id = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER . ' OR (task_type_id = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER . ' AND (users_id = ' . C_PHP_BS_GRID_DACAPO_SQL_PLACEHOLDER . ' OR is_public = 1)))'
	);
	$a_dg_params['dg_fixed_bind_params'] = array(1, 2, $_SESSION['user_id']);
} elseif($dg_params_sql == 'pull_from_session') {
	$a_dg_params['dg_columns'] = $_SESSION[$dg_name]['dg_columns'];
	$a_dg_params['dg_select_count_column'] = $_SESSION[$dg_name]['dg_select_count_column'];
	$a_dg_params['dg_select_from_sql'] = $_SESSION[$dg_name]['dg_select_from_sql'];
	$a_dg_params['dg_select_from_sql_more'] = $_SESSION[$dg_name]['dg_select_from_sql_more'];
	$a_dg_params['dg_fixed_where'] = $_SESSION[$dg_name]['dg_fixed_where'];
	$a_dg_params['dg_fixed_bind_params'] = $_SESSION[$dg_name]['dg_fixed_bind_params'];
}

// params grid (22) ------------------------------------------------------------
if($dg_params_grid == 'define') {
	$a_dg_params['dg_rows_per_page_options'] = array(10, 20, 30, 50, 100);
	$a_dg_params['dg_rows_per_page'] = sanitize_int('rows_per_page', C_PHP_BS_GRID_DEFAULT_ROWS_PER_PAGE, $a_dg_params['dg_rows_per_page_options'], null);

	$a_dg_params['dg_show_columns_switcher'] = true;
	$a_dg_params['dg_columns_to_display_options'] = array(C_PHP_BS_GRID_COLUMNS_DEFAULT, C_PHP_BS_GRID_COLUMNS_MORE);
	$a_dg_params['dg_columns_to_display'] = sanitize_int('columns_to_display', C_PHP_BS_GRID_COLUMNS_DEFAULT, $a_dg_params['dg_columns_to_display_options'], null);
	$a_dg_params['dg_columns_default_icon'] = 'glyphicon glyphicon-resize-full';
	$a_dg_params['dg_columns_more_icon'] = 'glyphicon glyphicon-resize-small';

	$a_dg_params['dg_show_addnew_record'] = false;
	$a_dg_params['dg_addnew_record_url'] = null;

	$a_dg_params['dg_col_sortable_class'] = 'col-sortable';
	$a_dg_params['dg_sort_asc_indicator'] = '<span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span> ';
	$a_dg_params['dg_sort_desc_indicator'] = '<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span> ';
	$a_dg_params['dg_advanced_sorting_options'] = array();

	$a_dg_params['dg_sort_simple_field'] = null;
	if(isset($_POST['sort_simple_field'])) {
		if(array_key_exists($_POST['sort_simple_field'], $a_dg_params['dg_columns'])) {
			if($a_dg_params['dg_columns'][$_POST['sort_simple_field']]['sort_simple']) {
				$a_dg_params['dg_sort_simple_field'] = $_POST['sort_simple_field'];
			}
		}
	}

	$a_dg_params['dg_sort_simple_order'] = null;
	if(isset($_POST['sort_simple_order'])) {
		if(in_array($_POST['sort_simple_order'], array('ASC', 'DESC'))) {
			$a_dg_params['dg_sort_simple_order'] = $_POST['sort_simple_order'];
		} else {
			$a_dg_params['dg_sort_simple_field'] = null;
		}
	}

	$a_dg_params['dg_sort_advanced'] = null;
	if(!$a_dg_params['dg_advanced_sorting_options']) {
		if(!$a_dg_params['dg_sort_simple_field']) {
			foreach($a_dg_params['dg_columns'] as $key => $column) {
				if(array_key_exists('sort_simple_default', $column) &&
					$column['sort_simple_default'] === true
				) {
					$a_dg_params['dg_sort_simple_field'] = $key;
					if(array_key_exists('sort_simple_default_order', $column)) {
						$a_dg_params['dg_sort_simple_order'] = $column['sort_simple_default_order'];
					} else {
						$a_dg_params['dg_sort_simple_order'] = 'ASC';
					}
					break;
				}
			}
		}
	} else {
		if($a_dg_params['dg_sort_simple_field']) {
			$a_dg_params['dg_sort_advanced'] = C_PHP_BS_GRID_SHORT_ADVANCED_IGNORE;
		} else {
			$a_dg_params['dg_sort_advanced'] = sanitize_int('sort_advanced', C_PHP_BS_GRID_SHORT_ADVANCED_DEFAULT, null, $a_dg_params['dg_advanced_sorting_options']);
		}
	}

	// never get page_num from cache (always use $_POST)
	$a_dg_params['dg_page_num'] = sanitize_int('page_num', C_PHP_BS_GRID_DEFAULT_PAGE_NUM, null, null);

	$a_dg_params['dg_allow_export_excel'] = true;
	$a_dg_params['dg_export_excel_options'] = array(C_PHP_BS_GRID_EXPORT_EXCEL_NO, C_PHP_BS_GRID_EXPORT_EXCEL_YES);
	$a_dg_params['dg_export_excel_basename'] = 'calendar';
	$a_dg_params['dg_export_excel'] = sanitize_int('export_excel', C_PHP_BS_GRID_EXPORT_EXCEL_NO, $a_dg_params['dg_export_excel_options'], null);

	$a_dg_params['dg_grid_template_path'] = '/path/to/php_bs_grid/template.php';
} elseif($dg_params_grid == 'pull_from_session') {
	$a_dg_params['dg_rows_per_page_options'] = $_SESSION[$dg_name]['dg_rows_per_page_options'];
	$a_dg_params['dg_rows_per_page'] = $_SESSION[$dg_name]['dg_rows_per_page'];

	$a_dg_params['dg_show_columns_switcher'] = $_SESSION[$dg_name]['dg_show_columns_switcher'];
	$a_dg_params['dg_columns_to_display_options'] = $_SESSION[$dg_name]['dg_columns_to_display_options'];
	$a_dg_params['dg_columns_to_display'] = $_SESSION[$dg_name]['dg_columns_to_display'];
	$a_dg_params['dg_columns_default_icon'] = $_SESSION[$dg_name]['dg_columns_default_icon'];
	$a_dg_params['dg_columns_more_icon'] = $_SESSION[$dg_name]['dg_columns_more_icon'];

	$a_dg_params['dg_show_addnew_record'] = $_SESSION[$dg_name]['dg_show_addnew_record'];
	$a_dg_params['dg_addnew_record_url'] = $_SESSION[$dg_name]['dg_addnew_record_url'];

	$a_dg_params['dg_col_sortable_class'] = $_SESSION[$dg_name]['dg_col_sortable_class'];
	$a_dg_params['dg_sort_asc_indicator'] = $_SESSION[$dg_name]['dg_sort_asc_indicator'];
	$a_dg_params['dg_sort_desc_indicator'] = $_SESSION[$dg_name]['dg_sort_desc_indicator'];
	$a_dg_params['dg_advanced_sorting_options'] = $_SESSION[$dg_name]['dg_advanced_sorting_options'];
	$a_dg_params['dg_sort_simple_field'] = $_SESSION[$dg_name]['dg_sort_simple_field'];
	$a_dg_params['dg_sort_simple_order'] = $_SESSION[$dg_name]['dg_sort_simple_order'];
	$a_dg_params['dg_sort_advanced'] = $_SESSION[$dg_name]['dg_sort_advanced'];

	// never get page_num from cache (always use $_POST)
	$a_dg_params['dg_page_num'] = sanitize_int('page_num', C_PHP_BS_GRID_DEFAULT_PAGE_NUM, null, null);

	$a_dg_params['dg_allow_export_excel'] = $_SESSION[$dg_name]['dg_allow_export_excel'];
	$a_dg_params['dg_export_excel_options'] = $_SESSION[$dg_name]['dg_export_excel_options'];
	$a_dg_params['dg_export_excel_basename'] = $_SESSION[$dg_name]['dg_export_excel_basename'];
	$a_dg_params['dg_export_excel'] = $_SESSION[$dg_name]['dg_export_excel'];

	$a_dg_params['dg_grid_template_path'] = $_SESSION[$dg_name]['dg_grid_template_path'];
}

// params criteria (2) ---------------------------------------------------------
if($dg_params_criteria == 'define') {

	// task_type
	$orientation = 'horizontal';
	$label_class = 'checkbox-inline';

	$a_task_types_valid = array(1, 2); // 1 = appointment 2 = task

	$criteria_task_type = array(1, 2);

	if(isset($_POST['criteria_task_type'])) {
		$valid = true;
		foreach($_POST['criteria_task_type'] as $item) {
			if(!in_array((int)$item, $a_task_types_valid)) {
				$valid = false;
				break;
			}
		}
		if($valid === true) {
			$criteria_task_type = array_map('intval', $_POST['criteria_task_type']);
		}
	}

	$a_criteria_params_html_task_type = array(
		'wrapper_id' => 'criteria_task_type_wrapper',
		'wrapper_class' => '',

		'fieldset_id' => '',
		'fieldset_class' => '',

		'legend' => 'Type',
		'legend_id' => '',
		'legend_class' => '',
		'legend_style' => '',

		'orientation' => $orientation,
		'group_name' => 'criteria_task_type[]',
		'group_value' => $criteria_task_type,

		'items' => array(
			array(
				'group_class' => 'checkbox',
				'input_class' => '',
				'input_id' => 'task_type_appointment',
				'input_value' => 1,
				'label_class' => $label_class,
				'label' => 'Appointment',
				'default_checked_status' => true
			),
			array(
				'group_class' => 'checkbox',
				'input_class' => '',
				'input_id' => 'task_type_task',
				'input_value' => 2,
				'label_class' => $label_class,
				'label' => 'Task',
				'default_checked_status' => true
			)
		),
		'msg_all_deselected' => 'Please, select at least one option of filter type'
	);

	// task_status
	$orientation = 'vertical';
	$label_class = '';
	$a_task_status_valid = array(1, 2, 3, 4); // 1 pending 2 done 3 postponed 4 cancelled
	$criteria_task_status = array(1, 2, 3, 4);
	if(isset($_POST['criteria_task_status'])) {
		$valid = true;
		foreach($_POST['criteria_task_status'] as $item) {
			if(!in_array((int)$item, $a_task_status_valid)) {
				$valid = false;
				break;
			}
		}
		if($valid === true) {
			$criteria_task_status = array_map('intval', $_POST['criteria_task_status']);
		}
	}
	$a_criteria_params_html_task_status = array(
		'wrapper_id' => 'criteria_task_status_wrapper',
		'wrapper_class' => '',

		'fieldset_id' => '',
		'fieldset_class' => '',

		'legend' => 'Status',
		'legend_id' => '',
		'legend_class' => '',
		'legend_style' => 'width: 40%;',

		'orientation' => $orientation,
		'group_name' => 'criteria_task_status[]',
		'group_value' => $criteria_task_status,

		'items' => array(
			array(
				'group_class' => 'checkbox',
				'input_class' => '',
				'input_id' => 'task_status_pending',
				'input_value' => 1,
				'label_class' => $label_class,
				'label' => 'pending',
				'default_checked_status' => true
			),
			array(
				'group_class' => 'checkbox',
				'input_class' => '',
				'input_id' => 'task_status_done',
				'input_value' => 2,
				'label_class' => 'checkbox-inline',
				'label' => 'done',
				'default_checked_status' => true
			),
			array(
				'group_class' => 'checkbox',
				'input_class' => '',
				'input_id' => 'task_status_postponed',
				'input_value' => 3,
				'label_class' => 'checkbox-inline',
				'label' => 'postponed',
				'default_checked_status' => true
			),
			array(
				'group_class' => 'checkbox',
				'input_class' => '',
				'input_id' => 'task_status_cancelled',
				'input_value' => 4,
				'label_class' => 'checkbox-inline',
				'label' => 'cancelled',
				'default_checked_status' => true
			)
		),
		'msg_all_deselected' => 'Please, select at least one option of filter status'
	);

	// physician_id
	$a_criteria_operators_physician_id = array(
		C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE => 'Please select',
		C_PHP_BS_GRID_CRITERIA_LOOKUP_EQUAL => 'to be'
	);
	$criteria_operator_physician_id = sanitize_int('criteria_operator_physician_id', C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE,
		null, $a_criteria_operators_physician_id);

	$criteria_physician_id = sanitize_int('criteria_physician_id', null,
		$conf['lk']['genders'], null);

	$a_criteria_params_html_physician_id = array(
		'wrapper_id' => 'criteria_physician_id_wrapper',
		'wrapper_class' => 'form-group form-inline',

		'label' => 'Appointment with physician',
		'label_id' => '',
		'label_class' => '',

		'dropdown_id' => 'criteria_operator_physician_id',
		'dropdown_name' => 'criteria_operator_physician_id',
		'dropdown_class' => 'form-control',
		'dropdown_options' => $a_criteria_operators_physician_id,
		'dropdown_value' => $criteria_operator_physician_id,

		'dropdown_lookup_id' => 'criteria_physician_id',
		'dropdown_lookup_name' => 'criteria_physician_id',
		'dropdown_lookup_class' => 'form-control',
		'dropdown_lookup_options' => $medical_staff,
		'dropdown_lookup_value' => $criteria_physician_id,
	);

	// patients_id
	$criteria_patients_id = null;
	if(isset($_POST['criteria_patients_id'])) {
		if(is_positive_integer($_POST['criteria_patients_id'])) {
			$criteria_patients_id = $_POST['criteria_patients_id'];
		}
	}
	$criteria_patients_fullname = null;
	if(isset($_POST['criteria_patients_fullname']) && $criteria_patients_id) {
		$criteria_patients_fullname = $_POST['criteria_patients_fullname'];
	}
	$a_criteria_params_html_patients_id = array(
		'wrapper_id' => 'criteria_patients_id_wrapper',
		'wrapper_class' => '',

		'autocomplete_wrapper_class' => 'col-xs-12 col-sm-12 col-md-6 col-lg-6',
		'autocomplete_group_wrapper_class' => 'form-group',
		'autocomplete_label_id' => 'criteria_patients_fullname_label',
		'autocomplete_label_class' => '',
		'autocomplete_label' => 'Appointment for patient',
		'autocomplete_id' => 'criteria_patients_fullname',
		'autocomplete_name' => 'criteria_patients_fullname',
		'autocomplete_class' => 'form-control',
		'autocomplete_style' => '',
		'autocomplete_placeholder' => 'a few letters from the patient\'s name',
		'autocomplete_value' => $criteria_patients_fullname,

		'filter_wrapper_class' => 'col-xs-12 col-sm-12 col-md-3 col-lg-3',
		'filter_group_wrapper_class' => 'form-group',
		'filter_label_id' => 'criteria_patients_id_label',
		'filter_label_class' => '',
		'filter_label' => 'Code',
		'filter_id' => 'criteria_patients_id',
		'filter_name' => 'criteria_patients_id',
		'filter_class' => 'form-control',
		'filter_style' => 'width: 100px;',
		'filter_value' => $criteria_patients_id,

		'autocomplete_params' => array(
			'source' => '/url/to/ajax_patient_autocomplete.php',
			'minLength' => 2,
			'delay' => 500,
			'html' => true
		)
	);

	// description
	$a_criteria_operators_description = array(
		C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE => 'Please select',
		C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL => 'equal',
		C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH => 'starts with',
		C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS => 'contains'
	);
	$criteria_operator_description = sanitize_int('criteria_operator_description', C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE,
		null, $a_criteria_operators_description);

	$criteria_description = null;
	if($criteria_operator_description !== C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE) {
		if(isset($_POST['criteria_description']) && $_POST['criteria_description']) {
			// replace multiple spaces with one
			$tmp = preg_replace('/\s+/', ' ', $_POST['criteria_description']);
			// allow space, any unicode letter or digit
			if(preg_match("/[^ \pL\pN]/u", $tmp)) {
				$criteria_description = null;
				$criteria_operator_description = C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE;
			} else {
				$criteria_description = $tmp;
			}
		}
	}
	$a_criteria_params_html_description = array(
		'wrapper_id' => 'criteria_description_wrapper',
		'wrapper_class' => 'form-group form-inline',

		'label' => 'Description',
		'label_id' => '',
		'label_class' => '',

		'dropdown_id' => 'criteria_operator_description',
		'dropdown_name' => 'criteria_operator_description',
		'dropdown_class' => 'form-control',
		'dropdown_options' => $a_criteria_operators_description,
		'dropdown_value' => $criteria_operator_description,

		'input_id' => 'criteria_description',
		'input_name' => 'criteria_description',
		'input_class' => 'form-control',
		'maxlength' => 100,
		'autocomplete' => true,
		'input_value' => $criteria_description,

		'msg_missing_operator' => 'Please, give description search type',
		'msg_missing_value' => 'Please, give description to search',
		'minchars' => 2,
		'msg_minchars' => 'Please, give at least two characters to description',
	);

	// task_date_start_from
	$a_criteria_operators_task_date_start_from = array(
		C_PHP_BS_GRID_CRITERIA_DATE_IGNORE => 'Please select',
		C_PHP_BS_GRID_CRITERIA_DATE_EQUAL => 'equal',
		C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN_OR_EQUAL_TO => 'greater than or equal to'
	);
	$criteria_operator_task_date_start_from = sanitize_int('criteria_operator_task_date_start_from', C_PHP_BS_GRID_CRITERIA_DATE_IGNORE,
		null, $a_criteria_operators_task_date_start_from);

	$criteria_task_date_start_from = null;
	if($criteria_operator_task_date_start_from !== C_PHP_BS_GRID_CRITERIA_DATE_IGNORE) {
		if(isset($_POST['criteria_task_date_start_from']) && $_POST['criteria_task_date_start_from']) {
			$res_criteria_task_date_start_from = isValidDateTimeString($_POST['criteria_task_date_start_from'], $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_datetime_short'], null, $intl);
			if($res_criteria_task_date_start_from) {
				$criteria_task_date_start_from = $_POST['criteria_task_date_start_from'];
				if(dateformat_i18n()) {
					$criteria_task_date_start_from = $res_criteria_task_date_start_from; // integer timestamp
				}
			} else {
				$criteria_operator_task_date_start_from = C_PHP_BS_GRID_CRITERIA_DATE_IGNORE;
			}
		}
	}
	$a_criteria_params_html_task_date_start_from = array(
		'wrapper_id' => 'criteria_task_date_start_from_wrapper',
		'wrapper_class' => 'form-group form-inline',

		'label' => 'To be started',
		'label_id' => '',
		'label_class' => '',

		'dropdown_id' => 'criteria_operator_task_date_start_from',
		'dropdown_name' => 'criteria_operator_task_date_start_from',
		'dropdown_class' => 'form-control',
		'dropdown_options' => $a_criteria_operators_task_date_start_from,
		'dropdown_value' => $criteria_operator_task_date_start_from,

		'input_id' => 'criteria_task_date_start_from',
		'input_name' => 'criteria_task_date_start_from',
		'input_class' => 'form-control',
		'maxlength' => 100,
		'autocomplete' => true,
		'placeholder' => 'from',
		'input_value' => $criteria_task_date_start_from,

		'associated_criteria_name' => 'task_date_start_until',
		'msg_missing_operator' => 'Please, give start date search type',
		'msg_missing_value' => 'Please, give the start date',
		'show_time' => true,
		'datepicker_params' => array(
			'dateFormat' => 'd/m/yy',
			'changeMonth' => true,
			'changeYear' => true,
			'showButtonPanel' => true,
			'timeFormat' => 'HH:mm',
			'stepMinute' => 5
		)
	);

	// task_date_start_until
	$a_criteria_operators_task_date_start_until = array(
		C_PHP_BS_GRID_CRITERIA_DATE_IGNORE => 'Please select',
		C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN_OR_EQUAL_TO => 'less than or equal to'
	);
	$criteria_operator_task_date_start_until = sanitize_int('criteria_operator_task_date_start_until', C_PHP_BS_GRID_CRITERIA_DATE_IGNORE,
		null, $a_criteria_operators_task_date_start_until);

	$criteria_task_date_start_until = null;
	if($criteria_operator_task_date_start_until !== C_PHP_BS_GRID_CRITERIA_DATE_IGNORE) {
		if(isset($_POST['criteria_task_date_start_until']) && $_POST['criteria_task_date_start_until']) {
			$res_criteria_task_date_start_until = isValidDateTimeString($_POST['criteria_task_date_start_until'], $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['php_datetime_short'], null, $intl);
			if($res_criteria_task_date_start_until) {
				$criteria_task_date_start_until = $_POST['criteria_task_date_start_until'];
				if(dateformat_i18n()) {
					$criteria_task_date_start_until = $res_criteria_task_date_start_until; // integer timestamp
				}
			} else {
				$criteria_operator_task_date_start_until = C_PHP_BS_GRID_CRITERIA_DATE_IGNORE;
			}
		}
	}
	$a_criteria_params_html_task_date_start_until = array(
		'wrapper_id' => 'criteria_task_date_start_until_wrapper',
		'wrapper_class' => 'form-group form-inline',

		'label' => 'To be started',
		'label_id' => '',
		'label_class' => '',

		'dropdown_id' => 'criteria_operator_task_date_start_until',
		'dropdown_name' => 'criteria_operator_task_date_start_until',
		'dropdown_class' => 'form-control',
		'dropdown_options' => $a_criteria_operators_task_date_start_until,
		'dropdown_value' => $criteria_operator_task_date_start_until,

		'input_id' => 'criteria_task_date_start_until',
		'input_name' => 'criteria_task_date_start_until',
		'input_class' => 'form-control',
		'maxlength' => 100,
		'autocomplete' => true,
		'placeholder' => 'until',
		'input_value' => $criteria_task_date_start_until,

		'msg_missing_operator' => 'Please, give end date search type',
		'msg_missing_value' => 'Please, give the end date',
		'show_time' => true,
		'datepicker_params' => array(
			'dateFormat' => 'd/m/yy',
			'changeMonth' => true,
			'changeYear' => true,
			'showButtonPanel' => true,
			'timeFormat' => 'HH:mm',
			'stepMinute' => 5
		)
	);

	// criteria
	$a_dg_params['dg_criteria'] = array(
		'task_type' => array(
			'type' => 'multiselect_checkbox',
			'sql_column' => 'task_type_id',
			'column_value' => $criteria_task_type,
			'value_to_ignore' => array(1, 2),
			'params_html' => $a_criteria_params_html_task_type
		),
		'task_status' => array(
			'type' => 'multiselect_checkbox',
			'sql_column' => 'status_id',
			'column_value' => $criteria_task_status,
			'value_to_ignore' => array(1, 2, 3, 4),
			'params_html' => $a_criteria_params_html_task_status
		),
		'physician_id' => array(
			'type' => 'lookup',
			'sql_column' => 'physician_id',
			'sql_comparison_operator' => $criteria_operator_physician_id,
			'column_value' => $criteria_physician_id,
			'params_html' => $a_criteria_params_html_physician_id
		),
		'patients_id' => array(
			'type' => 'autocomplete',
			'sql_column' => 'patients_id',
			'column_value' => $criteria_patients_id,
			'params_html' => $a_criteria_params_html_patients_id
		),
		'description' => array(
			'type' => 'text',
			'sql_column' => 'public.f_unaccent(LOWER(description))',
			'sql_comparison_operator' => $criteria_operator_description,
			'column_value' => ci_ai($criteria_description),
			'params_html' => $a_criteria_params_html_description
		),
		'task_date_start_from' => array(
			'type' => 'date',
			'sql_column' => 'date_start',
			'sql_comparison_operator' => $criteria_operator_task_date_start_from,
			'column_value' => encode_usr_datetime_short($criteria_task_date_start_from),
			'params_html' => $a_criteria_params_html_task_date_start_from
		),
		'task_date_start_until' => array(
			'type' => 'date',
			'sql_column' => 'date_start',
			'sql_comparison_operator' => $criteria_operator_task_date_start_until,
			'column_value' => encode_usr_datetime_short($criteria_task_date_start_until),
			'params_html' => $a_criteria_params_html_task_date_start_until
		)
	);

	$a_dg_params['dg_criteria_template_path'] = '/path/to/criteria.php';
} elseif($dg_params_criteria == 'pull_from_session') {
	$a_dg_params['dg_criteria'] = $_SESSION[$dg_name]['dg_criteria'];
	$a_dg_params['dg_criteria_template_path'] = $_SESSION[$dg_name]['dg_criteria_template_path'];
}

/*
 * INITIALIZE php_bs_grid ******************************************************
 */
$dg = new php_bs_grid($ds, $objPHPExcel, $a_dg_params);

/*
 * Retrieve db data ************************************************************
 * This applies query criteria (WHERE SQL)
 * counts rows returned
 * and retrieves current page data (to display)
 * or all rows returned (for Excel export)
 */
$res = $dg->retrieveDbData();
if($res === false) {
	trigger_error($dg->getError(), E_USER_ERROR);
}

/*
 * Save status to $_SESSION ****************************************************
 */
$_SESSION[$dg->getName()] = $a_dg_params;

/*
 * db data custom format (OPTIONAL) ********************************************
 */
$db_data = $dg->getDbData();

foreach($db_data as $key => $row) {

	if($row['date_start']) {
		$db_data[$key]['date_start'] = decode_usr_datetime_short($row['date_start']);
	}
	if($row['date_end']) {
		$db_data[$key]['date_end'] = decode_usr_datetime_short($row['date_end']);
	}

}

$dg->setDbDataFormatted($db_data);

/*
 * Excel export ****************************************************************
 */
if($dg->allowExportExcel()) {
	if($dg->getExportExcel() === C_PHP_BS_GRID_EXPORT_EXCEL_YES) {

		// revert value
		if(isset($_SESSION[$dg->getName()])) {
			$_SESSION[$dg->getName()]['dg_export_excel'] = C_PHP_BS_GRID_EXPORT_EXCEL_NO;
		}

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
          href="/assets/bootstrap_v3.3.7/bootstrap.css">
    <link id="jquery_ui_css"
          rel="stylesheet"
          type="text/css"
          href="/assets/jquery-ui_v1.12.1/css/smoothness/jquery-ui.min.css">
    <link rel="stylesheet"
          type="text/css"
          href="/assets/php_bs_grid/php_bs_grid.css">

    <script src="/assets/jquery_v3.1.1/jquery.min.js"
            type="text/javascript"></script>
    <script src="/assets/bootstrap_v3.3.7/bootstrap.min.js"
            type="text/javascript"></script>
    <script src="/assets/jquery-ui_v1.12.1/js/jquery-ui.min.js"
            type="text/javascript"></script>
    <script src="/assets/jquery-ui-i18n/datepicker/datepicker-en.js"
            type="text/javascript"></script>
    <script src="/assets/php_bs_grid/jquery.php_bs_grid.js"
            type="text/javascript"></script>
    <script src="/path/to/index.js"
            type="text/javascript"></script>
</head>

<body>

<div class="container">

    <h1>Page title</h1>

	<?php include_once $dg->getGridTemplatePath() ?>

</div>

</body>
</html>
