# php_bs_grid parameters: Advanced Sorting

```php
$a_dg_params['dg_advanced_sorting_options'] = array(
	1 => array(
		'text' => 'Please, select',
		'sql' => ''
	),
	2 => array(
		'text' => 'Last Name' . ' - ' . 'First Name',
		'sql' => 'ORDER BY lastname ASC, firstname ASC'
	),
	3 => array(
		'text' => 'First Name' . ' - ' . 'Last Name',
		'sql' => 'ORDER BY firstname ASC, lastname ASC'
	)
);
```