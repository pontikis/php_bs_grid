$(function() {

    var data, // example data
        elem_php_bs_grid = $("#php_bs_grid_form"),

        bs_modal_id = data["bs_modal_id"],
        bs_modal_content_id = data["bs_modal_content_id"];

    elem_php_bs_grid.php_bs_grid({
        default_page_num:  data["default_page_num"],
        columns_default: data["columns_default"],
        columns_more: data["columns_more"],
        export_excel_no: data["export_excel_no"],
        export_excel_yes: data["export_excel_yes"],

        criteria_operator_text_ignore: data["criteria_operator_text_ignore"],
        criteria_operator_text_isnull: data["criteria_operator_text_isnull"],

        criteria_operator_lookup_ignore: data["criteria_operator_lookup_ignore"],
        criteria_operator_lookup_equal: data["criteria_operator_lookup_equal"],

        criteria_operator_date_ignore: data["criteria_operator_date_ignore"],
        criteria_operator_date_equal: data["criteria_operator_date_equal"],
        criteria_operator_date_isnull: data["criteria_operator_date_isnull"],

        url_addnew_record: encodeURI(data["url_addnew_record"]),

        criteria: [
            {
                criteria_name: "lastname",
                criteria_type: "text",
                label_id: null,
                dropdown_id: "criteria_operator_lastname",
                input_id: "criteria_lastname",
                msg_missing_operator: data["missing_criteria_operator_lastname"],
                msg_missing_value: data["missing_criteria_lastname"],
                minchars: 3,
                msg_minchars: data["minchars_lastname"]
            },
            {
                criteria_name: "firstname",
                criteria_type: "text",
                label_id: null,
                dropdown_id: "criteria_operator_firstname",
                input_id: "criteria_firstname",
                msg_missing_operator: data["missing_criteria_operator_firstname"],
                msg_missing_value: data["missing_criteria_firstname"]
            },
            {
                criteria_name: "gender",
                criteria_type: "lookup",
                dropdown_id: "criteria_operator_gender",
                dropdown_lookup_id: "criteria_gender"
            },
            {
                criteria_name: "date_start",
                criteria_type: "date_start",
                label_id: null,
                dropdown_id: "criteria_operator_date_start",
                input_id: "criteria_date_start",
                associated_criteria_name: "date_end",
                msg_missing_operator: data["missing_criteria_operator_date_start"],
                msg_missing_value: data["missing_criteria_date_start"],
                datepicker_params: {
                    dateFormat: data["date_format"],
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    maxDate: 0
                }
            },
            {
                criteria_name: "date_end",
                criteria_type: "date_end",
                label_id: "criteria_date_end_label",
                dropdown_id: "criteria_operator_date_end",
                input_id: "criteria_date_end",
                msg_missing_operator: data["missing_criteria_operator_date_end"],
                msg_missing_value: data["missing_criteria_date_end"],
                datepicker_params: {
                    dateFormat: data["date_format"],
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    maxDate: 0
                }
            }
        ],
        //ajax_validate_form_url: encodeURI($.ajaxSetup()["url"] + "ajax_validate_form.php"),

        bs_modal_id: bs_modal_id,
        bs_modal_content_id: bs_modal_content_id

    });

});
