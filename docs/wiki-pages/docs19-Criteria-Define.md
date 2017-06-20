# php_bs_grid parameters: Define Criteria

```php
$a_dg_params['dg_criteria'] = array(
	'task_type' => array(
		'type' => 'multiselect_checkbox',
		'sql_column' => 'task_type_id',
		'column_value' => $criteria_task_type,
		'value_to_ignore' => array(1, 2); // 1 = appointment 2 = task
		'params_html' => $a_criteria_params_html_task_type
	),
	'task_status' => array(
		'type' => 'multiselect_checkbox',
		'sql_column' => 'status_id',
		'column_value' => $criteria_task_status,
		'value_to_ignore' => array(1, 2, 3, 4), // 1 pending 2 done 3 postponed 4 cancelled
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
```