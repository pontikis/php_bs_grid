$(function() {

    // get vars
    $.ajax({
        url: "/url/to/ajax_get_vars.php",
        type: "POST",
        dataType: 'json',
        success: function(data) {

            var elem_php_bs_grid = $("#php_bs_grid_form");

            elem_php_bs_grid.php_bs_grid({
                addnew_record_url: data["addnew_record_url"],
                criteria: data["criteria"],
                msg_criteria_not_changed: data["msg_criteria_not_changed"],
                msg_apply_or_reset_criteria: data["msg_apply_or_reset_criteria"],
                ajax_validate_form_url: data["ajax_validate_form_url"],
                ajax_reset_all_url: data["ajax_reset_all_url"],
                bs_modal_id: data["bs_modal_id"],
                bs_modal_content_id: data["bs_modal_content_id"]
            });

        }
    });

});