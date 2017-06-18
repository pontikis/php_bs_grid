# php_bs_grid parameters: Criteria - DATE

```php
// define operators (start date)
$a_criteria_operators_task_date_start_from = array(
	C_PHP_BS_GRID_CRITERIA_DATE_IGNORE => 'Please select',
	C_PHP_BS_GRID_CRITERIA_DATE_EQUAL => 'equal',
	C_PHP_BS_GRID_CRITERIA_DATE_GREATER_THAN_OR_EQUAL_TO => 'greater than or equal to'
);

// define html params (start date)
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
		'dateFormat' => $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['jq_date'],
		'changeMonth' => true,
		'changeYear' => true,
		'showButtonPanel' => true,
		'timeFormat' => $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['jq_time_short'],
		'stepMinute' => 5
	)
);

// define operators (end date)
$a_criteria_operators_task_date_start_until = array(
	C_PHP_BS_GRID_CRITERIA_DATE_IGNORE => 'Please select',
	C_PHP_BS_GRID_CRITERIA_DATE_LESS_THAN_OR_EQUAL_TO => 'less than or equal to'
);

// define html params (end date)
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
		'dateFormat' => $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['jq_date'],
		'changeMonth' => true,
		'changeYear' => true,
		'showButtonPanel' => true,
		'timeFormat' => $conf['dt']['dateformat'][$_SESSION['user_dateformat']]['jq_time_short'],
		'stepMinute' => 5
	)
);
```