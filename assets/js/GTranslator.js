define('GTranslator', ['core'], function (core) {
    var GTranslator = {};
    GTranslator.url = 'https://translation.googleapis.com/language/translate/v2?';
    GTranslator.key = '';
    GTranslator.init = function (key) {
        GTranslator.key = key;
        GTranslator.bind.translate.automatic('[data-translate="gtranslate"]');
        GTranslator.bind.translate.revision('[data-translate="wtranslate"]');
        GTranslator.bind.translate.revised('[data-translate="rtranslate"]');
    };

    GTranslator.save = {
        waitTranslate: function (form) {
            jQuery.ajax({
                url: core.format.normalizeUrl(jQuery(form).attr('action')),
                method: 'POST',
                data: jQuery(form).serialize(),
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data && data.response && data.response.success) {
                        var qtd_wait_translate = parseInt(jQuery('[data-qtd-wait-translate]').data('qtd-wait-translate'));
                        var qtd_wait_revision = parseInt(jQuery('[data-qtd-wait-revision]').data('qtd-wait-revision'));
                        var clone = jQuery(jQuery(form).data('translate-container-clone')).clone();
                        jQuery('[data-qtd-wait-translate]').data('qtd-wait-translate', (qtd_wait_translate - 1)).html('(' + (qtd_wait_translate - 1) + ')');
                        jQuery('[data-qtd-wait-revision]').data('qtd-wait-revision', (qtd_wait_revision + 1)).html('(' + (qtd_wait_revision + 1) + ')');
                        jQuery.each(data.response.data, function (key, value) {
                            clone = jQuery('<div>').append(jQuery(clone)).html().replace(new RegExp('{' + key + '}', 'g'), value);
                        });
                        jQuery(jQuery(form).data('translate-container-target')).append(jQuery(clone).removeAttr('id').removeClass('clone').removeClass('hidden').removeAttr('data-clone').hide(function () {
                            var jQuerythis = this;
                            requirejs(['core'], function (core) {
                                core.bind(jQuerythis);
                            });
                            jQuery(this).delay(800).fadeIn();
                        }));
                        jQuery(form).slideUp(1000, function () {
                            jQuery(form).remove();
                        });
                    }
                }
            });
        },
        waitRevision: function (form) {
            jQuery.ajax({
                url: core.format.normalizeUrl(jQuery(form).attr('action')),
                method: 'POST',
                data: jQuery(form).serialize(),
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (data && data.response && data.response.success) {
                        var qtd_wait_revision = parseInt(jQuery('[data-qtd-wait-revision]').data('qtd-wait-revision'));
                        var qtd_revised = parseInt(jQuery('[data-qtd-revised]').data('qtd-revised'));
                        var clone = jQuery(jQuery(form).data('translate-container-clone')).clone();
                        jQuery('[data-qtd-wait-revision]').data('qtd-wait-revision', (qtd_wait_revision - 1)).html('(' + (qtd_wait_revision - 1) + ')');
                        jQuery('[data-qtd-revised]').data('qtd-revised', (qtd_revised + 1)).html('(' + (qtd_revised + 1) + ')');
                        jQuery.each(data.response.data, function (key, value) {
                            clone = jQuery('<div>').append(jQuery(clone)).html().replace(new RegExp('{' + key + '}', 'g'), value);
                        });
                        jQuery(jQuery(form).data('translate-container-target')).append(jQuery(clone).removeAttr('id').removeClass('clone').removeClass('hidden').removeAttr('data-clone').hide(function () {
                            var jQuerythis = this;
                            requirejs(['core'], function (core) {
                                core.bind(jQuerythis);
                            });
                            jQuery(this).delay(800).fadeIn();
                        }));
                        jQuery(form).slideUp(1000, function () {
                            jQuery(form).remove();
                        });
                    }
                }
            });
        },
        revised: function (form) {
            jQuery.ajax({
                url: core.format.normalizeUrl(jQuery(form).attr('action')),
                method: 'POST',
                data: jQuery(form).serialize(),
                dataType: 'json'
            });
        }
    };

    GTranslator.bind = {
        translate: {
            revised: function (selector) {
                jQuery(selector).each(function () {
                    var form = jQuery(this);
                    jQuery(this).submit(function (e) {
                        e.preventDefault();
                        GTranslator.save.revised(form);
                    });
                });
            },
            revision: function (selector) {
                jQuery(selector).each(function () {
                    var form = jQuery(this);
                    jQuery(this).submit(function (e) {
                        e.preventDefault();
                        GTranslator.save.waitRevision(form);
                    });
                });
            },
            automatic: function (selector) {
                jQuery(selector).each(function () {
                    var form = jQuery(this);
                    jQuery(this).submit(function (e) {
                        e.preventDefault();
                        var source = jQuery(form).data('souce-lang');
                        var target = jQuery(form).data('target-lang');
                        var translate_target = jQuery(form).data('translate-target');
                        var q = jQuery(form).find(jQuery(form).data('translate-text')).val();
                        var url = GTranslator.getUrl(source, target, q);
                        jQuery.ajax({
                            url: core.format.normalizeUrl(url),
                            method: 'GET',
                            dataType: 'json',
                            complete: function (data, textStatus, jqXHR) {
                                /*
                                 * @todo arrumar aqui pra quando tiver a chave do translator
                                 */
                                var translate = q;
                                jQuery(translate_target).val(translate);
                                GTranslator.save.waitTranslate(form);
                            }
                        });

                    });
                });
            }
        }
    };

    GTranslator.getUrl = function (source, target, q) {
        return GTranslator.url + 'key=' + GTranslator.key + '&source=' + source + '&target=' + target + '&q=' + q;
    };
    return GTranslator;
});