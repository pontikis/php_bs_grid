# php_bs_grid parameters: Criteria - NUMBER

```php
// amount_from
// define operators
$a_criteria_operators_amount_from = array(
	C_PHP_BS_GRID_CRITERIA_NUMBER_IGNORE => 'Please select',
	C_PHP_BS_GRID_CRITERIA_NUMBER_EQUAL => 'equal',
	C_PHP_BS_GRID_CRITERIA_NUMBER_GREATER_THAN => 'greater than',
	C_PHP_BS_GRID_CRITERIA_NUMBER_GREATER_THAN_OR_EQUAL_TO => 'greater than or equal to',
	C_PHP_BS_GRID_CRITERIA_NUMBER_IS_NULL => 'not given'
);

// define html params
$a_criteria_params_html_amount_from = array(
	'wrapper_id' => 'criteria_amount_from_wrapper',
	'wrapper_class' => 'form-group form-inline',

	'label' => 'Amount',
	'label_id' => '',
	'label_class' => '',

	'dropdown_id' => 'criteria_operator_amount_from',
	'dropdown_name' => 'criteria_operator_amount_from',
	'dropdown_class' => 'form-control',
	'dropdown_options' => $a_criteria_operators_amount_from,
	'dropdown_value' => $criteria_operator_amount_from,

	'input_id' => 'criteria_amount_from',
	'input_name' => 'criteria_amount_from',
	'input_class' => 'form-control',
	'maxlength' => 10,
	'autocomplete' => false,
	'placeholder' => 'from',
	'input_value' => $criteria_amount_from,

	'associated_criteria_name' => 'amount_upto',
	'msg_missing_operator' => 'Please, give initial amount search type',
	'msg_missing_value' => 'Please, give the initial amount'
);

// amount_upto
// define operators
$a_criteria_operators_amount_upto = array(
	C_PHP_BS_GRID_CRITERIA_NUMBER_IGNORE => 'Please select',
	C_PHP_BS_GRID_CRITERIA_NUMBER_LESS_THAN => 'less than',
	C_PHP_BS_GRID_CRITERIA_NUMBER_LESS_THAN_OR_EQUAL_TO => 'less than or equal to'
);

// define html params
$a_criteria_params_html_amount_upto = array(
	'wrapper_id' => 'criteria_amount_upto_wrapper',
	'wrapper_class' => 'form-group form-inline',

	'label' => 'Amount',
	'label_id' => '',
	'label_class' => '',

	'dropdown_id' => 'criteria_operator_amount_upto',
	'dropdown_name' => 'criteria_operator_amount_upto',
	'dropdown_class' => 'form-control',
	'dropdown_options' => $a_criteria_operators_amount_upto,
	'dropdown_value' => $criteria_operator_amount_upto,

	'input_id' => 'criteria_amount_upto',
	'input_name' => 'criteria_amount_upto',
	'input_class' => 'form-control',
	'maxlength' => 10,
	'autocomplete' => false,
	'placeholder' => 'up to',
	'input_value' => $criteria_amount_upto,

	'msg_missing_operator' => 'Please, give final amount search type',
	'msg_missing_value' => 'Please, give the final amount'
);
```