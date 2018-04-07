define('Autocomplete', ['jquery', 'core', 'jquery-ui/autocomplete'], function (jQuery, core, autocomplete) {
    var Autocomplete = {};
    Autocomplete.cssLoaded = false;
    Autocomplete.init = {
        css: function () {
            if (!Autocomplete.cssLoaded) {
                core.loadCss("jquery-ui/themes/base/jquery-ui.css", true);
                Autocomplete.cssLoaded = true;
            }
        },
        select: function () {
            if (typeof jQuery.combobox === 'undefined') {
                jQuery.widget("custom.combobox", {
                    _create: function () {
                        this.wrapper = jQuery("<span>")
                                .addClass("custom-combobox")
                                .insertAfter(this.element);

                        this.element.hide();
                        this._createAutocomplete();
                        this._createShowAllButton();
                    },
                    _createAutocomplete: function () {
                        var selected = this.element.children(":selected"),
                                value = selected.val() ? selected.text() : "";

                        this.input = jQuery("<input>")
                                .appendTo(this.wrapper)
                                .val(value)
                                .data('required-msg', this.element.data('required-msg'))
                                .addClass("custom-combobox-input ui-widget ui-widget-content ui-corner-left")
                                .autocomplete({
                                    delay: 0,
                                    minLength: 0,
                                    source: jQuery.proxy(this, "_source")
                                })
                                .tooltip({
                                    classes: {
                                        "ui-tooltip": "ui-state-highlight"
                                    }
                                });

                        this._on(this.input, {
                            autocompleteselect: function (event, ui) {
                                ui.item.option.selected = true;
                                this._trigger("select", event, {
                                    item: ui.item.option
                                });
                            },
                            autocompletechange: "_removeIfInvalid"
                        });
                    },
                    _createShowAllButton: function () {
                        var input = this.input,
                                wasOpen = false;

                        jQuery("<a>")
                                .attr("tabIndex", -1)
                                .tooltip()
                                .appendTo(this.wrapper)
                                .button({
                                    icons: {
                                        primary: "ui-icon-triangle-1-s"
                                    },
                                    text: false
                                })
                                .removeClass("ui-corner-all")
                                .addClass("custom-combobox-toggle ui-corner-right")
                                .on("mousedown", function () {
                                    wasOpen = input.autocomplete("widget").is(":visible");
                                })
                                .on("click", function () {
                                    input.trigger("focus");

                                    // Close if already visible
                                    if (wasOpen) {
                                        return;
                                    }

                                    // Pass empty string as value to search for, displaying all results
                                    input.autocomplete("search", "");
                                });
                    },
                    _source: function (request, response) {
                        var matcher = new RegExp(jQuery.ui.autocomplete.escapeRegex(request.term), "i");
                        response(this.element.children("option").map(function () {
                            var text = jQuery(this).text();
                            if (this.value && (!request.term || matcher.test(text)))
                                return {
                                    label: text,
                                    value: text,
                                    option: this
                                };
                        }));
                    },
                    _removeIfInvalid: function (event, ui) {

                        // Selected an item, nothing to do
                        if (ui.item) {
                            return;
                        }

                        // Search for a match (case-insensitive)
                        var value = this.input.val(),
                                valueLowerCase = value.toLowerCase(),
                                valid = false;
                        this.element.children("option").each(function () {
                            if (jQuery(this).text().toLowerCase() === valueLowerCase) {
                                this.selected = valid = true;
                                return false;
                            }
                        });

                        // Found a match, nothing to do
                        if (valid) {
                            return;
                        }

                        // Remove invalid value
                        this.input
                                .val("")
                                .attr("title", this.input.data('required-msg'))
                                .tooltip("open");
                        this.element.val("");
                        this._delay(function () {
                            this.input.tooltip("close").attr("title", "");
                        }, 2500);
                        this.input.autocomplete("instance").term = "";
                    },
                    _destroy: function () {
                        this.wrapper.remove();
                        this.element.show();
                    }
                });
            }
        }
    };
    Autocomplete.bind = {
        select: function (selector) {
            jQuery(selector).find("[data-autocomplete=select]").combobox();
        },
        ajax: {
            multi: function (selector) {
                jQuery(function () {
                    var cache = {};
                    function split(val) {
                        return val.split(/,\s*/);
                    }
                    function extractLast(term) {
                        return split(term).pop();
                    }
                    jQuery(selector).find("[data-autocomplete-multi-ajax-url]").each(function () {
                        var t = this;
                        jQuery(this).on("keydown", function (event) {
                            if (event.keyCode === jQuery.ui.keyCode.TAB &&
                                    jQuery(this).autocomplete("instance").menu.active) {
                                event.preventDefault();
                            }
                        }).autocomplete({
                            source: function (request, response) {
                                var data = {};
                                data.term = extractLast(request.term);
                                var term = data.term;
                                if (term in cache) {
                                    response(cache[ term ]);
                                    return;
                                }

                                jQuery.ajax({
                                    beforeSend: function (xhr) {

                                    },
                                    complete: function (jqXHR, textStatus) {

                                    },
                                    url: core.format.normalizeUrl(jQuery(t).data('autocomplete-multi-ajax-url')),
                                    data: data,
                                    method: 'GET',
                                    cache: true,
                                    dataType: 'json',
                                    success: function (r, status, xhr) {
                                        var result = typeof r === 'object' && typeof r.response === 'object' && typeof r.response.data === 'object' ? r.response.data : null;
                                        cache[ term ] = result;
                                        response(result);
                                    }
                                });


                            },
                            search: function () {
                                var term = extractLast(this.value);
                                if (term.length < 2) {
                                    return false;
                                }
                            },
                            focus: function () {
                                // prevent value inserted on focus
                                return false;
                            },
                            select: function (event, ui) {
                                var terms = split(this.value);
                                var show_result_field = jQuery(jQuery(this).data('autocomplete-hidden-result'));

                                // remove the current input
                                terms.pop();
                                // add the selected item
                                terms.push(ui.item.value);
                                // add placeholder to get the comma-and-space at the end
                                terms.push("");
                                this.value = terms.join(", ");
                                show_result_field.val(show_result_field.val() + ui.item.id + ', ');

                                return false;
                            }
                        });
                    });
                });
            },
            single: function (selector) {
                jQuery(function () {
                    jQuery(selector).find("[data-autocomplete-ajax-url]").each(function () {
                        var cache = {};
                        var search = [];
                        var t = this;
                        jQuery(this).autocomplete({
                            minLength: 2,
                            change: function (event, ui) {
                                var val = jQuery(this).val();
                                var exists = jQuery.inArray(val, search);
                                var show_result_field = jQuery(this).data('autocomplete-hidden-result');
                                if (exists < 0) {
                                    jQuery(this).val("");
                                    jQuery(show_result_field).val("");
                                    return false;
                                } else {
                                    return true;
                                }
                            },
                            select: function (event, ui) {
                                search.push(ui.item.label);
                                jQuery(this).val(ui.item.label);
                                var show_result_field = jQuery(this).data('autocomplete-hidden-result');
                                jQuery(show_result_field).val(ui.item.id);
                            },
                            source: function (request, response) {
                                var term = request.term;
                                if (term in cache) {
                                    response(cache[ term ]);
                                    return;
                                }

                                jQuery.ajax({
                                    beforeSend: function (xhr) {

                                    },
                                    complete: function (jqXHR, textStatus) {

                                    },
                                    url: core.format.normalizeUrl( jQuery(t).data('autocomplete-ajax-url')),
                                    data: request,
                                    method: 'GET',
                                    cache: true,
                                    dataType: 'json',
                                    success: function (data, status, xhr) {
                                        var result = typeof data === 'object' && typeof data.response === 'object' && typeof data.response.data === 'object' ? data.response.data : null;
                                        if (!result) {
                                            core.show.error(data.response && data.response.error ? data.response.error : jQuery(t).data('required-msg'), jQuery(t));
                                        }
                                        cache[ term ] = result;
                                        response(result);
                                    }
                                });
                            }
                        });
                    });
                });
            }
        }
    };
    return Autocomplete;
});