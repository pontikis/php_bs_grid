# php_bs_grid parameters: Criteria - AUTOCOMPLETE

```php
// define html params
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
```