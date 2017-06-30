/**
 * @fileOverview php_bs_grid is a jQuery helper plugin for php_bs_grid class. Project page https://github.com/pontikis/php_bs_grid
 * @version 0.9.5 (XX XXX 2017)
 * @licence MIT
 * @author Christos Pontikis http://www.pontikis.net
 * @copyright Christos Pontikis http://www.pontikis.net
 */

/**
 * See <a href="http://jquery.com">http://jquery.com</a>.
 * @name $
 * @class
 * See the jQuery Library  (<a href="http://jquery.com">http://jquery.com</a>) for full details.  This just
 * documents the function and classes that are added to jQuery by this plug-in.
 */

/**
 * See <a href="http://jquery.com">http://jquery.com</a>
 * @name fn
 * @class
 * See the jQuery Library  (<a href="http://jquery.com">http://jquery.com</a>) for full details.  This just
 * documents the function and classes that are added to jQuery by this plug-in.
 * @memberOf $
 */
"use strict";
(function($) {

    var pluginName = 'php_bs_grid';

    /* public methods ------------------------------------------------------- */
    var methods = {
        /**
         * @lends $.fn.php_bs_grid
         */
        init: function(options) {

            var elem = this;

            return this.each(function() {

                /**
                 * settings and defaults
                 * using $.extend, settings modification will affect elem.data() and vice versa
                 */
                var settings = elem.data(pluginName);
                if(typeof(settings) === 'undefined') {
                    var defaults = elem.php_bs_grid('getDefaults');
                    settings = $.extend({}, defaults, options);
                } else {
                    settings = $.extend({}, settings, options);
                }
                elem.data(pluginName, settings);

                /* define vars ---------------------------------------------- */
                var elem_php_bs_grid_form = $("#" + settings.php_bs_grid_form_id),

                    // elements
                    elem_reset_all = $("#" + settings.reset_all_id),
                    elem_rows_per_page = $("#" + settings.rows_per_page_id),
                    elem_columns_switcher = $("#" + settings.columns_switcher_id),
                    elem_columns_to_display = $("#" + settings.columns_to_display_id), // hidden
                    elem_addnew_record = $("#" + settings.addnew_record_id),

                    elem_sort_simple_field = $("#" + settings.sort_simple_field_id), // hidden
                    elem_sort_simple_order = $("#" + settings.sort_simple_order_id), // hidden
                    elem_sort_advanced = $("#" + settings.sort_advanced_id), // hidden
                    elem_columns_sortable = $(settings.col_sortable_selector), // th of sortable columns

                    elem_go_top = $("#" + settings.go_top_id),
                    elem_go_back = $("#" + settings.go_back_id),
                    elem_go_forward = $("#" + settings.go_forward_id),
                    elem_go_bottom = $("#" + settings.go_bottom_id),

                    elem_go_to_page = $("#" + settings.go_to_page_id),
                    elem_page_num = $("#" + settings.page_num_id),

                    elem_total_pages = $("#" + settings.total_pages_id), // hidden

                    elem_export_excel_btn = $("#" + settings.export_excel_btn_id),
                    elem_export_excel = $("#" + settings.export_excel_id), // hidden

                    elem_criteria_apply = $("#" + settings.criteria_apply_id),
                    elem_criteria_reset = $("#" + settings.criteria_reset_id),
                    elem_criteria_clear = $("#" + settings.criteria_clear_id),
                    elem_criteria_before = $("#" + settings.criteria_before_id), // hidden
                    elem_criteria_after = $("#" + settings.criteria_after_id), // hidden

                    // other vars
                    rows_per_page_before_change = elem_rows_per_page.val(),
                    criteria = settings.criteria,
                    elem_criteria,
                    elem_criteria_operator,
                    elem_criteria_wrapper,
                    elem_to_append,
                    elem_to_prepend,
                    datepicker_params,
                    elem_criteria_autocomplete,
                    elem_criteria_filter,
                    autocomplete_params,
                    elem_checkbox,

                    a_criteria_ids = [],
                    criteria_multiple_selector,
                    elem_criteria_multiple_selector, // a multiple selector contains criteria IDs
                    criteria_on_load,
                    criteria_on_post,
                    criteria_changed,
                    validate_criteria_result = {
                        "error": null,
                        "focus": null,
                        "text_inputs_contain_value": null,
                        "ajax_data_to_pass": {}
                    };

                // create Multiple Selector from criteria IDs
                $.each(criteria, function(criterion_name, criterion) {
                    switch(criterion.type) {
                        case "text":
                            a_criteria_ids.push("#" + criterion["params_html"]["dropdown_id"]);
                            a_criteria_ids.push("#" + criterion["params_html"]["input_id"]);
                            break;
                        case "lookup":
                            a_criteria_ids.push("#" + criterion["params_html"]["dropdown_id"]);
                            a_criteria_ids.push("#" + criterion["params_html"]["dropdown_lookup_id"]);
                            break;
                        case "number":
                        case "date":
                            a_criteria_ids.push("#" + criterion["params_html"]["dropdown_id"]);
                            a_criteria_ids.push("#" + criterion["params_html"]["input_id"]);
                            break;
                        case "autocomplete":
                            a_criteria_ids.push("#" + criterion["params_html"]["autocomplete_id"]);
                            a_criteria_ids.push("#" + criterion["params_html"]["filter_id"]);
                            break;
                        case "multiselect_checkbox":
                            $.each(criterion["params_html"]["items"], function(i, item) {
                                a_criteria_ids.push('#' + item["input_id"]);
                            });
                            break;
                    }
                });
                criteria_multiple_selector = a_criteria_ids.join(",");
                elem_criteria_multiple_selector = $(criteria_multiple_selector);

                // serialize criteria on load
                criteria_on_load = elem_criteria_multiple_selector.serialize();
                elem_criteria_before.val(criteria_on_load);

                var arrange_criteria = function(criteria_type) {
                    $.each(criteria, function(criterion_name, criterion) {

                        // append prepend element
                        if(criterion.params_html.hasOwnProperty("elem_id_to_append") && criterion["params_html"]["elem_id_to_append"]) {
                            elem_criteria_wrapper = $("#" + criterion["params_html"]["wrapper_id"]);
                            elem_to_append =  $("#" + criterion["params_html"]["elem_id_to_append"]);
                            elem_criteria_wrapper.append(elem_to_append);
                        }
                        if(criterion.params_html.hasOwnProperty("elem_id_to_prepend") && criterion["params_html"]["elem_id_to_prepend"]) {
                            elem_criteria_wrapper = $("#" + criterion["params_html"]["wrapper_id"]);
                            elem_to_prepend =  $("#" + criterion["params_html"]["elem_id_to_prepend"]);
                            elem_criteria_wrapper.prepend(elem_to_prepend);
                        }

                        if(criterion.type === criteria_type) {
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            switch(criteria_type) {
                                case "text":
                                    elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                                    if(parseInt(elem_criteria_operator.val()) === settings.criteria_operator_text_isnull) {
                                        elem_criteria.val("");
                                        elem_criteria.hide();
                                    } else {
                                        elem_criteria.show();
                                        if(parseInt(elem_criteria_operator.val()) === settings.criteria_operator_text_ignore) {
                                            elem_criteria.val("");
                                        }
                                    }
                                    break;
                                case "lookup":
                                    elem_criteria = $("#" + criterion["params_html"]["dropdown_lookup_id"]);

                                    if(parseInt(elem_criteria_operator.val()) === settings.criteria_operator_lookup_equal) {
                                        elem_criteria.show();
                                    } else {
                                        elem_criteria.hide();
                                    }
                                    break;
                                case "number":
                                    elem_criteria = $("#" + criterion["params_html"]["input_id"]);
                                    elem_criteria_wrapper = $("#" + criterion["params_html"]["wrapper_id"]);

                                    if(elem_criteria_wrapper.is(":visible")) {
                                        var associated_criteria = false;
                                        if(criterion.params_html.hasOwnProperty("associated_criteria_name") && criterion["params_html"]["associated_criteria_name"]) {
                                            associated_criteria = true;
                                            var criterion_name_assoc = criterion["params_html"]["associated_criteria_name"],
                                                assoc = criteria[criterion_name_assoc],
                                                elem_criteria_wrapper_assoc = $("#" + assoc["params_html"]["wrapper_id"]),
                                                elem_criteria_operator_assoc = $("#" + assoc["params_html"]["dropdown_id"]),
                                                elem_criteria_assoc = $("#" + assoc["params_html"]["input_id"]);
                                        }
                                        var criteria_number_operator = parseInt(elem_criteria_operator.val());
                                        if(criteria_number_operator === settings.criteria_operator_number_equal ||
                                            criteria_number_operator === settings.criteria_operator_number_isnull) {
                                            if(criteria_number_operator === settings.criteria_operator_number_isnull) {
                                                elem_criteria.val("");
                                                elem_criteria.hide();
                                            }
                                            if(associated_criteria) {
                                                elem_criteria_operator_assoc.val(settings.criteria_operator_number_ignore);
                                                elem_criteria_assoc.val("");
                                                elem_criteria_wrapper_assoc.hide();
                                            }
                                        } else {
                                            elem_criteria.show();
                                            if(criteria_number_operator === settings.criteria_operator_number_ignore) {
                                                elem_criteria.val("");
                                            }
                                            if(associated_criteria) {
                                                elem_criteria_wrapper_assoc.show();
                                            }
                                        }
                                    }
                                    break;
                                case "date":
                                    elem_criteria = $("#" + criterion["params_html"]["input_id"]);
                                    elem_criteria_wrapper = $("#" + criterion["params_html"]["wrapper_id"]);

                                    if(elem_criteria_wrapper.is(":visible")) {
                                        var associated_criteria = false;
                                        if(criterion.params_html.hasOwnProperty("associated_criteria_name") && criterion["params_html"]["associated_criteria_name"]) {
                                            associated_criteria = true;
                                            var criterion_name_assoc = criterion["params_html"]["associated_criteria_name"],
                                                assoc = criteria[criterion_name_assoc],
                                                elem_criteria_wrapper_assoc = $("#" + assoc["params_html"]["wrapper_id"]),
                                                elem_criteria_operator_assoc = $("#" + assoc["params_html"]["dropdown_id"]),
                                                elem_criteria_assoc = $("#" + assoc["params_html"]["input_id"]);
                                        }
                                        var criteria_date_operator = parseInt(elem_criteria_operator.val());
                                        if(criteria_date_operator === settings.criteria_operator_date_equal ||
                                            criteria_date_operator === settings.criteria_operator_date_isnull) {
                                            if(criteria_date_operator === settings.criteria_operator_date_isnull) {
                                                elem_criteria.val("");
                                                elem_criteria.hide();
                                            }
                                            if(associated_criteria) {
                                                elem_criteria_operator_assoc.val(settings.criteria_operator_date_ignore);
                                                elem_criteria_assoc.val("");
                                                elem_criteria_wrapper_assoc.hide();
                                            }
                                        } else {
                                            elem_criteria.show();
                                            if(criteria_date_operator === settings.criteria_operator_date_ignore) {
                                                elem_criteria.val("");
                                            }
                                            if(associated_criteria) {
                                                elem_criteria_wrapper_assoc.show();
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    });
                };

                var reset_criterion = function(criterion_name) {
                    var criterion = criteria[criterion_name];
                    switch(criterion.type) {
                        case "text":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                            elem_criteria_operator.val(criterion["params_html"]["dropdown_value"]);
                            elem_criteria.val(criterion["params_html"]["input_value"]);
                            break;
                        case "lookup":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["dropdown_lookup_id"]);

                            elem_criteria_operator.val(criterion["params_html"]["dropdown_value"]);
                            if(criterion["params_html"]["dropdown_lookup_value"]) {
                                elem_criteria.val(criterion["params_html"]["dropdown_lookup_value"]);
                            } else {
                                elem_criteria.prop('selectedIndex', 0);
                            }
                            break;
                        case "number":
                        case "date":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                            elem_criteria_operator.val(criterion["params_html"]["dropdown_value"]);
                            elem_criteria.val(criterion["params_html"]["input_value"]);
                            break;
                        case "autocomplete":
                            elem_criteria_filter = $("#" + criterion["params_html"]["filter_id"]);
                            elem_criteria_autocomplete = $("#" + criterion["params_html"]["autocomplete_id"]);

                            elem_criteria_filter.val(criterion["params_html"]["filter_value"]);
                            elem_criteria_autocomplete.val(criterion["params_html"]["autocomplete_value"]);
                            break;
                        case "multiselect_checkbox":
                            $.each(criterion["params_html"]["items"], function(index, item) {
                                elem_checkbox = $("#" + item["input_id"]);
                                if(jQuery.inArray(parseInt(elem_checkbox.val()), criterion["params_html"]["group_value"]) !== -1) {
                                    elem_checkbox.prop('checked', true);
                                } else {
                                    elem_checkbox.prop('checked', false);
                                }
                            });
                            break;
                    }

                };

                var clear_criterion = function(criterion_name) {
                    var criterion = criteria[criterion_name];
                    switch(criterion.type) {
                        case "text":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);
                            elem_criteria.val("");
                            elem_criteria_operator.val(settings.criteria_operator_text_ignore);
                            break;
                        case "lookup":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria_operator.val(settings.criteria_operator_lookup_ignore);
                            break;
                        case "number":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);
                            elem_criteria.val("");
                            elem_criteria_operator.val(settings.criteria_operator_number_ignore);
                            break;
                        case "date":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);
                            elem_criteria.val("");
                            elem_criteria_operator.val(settings.criteria_operator_date_ignore);
                            break;
                        case "autocomplete":
                            elem_criteria_filter = $("#" + criterion["params_html"]["filter_id"]);
                            elem_criteria_autocomplete = $("#" + criterion["params_html"]["autocomplete_id"]);
                            elem_criteria_filter.val("");
                            elem_criteria_autocomplete.val("");
                            break;
                        case "multiselect_checkbox":
                            $.each(criterion["params_html"]["items"], function(i, item) {
                                $('#' + item["input_id"]).prop('checked', item.default_checked_status);
                            });
                            break;
                    }
                };

                var validate_criteria = function() {

                    $.each(criteria, function(criterion_name, criterion) {

                        switch(criterion.type) {
                            case "text":
                                elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                                elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                                if(parseInt(elem_criteria_operator.val()) !== settings.criteria_operator_text_isnull) {

                                    elem_criteria.val($.trim(elem_criteria.val()));

                                    if(elem_criteria.val()) {
                                        validate_criteria_result.text_inputs_contain_value = true;
                                    }
                                    validate_criteria_result.ajax_data_to_pass[criterion["params_html"]["input_id"]] = elem_criteria.val();

                                    if(parseInt(elem_criteria_operator.val()) === settings.criteria_operator_text_ignore && elem_criteria.val()) {
                                        validate_criteria_result.error = criterion["params_html"]["msg_missing_operator"];
                                        validate_criteria_result.focus = criterion["params_html"]["dropdown_id"];
                                        return false;
                                    }
                                    if(parseInt(elem_criteria_operator.val()) !== settings.criteria_operator_text_ignore && !elem_criteria.val()) {
                                        validate_criteria_result.error = criterion["params_html"]["msg_missing_value"];
                                        validate_criteria_result.focus = criterion["params_html"]["input_id"];
                                        return false;
                                    }
                                    if(parseInt(elem_criteria_operator.val()) !== settings.criteria_operator_text_ignore && elem_criteria.val()) {
                                        if(criterion.params_html.hasOwnProperty("minchars") && criterion["params_html"]["minchars"]) {
                                            if(elem_criteria.val().length < criterion["params_html"]["minchars"]) {
                                                validate_criteria_result.error = criterion["params_html"]["msg_minchars"];
                                                validate_criteria_result.focus = criterion["params_html"]["input_id"];
                                                return false;
                                            }
                                        }
                                    }

                                }
                                break;
                            case "lookup":

                                break;
                            case "number":
                                elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                                elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                                if(parseInt(elem_criteria_operator.val()) !== settings.criteria_operator_number_isnull) {

                                    elem_criteria.val($.trim(elem_criteria.val()));

                                    if(elem_criteria.val()) {
                                        validate_criteria_result.text_inputs_contain_value = true;
                                    }

                                    validate_criteria_result.ajax_data_to_pass[criterion["params_html"]["dropdown_id"]] = elem_criteria_operator.val();
                                    validate_criteria_result.ajax_data_to_pass[criterion["params_html"]["input_id"]] = elem_criteria.val();

                                    if(parseInt(elem_criteria_operator.val()) === settings.criteria_operator_number_ignore && elem_criteria.val()) {
                                        validate_criteria_result.error = criterion["params_html"]["msg_missing_operator"];
                                        validate_criteria_result.focus = criterion["params_html"]["dropdown_id"];
                                        return false;
                                    }
                                    if(parseInt(elem_criteria_operator.val()) !== settings.criteria_operator_number_ignore && !elem_criteria.val()) {
                                        validate_criteria_result.error = criterion["params_html"]["msg_missing_value"];
                                        validate_criteria_result.focus = criterion["params_html"]["input_id"];
                                        return false;
                                    }

                                }
                                break;
                            case "date":
                                elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                                elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                                if(parseInt(elem_criteria_operator.val()) !== settings.criteria_operator_date_isnull) {

                                    elem_criteria.val($.trim(elem_criteria.val()));

                                    if(elem_criteria.val()) {
                                        validate_criteria_result.text_inputs_contain_value = true;
                                    }

                                    validate_criteria_result.ajax_data_to_pass[criterion["params_html"]["dropdown_id"]] = elem_criteria_operator.val();
                                    validate_criteria_result.ajax_data_to_pass[criterion["params_html"]["input_id"]] = elem_criteria.val();

                                    if(parseInt(elem_criteria_operator.val()) === settings.criteria_operator_date_ignore && elem_criteria.val()) {
                                        validate_criteria_result.error = criterion["params_html"]["msg_missing_operator"];
                                        validate_criteria_result.focus = criterion["params_html"]["dropdown_id"];
                                        return false;
                                    }
                                    if(parseInt(elem_criteria_operator.val()) !== settings.criteria_operator_date_ignore && !elem_criteria.val()) {
                                        validate_criteria_result.error = criterion["params_html"]["msg_missing_value"];
                                        validate_criteria_result.focus = criterion["params_html"]["input_id"];
                                        return false;
                                    }

                                }
                                break;
                            case "autocomplete":

                                break;
                            case "multiselect_checkbox":
                                var count_checked = 0;
                                $.each(criterion["params_html"]["items"], function(i, item) {
                                    if($('#' + item["input_id"]).prop('checked') === true) {
                                        count_checked = count_checked + 1;
                                    }
                                });
                                if(count_checked === 0) {
                                    validate_criteria_result.error = criterion["params_html"]["msg_all_deselected"];
                                    validate_criteria_result.focus = criterion["params_html"]["items"][0].input_id;
                                    return false;
                                }
                                break;
                        }

                    });

                };


                /* initialize criteria -------------------------------------- */
                arrange_criteria("text");
                arrange_criteria("number");
                arrange_criteria("lookup");
                arrange_criteria("date");

                /* reset all button ----------------------------------------- */
                elem_reset_all.click(function() {
                    if(settings.ajax_reset_all_url) {
                        $.ajax({
                            url: settings.ajax_reset_all_url,
                            type: "POST",
                            dataType: "json",
                            success: function(data) {
                                location.href = data["current_url"];
                            }
                        });
                    } else {
                        location.href = location.href.split('?')[0];
                    }
                });

                /* rows per page -------------------------------------------- */
                elem_rows_per_page.change(function() {
                    doSubmit("rows_per_page");
                });

                /* columns to display --------------------------------------- */
                elem_columns_switcher.click(function() {
                    doSubmit("columns_switcher");
                });

                /* add new record button ------------------------------------ */
                elem_addnew_record.click(function() {
                    location.href = settings.addnew_record_url;
                });

                /* simple (column) sorting ---------------------------------- */
                elem_columns_sortable.click(function() {
                    doSubmit("sort_simple", {"col_id": $(this).attr("id")});
                });

                /* advanced sorting ----------------------------------------- */
                elem_sort_advanced.change(function() {
                    doSubmit("sort_advanced");
                });

                /* pagination ----------------------------------------------- */
                elem_go_top.click(function() {
                    doSubmit("go_top");
                });

                elem_go_back.click(function() {
                    doSubmit("go_back");
                });

                elem_go_forward.click(function() {
                    doSubmit("go_forward");
                });

                elem_go_bottom.click(function() {
                    doSubmit("go_bottom");
                });

                elem_go_to_page.click(function() {
                    doSubmit("go_to_page");
                });

                elem_page_num.keypress(function(e) {
                    // if the key pressed is the enter key
                    if(e.which === 13) {
                        e.preventDefault();
                        elem_go_to_page.click();
                    }
                });

                /* Excel export --------------------------------------------- */
                elem_export_excel_btn.click(function() {
                    doSubmit('export_excel');
                });

                /* criteria ------------------------------------------------- */
                $.each(criteria, function(criterion_name, criterion) {

                    switch(criterion.type) {
                        case "text":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                            elem_criteria_operator.change(function() {
                                arrange_criteria("text");
                            });

                            elem_criteria.keypress(function(e) {
                                // if the key pressed is the enter key
                                if(e.which === 13) {
                                    e.preventDefault();
                                    elem_criteria_apply.click();
                                }
                            });
                            break;
                        case "number":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                            elem_criteria_operator.change(function() {
                                arrange_criteria("number");
                            });

                            elem_criteria.keypress(function(e) {
                                // if the key pressed is the enter key
                                if(e.which === 13) {
                                    e.preventDefault();
                                    elem_criteria_apply.click();
                                }
                            });
                            break;
                        case "lookup":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["dropdown_lookup_id"]);

                            elem_criteria_operator.change(function() {
                                arrange_criteria("lookup");
                            });
                            break;
                        case "date":
                            elem_criteria_operator = $("#" + criterion["params_html"]["dropdown_id"]);
                            elem_criteria = $("#" + criterion["params_html"]["input_id"]);

                            datepicker_params = criterion["params_html"]["datepicker_params"];
                            if(criterion.params_html.hasOwnProperty("show_time") && criterion["params_html"]["show_time"] === true) {
                                elem_criteria.datetimepicker(datepicker_params);
                            } else {
                                elem_criteria.datepicker(datepicker_params);
                            }

                            elem_criteria_operator.change(function() {
                                arrange_criteria("date");
                            });

                            elem_criteria.keypress(function(e) {
                                // if the key pressed is the enter key
                                if(e.which === 13) {
                                    e.preventDefault();
                                    elem_criteria_apply.click();
                                }
                            });
                            break;
                        case "autocomplete":
                            elem_criteria_filter = $("#" + criterion["params_html"]["filter_id"]);
                            elem_criteria_autocomplete = $("#" + criterion["params_html"]["autocomplete_id"]);
                            autocomplete_params = criterion["params_html"]["autocomplete_params"];
                            // set value to filter field (id) after selection from the list
                            autocomplete_params.select = function(event, ui) {
                                elem_criteria_filter.val(ui.item.id);
                            };
                            // mustMatch implementation
                            autocomplete_params.change = function(event, ui) {
                                if(ui.item === null) {
                                    elem_criteria_autocomplete.val("");
                                    elem_criteria_filter.val('');
                                }
                            };
                            elem_criteria_autocomplete.autocomplete(autocomplete_params);

                            // force select from list (mustMatch implementation)
                            elem_criteria_autocomplete.on('input', function() {
                                elem_criteria_filter.val('');
                            });

                            // set list width
                            elem_criteria_autocomplete.on('open', function() {
                                $(".ui-autocomplete").css("width", elem_criteria_autocomplete.css("width"));
                            });
                            break;
                        case "multiselect_checkbox":
                            break;
                    }
                });


                /* apply criteria ------------------------------------------- */
                elem_criteria_apply.click(function() {
                    doSubmit("apply_criteria");
                });

                /* reset criteria ------------------------------------------- */
                elem_criteria_reset.click(function() {
                    $.each(criteria, function(criterion_name, criterion) {
                        reset_criterion(criterion_name);
                    });
                    arrange_criteria("text");
                    arrange_criteria("number");
                    arrange_criteria("lookup");
                    arrange_criteria("date");
                });

                /* clear criteria ------------------------------------------- */
                elem_criteria_clear.click(function() {
                    doSubmit("clear_criteria");
                });

                /**
                 * Validate criteria and submit form
                 */

                var doSubmitForm = function() {
                    elem_criteria_after.val(criteria_on_post);
                    elem_php_bs_grid_form.submit();
                };

                var doSubmit = function(action, params) {

                    // clear text in autocomplete fields which does not match with some id (mustMatch)
                    $.each(criteria, function(criterion_name, criterion) {
                        if(criterion.type === "autocomplete") {
                            elem_criteria_filter = $("#" + criterion["params_html"]["filter_id"]);
                            elem_criteria_autocomplete = $("#" + criterion["params_html"]["autocomplete_id"]);
                            if(!elem_criteria_filter.val()) {
                                elem_criteria_autocomplete.val("");
                            }
                        }
                    });

                    if(action === "export_excel") {
                        elem_export_excel.val(settings.export_excel_yes);
                    } else {
                        elem_export_excel.val(settings.export_excel_no);
                    }

                    // serialize criteria on post
                    criteria_on_post = elem_criteria_multiple_selector.serialize();

                    // check if criteria changed
                    criteria_changed = (criteria_on_load !== criteria_on_post);

                    if(action === 'apply_criteria') {

                        if(criteria_changed) {
                            validate_criteria();
                            if(validate_criteria_result.error) {
                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, validate_criteria_result.error, validate_criteria_result.focus);
                                validate_criteria_result = {
                                    "error": null,
                                    "focus": null,
                                    "text_inputs_contain_value": null,
                                    "ajax_data_to_pass": {}
                                };
                                return false;
                            } else {
                                if(validate_criteria_result.text_inputs_contain_value && settings.ajax_validate_form_url) {

                                    $.ajax({
                                        url: settings.ajax_validate_form_url,
                                        type: "POST",
                                        data: validate_criteria_result.ajax_data_to_pass,
                                        dataType: "json",
                                        success: function(data) {
                                            if(data["error"]) {
                                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, data["error"], data["focus"]);
                                                validate_criteria_result = {
                                                    "error": null,
                                                    "focus": null,
                                                    "text_inputs_contain_value": null,
                                                    "ajax_data_to_pass": {}
                                                };
                                                return false;
                                            } else {
                                                elem_page_num.val(settings.default_page_num);

                                                doSubmitForm();
                                            }
                                        }
                                    });

                                } else {
                                    elem_page_num.val(settings.default_page_num);

                                    doSubmitForm();
                                }
                            }
                        } else {
                            show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, settings.msg_criteria_not_changed);
                            return false;
                        }

                    } else {

                        if(action === 'clear_criteria') {

                            elem_page_num.val(settings.default_page_num);

                            $.each(criteria, function(criterion_name, criterion) {
                                clear_criterion(criterion_name);
                            });
                            arrange_criteria("text");
                            arrange_criteria("number");
                            arrange_criteria("lookup");
                            arrange_criteria("date");

                            // serialize criteria on post (after clear)
                            criteria_on_post = elem_criteria_multiple_selector.serialize();

                        } else {
                            if(criteria_changed) {
                                if(action === "rows_per_page") {
                                    // revert selected value
                                    elem_rows_per_page.val(rows_per_page_before_change);
                                }
                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, settings.msg_apply_or_reset_criteria, settings.criteria_apply_id);
                                return false;
                            } else {
                                switch(action) {
                                    case "rows_per_page":
                                        elem_page_num.val(settings.default_page_num);
                                        break;
                                    case "columns_switcher":
                                        if(parseInt(elem_columns_to_display.val()) === settings.columns_default) {
                                            elem_columns_to_display.val(settings.columns_more)
                                        } else {
                                            elem_columns_to_display.val(settings.columns_default)
                                        }
                                        break;
                                    case "sort_simple":
                                        if(elem_sort_simple_field.val() === params.col_id) {
                                            if(elem_sort_simple_order.val() === "ASC") {
                                                elem_sort_simple_order.val("DESC");
                                            } else {
                                                elem_sort_simple_order.val("ASC");
                                            }
                                        } else {
                                            elem_sort_simple_field.val(params.col_id);
                                            elem_sort_simple_order.val("ASC");
                                        }
                                        break;
                                    case "sort_advanced":
                                        elem_sort_simple_field.val("");
                                        elem_sort_simple_order.val("");
                                        break;
                                    case "go_top":
                                        elem_page_num.val(settings.default_page_num);
                                        break;
                                    case "go_back":
                                        elem_page_num.val(+elem_page_num.val() - 1);
                                        break;
                                    case "go_forward":
                                        elem_page_num.val(+elem_page_num.val() + 1);
                                        break;
                                    case "go_bottom":
                                        elem_page_num.val(elem_total_pages.val());
                                        break;
                                    case "go_to_page":
                                        break;
                                }
                            }

                        }

                        doSubmitForm();

                    }
                };

                var show_bs_modal = function(bs_modal_id, bs_modal_content_id, content_html, elem_focus_id) {

                    var elem_bs_modal = $("#" + bs_modal_id),
                        elem_bs_modal_content = $("#" + bs_modal_content_id);

                    if(elem_focus_id) {
                        var elem_focus = $("#" + elem_focus_id);
                        elem_bs_modal.on('hidden.bs.modal', function() {
                            elem_focus.focus();
                        });
                    }
                    elem_bs_modal_content.html(content_html);
                    elem_bs_modal.modal("show");
                }

            });

        },

        /**
         * Get any option set to plugin using its name (as string)
         * @example $(element).php_bs_grid('getOption', some_option);
         * @param {String} opt
         * @return {*}
         */
        getOption: function(opt) {
            var elem = this;
            return elem.data(pluginName)[opt];
        },

        /**
         * Get default values
         * @example $(element).php_bs_grid('getDefaults');
         * @return {Object}
         */
        getDefaults: function() {
            return {
                php_bs_grid_form_id: "php_bs_grid_form",

                reset_all_id: "reset_all",
                rows_per_page_id: "rows_per_page",
                columns_switcher_id: "columns_switcher",
                columns_to_display_id: "columns_to_display", // hidden
                addnew_record_id: "addnew_record",

                col_sortable_selector: 'th.col-sortable', // th of sortable columns
                sort_simple_field_id: "sort_simple_field", // hidden
                sort_simple_order_id: "sort_simple_order", // hidden
                sort_advanced_id: "sort_advanced",

                go_top_id: "go_top",
                go_back_id: "go_back",
                go_forward_id: "go_forward",
                go_bottom_id: "go_bottom",
                go_to_page_id: "go_to_page",
                page_num_id: "page_num",
                total_pages_id: "total_pages", // hidden

                export_excel_btn_id: "export_excel_btn",
                export_excel_id: "export_excel", // hidden

                criteria_apply_id: "criteria_apply",
                criteria_reset_id: "criteria_reset",
                criteria_clear_id: "criteria_clear",
                criteria_before_id: "criteria_before", // hidden
                criteria_after_id: "criteria_after", // hidden

                // php constants
                default_page_num: 1,
                columns_default: 1,
                columns_more: 2,
                export_excel_no: 1,
                export_excel_yes: 2,

                criteria_operator_text_ignore: 1,
                criteria_operator_text_isnull: 5,

                criteria_operator_number_ignore: 1,
                criteria_operator_number_equal: 2,
                criteria_operator_number_isnull: 7,

                criteria_operator_lookup_ignore: 1,
                criteria_operator_lookup_equal: 2,

                criteria_operator_date_ignore: 1,
                criteria_operator_date_equal: 2,
                criteria_operator_date_isnull: 7
            };
        },

        /**
         * Display criterion (in case it is hidden)
         * @example $(element).php_bs_grid('showCriterion', criterion_name);
         * @param {String} str_criterion_name
         */
        showCriterion: function(str_criterion_name) {
            var elem = this,
                criteria = elem.data(pluginName)["criteria"],
                elem_criterion_wrapper;
            $.each(criteria, function(criterion_name, criterion) {
                if(criterion_name === str_criterion_name) {
                    elem_criterion_wrapper = $("#" + criterion["params_html"]["wrapper_id"]);
                    return false;
                }
            });
            elem_criterion_wrapper.show();
            elem_criterion_wrapper.children().show();
        }
    };

    /**
     * php_bs_grid - common javascript functionality.
     *
     * @class php_bs_grid
     * @memberOf $.fn
     */
    $.fn.php_bs_grid = function(method) {

        // Method calling logic
        if(methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if(typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.' + pluginName);
        }

    };

})(jQuery);