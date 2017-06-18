# php_bs_grid parameters: Criteria - LOOKUP

```php
// define operators
$a_criteria_operators_physician_id = array(
	C_PHP_BS_GRID_CRITERIA_LOOKUP_IGNORE => 'Please select',
	C_PHP_BS_GRID_CRITERIA_LOOKUP_EQUAL => 'to be'
);

// define html params
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
```