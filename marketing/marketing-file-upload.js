(function (global) {
    'use strict';

    var config = global.ecmd_plugin_vars || global.ECMD_PLUGIN_VARS || {};
    var PLUGIN_SLUG = config.pluginSlug || 'contact-form-extender-for-divi-builder';
    var PLUGIN_INIT = config.pluginInit || 'contact-form-extender-for-divi-builder/contact-form-extender-for-divi-builder.php';
    var STATUS = config.status || 'not_installed';
    var AJAX_URL = config.ajaxurl || (typeof ajaxurl !== 'undefined' ? ajaxurl : '');
    var INSTALL_NONCE = config.installNonce || '';
    var ACTIVATE_NONCE = config.activateNonce || '';

    function buildButtonHtml(status, extraClasses) {
        extraClasses = extraClasses || '';
        if (status === 'active') {
            return '<span class="ecmd-d4-promo__btn ecmd-d4-promo__btn--active">' +
                'Contact Form Extender for Divi is active' +
                '</span>';
        }
        if (status === 'inactive') {
            return '<button type="button" class="ecmd-d4-promo__btn ecmd-activate-plugin-btn ' + extraClasses + '" data-init="' + PLUGIN_INIT + '">' +
                'Activate Plugin' +
                '</button>';
        }
        return '<button type="button" class="ecmd-d4-promo__btn ecmd-install-plugin-btn ' + extraClasses + '" data-slug="' + PLUGIN_SLUG + '" data-init="' + PLUGIN_INIT + '">' +
            'Install &amp; Activate' +
            '</button>';
    }

    function postAjax(action, payload) {
        if (!AJAX_URL) {
            return Promise.reject(new Error('Missing AJAX URL'));
        }
        var data = Object.assign({}, payload || {}, { action: action });
        if (action === 'ecmd_plugin_install') {
            data._ajax_nonce = INSTALL_NONCE;
            
        } else if (action === 'ecmd_plugin_activate') {
            data.security = ACTIVATE_NONCE;
        }

        var body = Object.keys(data)
            .map(function (k) {
                return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
            })
            .join('&');

        return fetch(AJAX_URL, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: body
        }).then(function (res) {
            return res.json();
        });
    }

    function handleButtonClick(event) {
        var target = event.target;
        if (!target) return;

        if (target.classList.contains('ecmd-install-plugin-btn')) {
            var slug = target.getAttribute('data-slug') || PLUGIN_SLUG;
            var init = target.getAttribute('data-init') || PLUGIN_INIT;
            if (!slug || !init) return;

            var originalText = target.textContent;
            target.disabled = true;
            target.textContent = 'Installing...';

            postAjax('ecmd_plugin_install', { slug: slug })
                .then(function (res) {
                    if (!res || !res.success) {
                        throw new Error(res && res.data && res.data.message ? res.data.message : 'Installation failed');
                    }
                    target.textContent = 'Activating...';
                    return postAjax('ecmd_plugin_activate', { init: init });
                })
                .then(function (res) {
                    if (!res || !res.success) {
                        throw new Error(res && res.data && res.data.message ? res.data.message : 'Activation failed');
                    }
                    if (typeof location !== 'undefined') {
                        location.reload();
                    }
                })
                .catch(function (err) {
                    alert(err && err.message ? err.message : 'Installation/activation failed. Please try again from Plugins page.');
                    target.disabled = false;
                    target.textContent = originalText;
                });
        } else if (target.classList.contains('ecmd-activate-plugin-btn')) {
            var initFile = target.getAttribute('data-init') || PLUGIN_INIT;
            if (!initFile) return;

            var original = target.textContent;
            target.disabled = true;
            target.textContent = 'Activating...';

            postAjax('ecmd_plugin_activate', { init: initFile })
                .then(function (res) {
                    if (!res || !res.success) {
                        throw new Error(res && res.data && res.data.message ? res.data.message : 'Activation failed');
                    }
                    if (typeof location !== 'undefined') {
                        location.reload();
                    }
                })
                .catch(function (err) {
                    alert(err && err.message ? err.message : 'Activation failed. Please try again from Plugins page.');
                    target.disabled = false;
                    target.textContent = original;
                });
        }
    }

    if (global.vendor && global.vendor.wp && global.vendor.wp.hooks) {
        var PROMO_MSG = '<div class="ecmd-promo-notice" style="position:relative;padding:16px 40px 16px 16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;color:#475569;font-size:13px;line-height:1.6;">' +
            '<button type="button" onclick="var e=this.closest(\\\'ecmd-promo-notice\\\');e&&(e.style.display=\\\'none\\\')" style="position:absolute;top:8px;right:8px;width:28px;height:28px;padding:0;border:none;background:#e2e8f0;color:#64748b;border-radius:4px;cursor:pointer;font-size:20px;line-height:26px;text-align:center;" aria-label="Close">×</button>' +
            '<h4 style="margin:0 0 8px;color:#334155;font-size:14px;font-weight:600;">Want better form management?</h4>' +
            '<p style="margin:0 0 14px;color:#475569;font-size:13px;line-height:1.5;">Save submissions, add file upload, and extend your Divi Form with Contact Form Extender for Divi.</p>' +
            buildButtonHtml(STATUS, '') +
            '</div>';
        var GROUP = { panel: 'content', priority: 250, multiElements: true, groupName: 'ecmdFileUploadPromo', component: { name: 'divi/composite', props: { groupLabel: 'Save submissions' } } };
        var ATTR = { type: 'string', default: '', settings: { innerContent: { groupType: 'group-item', item: { groupSlug: 'ecmdFileUploadPromo', priority: 10, render: true, attrName: 'ecmdMarketingFileUploadPromo', label: '', component: { type: 'field', name: 'divi/warning', props: { message: PROMO_MSG } } } } } };
        var addGroups = function (g, m) { return (m && m.name === 'divi/contact-form') ? Object.assign({}, g || {}, { ecmdFileUploadPromo: GROUP }) : g; };
        var addAttrs = function (a) { return Object.assign({}, a || {}, { ecmdMarketingFileUploadPromo: ATTR }); };
        var h = global.vendor.wp.hooks;
        if (h) {
            h.addFilter('divi.moduleLibrary.moduleSettings.groups.divi.contact-form', 'ecmd_marketing', addGroups);
            h.addFilter('divi.moduleLibrary.moduleAttributes.divi.contact-form', 'ecmd_marketing', addAttrs);
        }
    } else if (typeof global.jQuery !== 'undefined') {
        var $ = global.jQuery;
        var PROMO_HTML = '<div class="ecmd-d4-promo">' +
            '<button type="button" class="ecmd-d4-promo__close" aria-label="Close">×</button>' +
            '<h4 class="ecmd-d4-promo__title">Want better form management?</h4>' +
            '<p class="ecmd-d4-promo__text">Save submissions, add file upload, and extend your Divi Form with Contact Form Extender for Divi.</p>' +
            buildButtonHtml(STATUS, '') +
            '</div>';
        var injectPromo = function () {
            var $container = $('[data-name="ecmd_file_upload_promo"]');
            if (!$container.length || $container.find('.ecmd-d4-promo').length) return false;
            $container.find('input[name="ecmd_marketing_file_upload_promo"]').closest('.et-fb-settings-options').remove();
            var $content = $container.find('.et-fb-form__group').first();
            return $content.length ? ($content.prepend(PROMO_HTML), true) : false;
        };
        $(document).on('click', '[data-name="ecmd_file_upload_promo"] .et-fb-form__toggle-title', function () {
            requestAnimationFrame(injectPromo);
        });
        $(document).on('click', '[data-name="ecmd_file_upload_promo"] .ecmd-d4-promo__close', function () {
            $(this).closest('.ecmd-d4-promo').hide();
        });
        $(function () { injectPromo(); });
    }

    if (typeof document !== 'undefined') {
        document.addEventListener('click', handleButtonClick, false);
    }

})(typeof window !== 'undefined' ? window : this);
