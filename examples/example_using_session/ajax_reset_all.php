<?php
session_start();
if(isset($_SESSION['php_bs_grid_example'])) {
	unset($_SESSION['php_bs_grid_example']);
}
$a_vars = array();
$a_vars['current_url'] = $foo;

echo json_encode($a_vars);