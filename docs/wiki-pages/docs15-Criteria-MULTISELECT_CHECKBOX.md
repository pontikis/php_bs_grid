# php_bs_grid parameters: Criteria - MULTISELECT_CHECKBOX

```php
// define operators
$criteria_operator_task_type = C_PHP_BS_GRID_CRITERIA_MULTISELECT_CHECKBOX_ONE_OR_MORE_OF;
$criteria_isnull_checked_task_type = '';
if(isset($_POST['criteria_task_type_isnull'])) {
	$criteria_operator_task_type = C_PHP_BS_GRID_CRITERIA_MULTISELECT_CHECKBOX_IS_NULL;
	$criteria_isnull_checked_task_type = ' checked';
}

// define html params
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

	'display_is_null_option' => C_PHP_BS_GRID_CRITERIA_MULTISELECT_CHECKBOX_DISPLAY_IS_NULL_YES,
	'is_null_class' => 'checkbox',
	'is_null_style' => '',
	'is_null_label_id' => '',
	'is_null_label_class' => '',
	'is_null_label' => gettext('not given'),
	'is_null_id' => 'criteria_task_type_isnull',
	'is_null_name' => 'criteria_task_type_isnull',
	'is_null_checked' => $criteria_isnull_checked_task_type,

	'msg_all_deselected' => 'Please, select at least one option of filter type'
);
```