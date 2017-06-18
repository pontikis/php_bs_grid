# php_bs_grid parameters: Criteria - MULTISELECT_CHECKBOX

```php
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
			'input_value' => $conf['calendar']['task_types']['appointment'],
			'label_class' => $label_class,
			'label' => $task_types[$conf['calendar']['task_types']['appointment']],
			'default_checked_status' => true
		),
		array(
			'group_class' => 'checkbox',
			'input_class' => '',
			'input_id' => 'task_type_task',
			'input_value' => $conf['calendar']['task_types']['task'],
			'label_class' => $label_class,
			'label' => $task_types[$conf['calendar']['task_types']['task']],
			'default_checked_status' => true
		)
	),
	'msg_all_deselected' => 'Please, select at least one option of filter type'
);
```