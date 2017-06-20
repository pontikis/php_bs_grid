<?php
session_start();
if(isset($_SESSION['php_bs_grid_calendar'])) {
	unset($_SESSION['php_bs_grid_calendar']);
}

$a_vars = array();
$a_vars['current_url'] = $section_urls['activities_archive'];

echo json_encode($a_vars);