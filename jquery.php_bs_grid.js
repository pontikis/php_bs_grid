/**
 * @fileOverview php_bs_grid is a jQuery helper plugin for php_bs_grid class.
 *               <p>License MIT
 *               <br />Copyright Christos Pontikis <a href="http://www.pontikis.net">http://www.pontikis.net</a>
 *               <br />Project page <a href="https://github.com/pontikis/php_bs_grid">https://github.com/pontikis/php_bs_grid</a>
 * @version 0.9.0 (Apr 2017)
 * @author Christos Pontikis http://www.pontikis.net
 * @requires jquery (current version 3.1.1), twitter bootstrap (current version 3.3.7), jQuery UI datepicker optional (current version 1.12.1)
 */
"use strict";
(function($) {

    var pluginName = 'php_bs_grid';

    /* public methods ------------------------------------------------------- */
    var methods = {

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

                /* elements and vars ---------------------------------------- */
                var elem_php_bs_grid_form = $("#" + settings.php_bs_grid_form_id),
                    elem_go_top = $("#" + settings.go_top_id),
                    elem_go_back = $("#" + settings.go_back_id),
                    elem_go_forward = $("#" + settings.go_forward_id),
                    elem_go_bottom = $("#" + settings.go_bottom_id),
                    elem_rows_per_page = $("#" + settings.rows_per_page_id),
                    elem_go_to_page = $("#" + settings.go_to_page_id),
                    elem_page_num = $("#" + settings.page_num_id),
                    elem_total_pages = $("#" + settings.total_pages_id),
                    elem_addnew_record = $("#" + settings.addnew_record_id),
                    elem_columns_to_display = $("#" + settings.columns_to_display_id),
                    elem_columns_switcher = $("#" + settings.columns_switcher_id),
                    elem_columns_sortable = $(settings.col_sortable_selector),
                    elem_sort_simple_field = $("#" + settings.sort_simple_field_id),
                    elem_sort_simple_mode = $("#" + settings.sort_simple_mode_id),
                    elem_sort_advanced = $("#" + settings.sort_advanced_id),

                    elem_criteria_apply = $("#" + settings.criteria_apply_id),
                    elem_criteria_reset = $("#" + settings.criteria_reset_id),

                    elem_export_csv = $("#" + settings.export_csv_id),
                    elem_export_csv_buttons = $(settings.export_csv_buttons_selector),

                    v_default_page_num = settings.default_page_num,
                    v_export_csv_none = settings.export_csv_none,
                    v_export_csv_encoding_default = settings.export_csv_encoding_default,
                    v_export_csv_encoding_utf8 = settings.export_csv_encoding_utf8,

                    criteria = settings.criteria,
                    elem_criteria,
                    elem_criteria_mode,
                    datepicker_params;

                var getCriterionByName = function(criterion_name) {

                    var criterion = {};
                    $.each(criteria, function(key, value) {
                        if(value.criteria_name === criterion_name) {
                            criterion = value;
                            return false;
                        }
                    });
                    return criterion;
                };

                var arrange_criteria = function(criteria_type) {

                    switch(criteria_type) {
                        case "text":
                            $.each(criteria, function(key, value) {

                                if(value.criteria_type === "text") {
                                    elem_criteria_mode = $("#" + value.dropdown_id);
                                    elem_criteria = $("#" + value.input_id);

                                    if(parseInt(elem_criteria_mode.val()) === value.default_mode) {
                                        elem_criteria.val("");
                                    }
                                }

                            });
                            break;

                        case "lookup":
                            $.each(criteria, function(key, value) {

                                if(value.criteria_type === "lookup") {

                                    elem_criteria_mode = $("#" + value.dropdown_id);
                                    elem_criteria = $("#" + value.dropdown_lookup_id);

                                    if(parseInt(elem_criteria_mode.val()) === value.mode_equal) {
                                        elem_criteria.show();
                                    } else {
                                        elem_criteria.hide();
                                    }
                                }

                            });
                            break;

                        case "date_start":

                            $.each(criteria, function(key, value) {

                                if(value.criteria_type === "date_start") {
                                    elem_criteria_mode = $("#" + value.dropdown_id);
                                    elem_criteria = $("#" + value.input_id);

                                    var assoc = getCriterionByName(value.associated_criteria_name),
                                        elem_criteria_label_assoc = $("#" + assoc.label_id),
                                        elem_criteria_mode_assoc = $("#" + assoc.dropdown_id),
                                        elem_criteria_assoc = $("#" + assoc.input_id);

                                    var criteria_date_mode = parseInt(elem_criteria_mode.val());
                                    if(criteria_date_mode === value.default_mode) {
                                        elem_criteria.val("");
                                    }
                                    if(criteria_date_mode === value.mode_equal) {
                                        elem_criteria_label_assoc.hide();
                                        elem_criteria_mode_assoc.val(value.default_mode);
                                        elem_criteria_mode_assoc.hide();
                                        elem_criteria_assoc.val("");
                                        elem_criteria_assoc.hide();
                                    } else {
                                        elem_criteria_label_assoc.show();
                                        elem_criteria_mode_assoc.show();
                                        elem_criteria_assoc.show();
                                    }

                                }

                            });
                            break;

                        case "date_end":
                            $.each(criteria, function(key, value) {

                                if(value.criteria_type === "date_end") {
                                    elem_criteria_mode = $("#" + value.dropdown_id);
                                    elem_criteria = $("#" + value.input_id);
                                    if(parseInt(elem_criteria_mode.val()) === value.default_mode) {
                                        elem_criteria.val("");
                                    }
                                }

                            });
                            break;
                    }

                };


                /* initialize criteria -------------------------------------- */
                arrange_criteria("text");
                arrange_criteria("lookup");
                arrange_criteria("date_start");
                arrange_criteria("date_end");

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

                elem_rows_per_page.change(function() {
                    elem_page_num.val(v_default_page_num);
                    doSubmit("rows_per_page");
                });

                elem_go_to_page.click(function() {
                    doSubmit("go_to_page");
                });

                elem_page_num.keypress(function(e) {
                    // if the key pressed is the enter key
                    if(e.which === 13) {
                        elem_go_to_page.click();
                    }
                });

                /* add new record button ------------------------------------ */
                elem_addnew_record.click(function() {
                    location.href = settings.url_addnew_record;
                });

                /* columns to display --------------------------------------- */
                elem_columns_switcher.click(function() {
                    if(parseInt(elem_columns_to_display.val()) === settings.columns_default) {
                        elem_columns_to_display.val(settings.columns_more)
                    } else {
                        elem_columns_to_display.val(settings.columns_default)
                    }
                    doSubmit("columns_switcher");
                });

                /* simple (column) sorting ---------------------------------- */
                elem_columns_sortable.click(function() {
                    var col_id = $(this).attr("id");
                    if(elem_sort_simple_field.val() === col_id) {
                        if(elem_sort_simple_mode.val() === "ASC") {
                            elem_sort_simple_mode.val("DESC");
                        } else {
                            elem_sort_simple_mode.val("ASC");
                        }
                    } else {
                        elem_sort_simple_field.val(col_id);
                        elem_sort_simple_mode.val("ASC");
                    }
                    doSubmit("sort_simple");
                });

                /* advanced sorting ----------------------------------------- */
                elem_sort_advanced.change(function() {
                    elem_sort_simple_field.val("");
                    elem_sort_simple_mode.val("");
                    doSubmit("sort_advanced");
                });

                /* criteria ------------------------------------------------- */
                $.each(criteria, function(key, value) {
                    if(value.criteria_type === "text") {
                        elem_criteria_mode = $("#" + value.dropdown_id);
                        elem_criteria = $("#" + value.input_id);

                        elem_criteria_mode.change(function() {
                            arrange_criteria("text");
                            disableExport();
                        });

                        elem_criteria.on('input', function() {
                            disableExport();
                        });

                        elem_criteria.keypress(function(e) {
                            // if the key pressed is the enter key
                            if(e.which === 13) {
                                elem_criteria_apply.click();
                            }
                        });
                    }
                    if(value.criteria_type === "lookup") {
                        elem_criteria_mode = $("#" + value.dropdown_id);
                        elem_criteria = $("#" + value.dropdown_lookup_id);

                        elem_criteria.change(function() {
                            disableExport();
                        });

                        elem_criteria_mode.change(function() {
                            arrange_criteria("lookup");
                            disableExport();
                        });

                    }
                    if(value.criteria_type === "date_start") {
                        elem_criteria_mode = $("#" + value.dropdown_id);
                        elem_criteria = $("#" + value.input_id);

                        datepicker_params = value.datepicker_params;
                        datepicker_params.onSelect = function() {
                            disableExport();
                        };
                        elem_criteria.datepicker(datepicker_params);

                        elem_criteria_mode.change(function() {
                            arrange_criteria("date_start");
                            disableExport();
                        });

                        elem_criteria.on('input', function() {
                            disableExport();
                        });

                        elem_criteria.keypress(function(e) {
                            // if the key pressed is the enter key
                            if(e.which === 13) {
                                elem_criteria_apply.click();
                            }
                        });
                    }
                    if(value.criteria_type === "date_end") {
                        elem_criteria_mode = $("#" + value.dropdown_id);
                        elem_criteria = $("#" + value.input_id);

                        datepicker_params = value.datepicker_params;
                        datepicker_params.onSelect = function() {
                            disableExport();
                        };
                        elem_criteria.datepicker(datepicker_params);

                        elem_criteria_mode.change(function() {
                            arrange_criteria("date_end");
                            disableExport();
                        });

                        elem_criteria.on('input', function() {
                            disableExport();
                        });

                        elem_criteria.keypress(function(e) {
                            // if the key pressed is the enter key
                            if(e.which === 13) {
                                elem_criteria_apply.click();
                            }
                        });
                    }
                });


                /* apply criteria ------------------------------------------- */
                elem_criteria_apply.click(function() {
                    doSubmit("apply_criteria");
                });

                /* clear criteria ------------------------------------------- */
                elem_criteria_reset.click(function() {
                    elem_page_num.val(1);
                    $.each(criteria, function(key, value) {
                        if(value.criteria_type === "text") {
                            var elem_criteria_mode = $("#" + value.dropdown_id),
                                elem_criteria = $("#" + value.input_id);
                            elem_criteria.val("");
                            elem_criteria_mode.val(value.default_mode);
                        }
                        if(value.criteria_type === "lookup") {
                            elem_criteria_mode = $("#" + value.dropdown_id);
                            elem_criteria_mode.val(value.default_mode);
                        }
                        if(value.criteria_type === "date_start") {
                            elem_criteria_mode = $("#" + value.dropdown_id);
                            elem_criteria = $("#" + value.input_id);
                            elem_criteria.val("");
                            elem_criteria_mode.val(value.default_mode);
                        }
                        if(value.criteria_type === "date_end") {
                            elem_criteria_mode = $("#" + value.dropdown_id);
                            elem_criteria = $("#" + value.input_id);
                            elem_criteria.val("");
                            elem_criteria_mode.val(value.default_mode);
                        }
                    });
                    doSubmit("reset_criteria");
                });

                /* csv export ----------------------------------------------- */
                elem_export_csv_buttons.click(function() {
                    var elem_id = $(this).attr("id"),
                        csv_encoding = elem_id.substr(12);
                    if(csv_encoding === "local") {
                        elem_export_csv.val(v_export_csv_encoding_default);
                    } else {
                        elem_export_csv.val(v_export_csv_encoding_utf8);
                    }
                    elem_php_bs_grid_form.submit();
                });

                /**
                 * Validate criteria and submit form
                 */
                var doSubmit = function(action) {

                    var no_errors = true,
                        text_inputs_contain_value = false,
                        ajax_data_to_pass = {};

                    $.each(criteria, function(key, value) {

                        if(value.criteria_type === "text") {

                            var elem_criteria_mode = $("#" + value.dropdown_id),
                                elem_criteria = $("#" + value.input_id);

                            if(elem_criteria.val()) {
                                text_inputs_contain_value = true;
                            }
                            ajax_data_to_pass[value.input_id] = elem_criteria.val();

                            if(parseInt(elem_criteria_mode.val()) === value.default_mode && elem_criteria.val()) {
                                no_errors = false;
                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, value.msg_missing_mode, value.dropdown_id);
                                return false;
                            }
                            if(parseInt(elem_criteria_mode.val()) !== value.default_mode && !elem_criteria.val()) {
                                no_errors = false;
                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, value.msg_missing_value, value.input_id);
                                return false;
                            }
                        }

                        if(value.criteria_type === "date_start") {

                            elem_criteria_mode = $("#" + value.dropdown_id);
                            elem_criteria = $("#" + value.input_id);

                            if(elem_criteria.val()) {
                                text_inputs_contain_value = true;
                            }

                            ajax_data_to_pass[value.dropdown_id] = elem_criteria_mode.val();
                            ajax_data_to_pass[value.input_id] = elem_criteria.val();

                            if(parseInt(elem_criteria_mode.val()) === value.default_mode && elem_criteria.val()) {
                                no_errors = false;
                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, value.msg_missing_mode, value.dropdown_id);
                                return false;
                            }
                            if(parseInt(elem_criteria_mode.val()) !== value.default_mode && !elem_criteria.val()) {
                                no_errors = false;
                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, value.msg_missing_value, value.input_id);
                                return false;
                            }
                        }

                        if(value.criteria_type === "date_end") {

                            elem_criteria_mode = $("#" + value.dropdown_id);
                            elem_criteria = $("#" + value.input_id);

                            if(elem_criteria.val()) {
                                text_inputs_contain_value = true;
                            }

                            ajax_data_to_pass[value.dropdown_id] = elem_criteria_mode.val();
                            ajax_data_to_pass[value.input_id] = elem_criteria.val();

                            if(parseInt(elem_criteria_mode.val()) === value.default_mode && elem_criteria.val()) {
                                no_errors = false;
                                show_bs_modal(bs_modal_id, elem_bs_modal_content, value.msg_missing_mode, value.dropdown_id);
                                return false;
                            }
                            if(parseInt(elem_criteria_mode.val()) !== value.default_mode && !elem_criteria.val()) {
                                no_errors = false;
                                show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, value.msg_missing_value, value.input_id);
                                return false;
                            }
                        }

                    });

                    if(no_errors) {
                        if(text_inputs_contain_value) {
                            $.ajax({
                                url: settings.ajax_validate_form_url,
                                type: "POST",
                                data: ajax_data_to_pass,
                                dataType: "json",
                                success: function(data) {
                                    if(data["error"]) {
                                        show_bs_modal(settings.bs_modal_id, settings.bs_modal_content_id, data["error"], data["focus"]);
                                    } else {
                                        doSubmitNoErrors(action);
                                    }
                                }
                            });
                        } else {
                            doSubmitNoErrors(action);
                        }
                    }

                };

                var doSubmitNoErrors = function(action) {
                    switch(action) {
                        case "go_top":
                            elem_page_num.val(v_default_page_num);
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
                        case "rows_per_page":
                            elem_page_num.val(v_default_page_num);
                            break;
                        case "go_to_page":
                            break;
                    }

                    elem_export_csv.val(v_export_csv_none);
                    elem_php_bs_grid_form.submit();
                };

                var disableExport = function() {
                    elem_export_csv_buttons.prop("disabled", true);
                };

                var show_bs_modal = function(bs_modal_id, bs_modal_content_id, content_html, elem_focus_id) {

                    var elem_bs_modal = $("#" + bs_modal_id),
                        elem_bs_modal_content = $("#" + bs_modal_content_id);

                    if(elem_focus_id) {
                        var elem_focus = $("#" + elem_focus_id);
                        elem_bs_modal.on('hidden.bs.modal', function(e) {
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
                go_top_id: "go_top",
                go_back_id: "go_back",
                go_forward_id: "go_forward",
                go_bottom_id: "go_bottom",
                rows_per_page_id: "rows_per_page",
                page_num_id: "page_num",
                go_to_page_id: "go_to_page",
                total_pages_id: "total_pages", // hidden
                addnew_record_id: "addnew_record",
                columns_switcher_id: "columns_switcher",
                columns_to_display_id: "columns_to_display", // hidden
                col_sortable_class: ".col-sortable",
                col_sortable_selector: 'th.col-sortable',
                sort_simple_field_id: "sort_simple_field",
                sort_simple_mode_id: "sort_simple_mode",
                sort_advanced_id: "sort_advanced",

                criteria_apply_id: "criteria_apply",
                criteria_reset_id: "criteria_reset",

                export_csv_id: "export_csv",
                export_csv_buttons_selector: '[id^="export_data_"]',

                default_page_num: 1,
                columns_default: 1,
                columns_more: 2,
                export_csv_none: 1,
                export_csv_encoding_default: 2,
                export_csv_encoding_utf8: 3
            };
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