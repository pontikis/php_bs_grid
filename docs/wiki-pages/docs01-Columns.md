# php_bs_grid parameters: Columns

```php
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
```
