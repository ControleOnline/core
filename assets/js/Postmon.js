define(['Postmon'], function () {    var Postmon = {};    Postmon.api = '//api.postmon.com.br/v1/cep/';    Postmon.init = function (selector) {        jQuery(selector).change(function () {            var field = jQuery(this);            var cep = jQuery(field).val();            var form = jQuery(field).closest('form');            if (cep) {                jQuery.ajax({                    xhrFields: {                        withCredentials: false                    },                    url: Postmon.api + cep,                    error: function (jqXHR, textStatus, errorThrown) {                        Postmon.form.clear(form);                        jQuery(form).find('input[data-country]').val('Brasil');                        Postmon.form.enable(form);                    },                    success: function (data, textStatus, jqXHR) {                        if (data && data.logradouro) {                            Postmon.form.clear(form);                            Postmon.form.fill(form, data);                            jQuery(form).find('input[data-street]').change();                            jQuery(form).find('input[data-address-number]').focus();                        } else {                            Postmon.form.clear(form);                            Postmon.form.fill(form, data);                            Postmon.form.enable(form);                        }                        Postmon.form.clear_selected_address(form);                    }                });            }        });    };    Postmon.form = {        clear_selected_address: function (form) {            jQuery(jQuery(form).data('select-address')).find("option").removeAttr('selected')                    .filter('[value=""]')                    .attr('selected', true);            jQuery(form).find('.address-input-toggle').removeClass('hidden');        },        clear_all: function (form) {            Postmon.form.clear(form);            Postmon.form.clear_selected_address(form);            jQuery(form).find('input[data-cep]').val('');            jQuery('#' + jQuery(form).data('svp-id')).html('').parent().hide();            jQuery('#' + jQuery(form).data('svm-id')).html('').parent().hide();        },        fill: function (form, data) {            jQuery(form).find('input[data-street]').val(data.logradouro);            jQuery(form).find('input[data-state]').val(data.estado);            jQuery(form).find('input[data-country]').val('Brasil');            jQuery(form).find('input[data-city]').val(data.cidade);            jQuery(form).find('input[data-district]').val(data.bairro);        },        clear: function (form) {            jQuery(form).find('input[data-street]').val('');            jQuery(form).find('input[data-state]').val('');            jQuery(form).find('input[data-city]').val('');            jQuery(form).find('input[data-district]').val('');            jQuery(form).find('input[data-address-number]').val('');            jQuery(form).find('input[data-complement]').val('');        },        enable: function (form) {            jQuery(form).find('input[data-street]').prop('readonly', false);            jQuery(form).find('input[data-state]').prop('readonly', false);            jQuery(form).find('input[data-city]').prop('readonly', false);            jQuery(form).find('input[data-district]').prop('readonly', false);            jQuery(form).find('input[data-complement]').prop('readonly', false);        }    };    return Postmon;});            