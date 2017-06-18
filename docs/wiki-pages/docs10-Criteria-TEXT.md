# php_bs_grid parameters: Criteria - TEXT

```php
// define operators
$a_criteria_operators_description = array(
	C_PHP_BS_GRID_CRITERIA_TEXT_IGNORE => 'Please select',
	C_PHP_BS_GRID_CRITERIA_TEXT_EQUAL => 'equal',
	C_PHP_BS_GRID_CRITERIA_TEXT_STARTS_WITH => 'starts with',
	C_PHP_BS_GRID_CRITERIA_TEXT_CONTAINS => 'contains'
);

// define html params
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
```