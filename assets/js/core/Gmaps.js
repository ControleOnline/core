define(['GMaps'], function () {
    var GMaps = {};
    GMaps.init = {
        panoramio: function (key, selector) {
            google.load('maps', '3', {
                other_params: 'libraries=places&key=' + key,
                callback: function () {                    
                    GMaps.search.panoramio(selector);
                }
            });
        },
        address_search: function (key, selector) {
            google.load('maps', '3', {
                other_params: 'libraries=places&key=' + key,
                callback: function () {
                    GMaps.bind.address_search(selector);
                }
            });
        },
        autocomplete: function (key, callback) {
            google.load('maps', '3', {
                other_params: 'libraries=places&key=' + key,
                callback: function () {
                    GMaps.bind.autocomplete(callback);
                }
            });
        }
    };
    GMaps.bind = {
        place_changed: function (autocomplete, callback) {
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                callback(place.geometry.location);
            });
        },
        address_search: function (selector) {
            jQuery(selector).change(function () {
                GMaps.search.address(this);
            });
        },
        autocomplete: function (callback) {
            GMaps.search.autocomplete(callback);
        }
    };
    GMaps.form = {
        clear: function (form) {
            jQuery(form).find('input[data-street]').val('');
            jQuery(form).find('input[data-country]').val('');
            jQuery(form).find('input[data-country-code]').val('');
            jQuery(form).find('input[data-state]').val('');
            jQuery(form).find('input[data-city]').val('');
            jQuery(form).find('input[data-district]').val('');
            jQuery(form).find('input[data-address-number]').val('');
        },
        enable: function (form) {
            jQuery(form).find('input[data-street]').prop('readonly', false);
            jQuery(form).find('input[data-country]').prop('readonly', false);
            jQuery(form).find('input[data-country-code]').prop('readonly', false);
            jQuery(form).find('input[data-state]').prop('readonly', false);
            jQuery(form).find('input[data-city]').prop('readonly', false);
            jQuery(form).find('input[data-district]').prop('readonly', false);
        }
    };
    GMaps.format = {
        address: function (form, selector) {
            var search = jQuery(form).find(selector);
            var address = '', separator, has_address = false, incomplete;
            jQuery.each(search, function (key, value) {
                if ((jQuery(value).attr('name') == 'street' && !jQuery(value).val()) || (jQuery(value).attr('name') == 'address-number' && !jQuery(value).val())) {
                    incomplete = true;
                }
                if (jQuery(value).val()) {
                    if (jQuery(value).attr('name') == 'address-number') {
                        separator = ', ';
                    } else if (jQuery(value).attr('name') == 'street') {
                        has_address = true;
                        separator = '';
                    } else {
                        separator = ' - ';
                    }
                    address = address + separator + jQuery(value).val();
                }
            });
            if (!has_address) {
                var default_contry = jQuery(form).find('input[name="cep"]').data('default-contry');
                address = jQuery(form).find('input[name="cep"]').val() + address + (default_contry && !jQuery(form).find('input[name="country"]').val() ? ' - ' + default_contry : '');
            }
            return incomplete ? false : address;
        }
    };
    GMaps.changeCoords = function (form, results) {
        var latitude = results[0].geometry.location.lat();
        var longitude = results[0].geometry.location.lng();
        jQuery(form).find('input[data-lat]').val(latitude);
        jQuery(form).find('input[data-lng]').val(longitude);
    }
    GMaps.search = {
        autocomplete: function (callback) {
            var input = (document.getElementById('gmaps-autocomplete'));
            var autocomplete = new google.maps.places.Autocomplete(input);
            GMaps.bind.place_changed(autocomplete, callback);
        },
        panoramio: function (selector) {            
            jQuery.each(jQuery(selector), function () {
                var t = jQuery(this);
                var form = jQuery(t).closest('form');
                var map_container = document.getElementById(jQuery(form).data('svm-id'));
                var panoramio_container = document.getElementById(jQuery(form).data('svp-id'));
                if (map_container && panoramio_container) {
                    jQuery.each(jQuery(form).find('.panoramio-search'), function () {
                        jQuery(this).change(function () {
                            setTimeout(function () {
                                var address = GMaps.format.address(form, '.panoramio-search');
                                if (address != '') {
                                    var geocoder = new google.maps.Geocoder();
                                    geocoder.geocode({'address': address}, function (results, status) {
                                        if (status === google.maps.GeocoderStatus.OK && results && results[0] && results[0].geometry && results[0].geometry.location) {
                                            GMaps.changeCoords(jQuery(t).closest('form'), results);
                                            var fenway = results[0].geometry.location;
                                            var map = new google.maps.Map(map_container, {
                                                center: fenway,
                                                zoom: 14
                                            });
                                            var panorama = new google.maps.StreetViewPanorama(
                                                    panoramio_container, {
                                                        position: fenway,
                                                        pov: {
                                                            heading: 34,
                                                            pitch: 10
                                                        }
                                                    });
                                            map.setStreetView(panorama);
                                            jQuery(panoramio_container).parent().show();
                                        } else {
                                            jQuery(map_container).html('').parent().hide();
                                            jQuery(panoramio_container).html('').parent().hide();
                                        }
                                    });
                                } else {
                                    jQuery(map_container).html('').parent().hide();
                                    jQuery(panoramio_container).html('').parent().hide();
                                }
                            }, 500);
                        });
                    });
                    setTimeout(function () {
                        jQuery(form).find('.panoramio-search:first').trigger('change');
                    }, 800);
                }
            });
        },
        address: function (address_search) {
            var geocoder = new google.maps.Geocoder();
            var address = jQuery(address_search).val();// + (jQuery(address_search).data('default-contry') ? ' - ' + jQuery(address_search).data('default-contry') : '');
            var form = jQuery(address_search).closest('form');
            geocoder.geocode({'address': address}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK && results && results[0] && results[0].address_components) {
                    GMaps.form.clear(form);
                    GMaps.form.enable(form);
                    GMaps.changeCoords(form, results);
                    jQuery.each(results[0].address_components, function (index, value) {
                        if (jQuery.inArray('route', value.types) >= 0) {
                            jQuery(form).find('input[data-street]').val(value.long_name);
                        }
                        if (jQuery.inArray('country', value.types) >= 0) {
                            jQuery(form).find('input[data-country]').val(value.long_name);
                            jQuery(form).find('input[data-country]').change();
                        }
                        if (jQuery.inArray('country', value.types) >= 0) {
                            jQuery(form).find('input[data-country-code]').val(value.short_name).prop('readonly', true);
                            jQuery(form).find('input[data-country]').val(value.long_name);
                        }
                        if (jQuery.inArray('administrative_area_level_1', value.types) >= 0) {
                            jQuery(form).find('input[data-state]').val(value.short_name);
                        }
                        if (jQuery.inArray('administrative_area_level_2', value.types) >= 0) {
                            jQuery(form).find('input[data-city]').val(value.long_name);
                        }
                        if (jQuery.inArray('sublocality_level_1', value.types) >= 0 || jQuery.inArray('administrative_area_level_4', value.types) >= 0) {
                            jQuery(form).find('input[data-district]').val(value.long_name);
                        }
                    });
                } else {
                    requirejs(['core'], function (core) {
                        core.show.error('Geocode was not successful for the following reason: ' + status);
                    });
                }
            });
        }
    }
    return GMaps;
});