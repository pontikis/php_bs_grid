<?php
session_start();
$a_vars = array();
$a_dg_params = array();
if(isset($_POST['dg_status']) && $_POST['dg_status']) {
	$a_dg_params = unserialize(base64_decode($_POST['dg_status']));
}
if(isset($_SESSION['php_bs_grid_calendar'])) {
	$a_dg_params = $_SESSION['php_bs_grid_calendar'];
}
$a_vars['addnew_record_url'] = $a_dg_params['dg_addnew_record_url'];
$a_vars['criteria'] = $a_dg_params['dg_criteria'];
$a_vars['ajax_validate_form_url'] = $a_dg_params['dg_ajax_validate_form_url'];
$a_vars['ajax_reset_all_url'] = $a_dg_params['dg_ajax_reset_all_url'];
$a_vars['msg_criteria_not_changed'] = $a_dg_params['dg_strings']['msg_criteria_not_changed'];
$a_vars['msg_apply_or_reset_criteria'] = $a_dg_params['dg_strings']['msg_apply_or_reset_criteria'];
$a_vars['bs_modal_id'] = $a_dg_params['dg_bs_modal_id'];
$a_vars['bs_modal_content_id'] = $a_dg_params['dg_bs_modal_content_id'];

echo json_encode($a_vars);