<?php

namespace GiveGoogleAnalytics\Donations;

use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

/**
 * @since 2.0.0
 */
class RecordGoogleEventWithGA4OnFrontend
{
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @since 2.0.0
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @since 2.0.0
     * @return void
     */
    public function handleWpFooter()
    {
        if (!$this->canPrintScript()) {
            return;
        }

        ?>
        <script type="text/javascript" id="give-google-analitics-addon-wp-footer-js">

            // GA Enhance Ecommerce tracking.
            (function ($) {

                window.addEventListener('load', function give_ga_purchase(event) {

                    window.removeEventListener('load', give_ga_purchase, false);

                    var gtag = window[window['GoogleAnalyticsObject'] || 'gtag'];

                    document.cookie = 'give_source=' + get_parameter('utm_source') + ';path=/';
                    document.cookie = 'give_medium=' + get_parameter('utm_medium') + ';path=/';
                    document.cookie = 'give_campaign=' + get_parameter('utm_campaign') + ';path=/';
                    document.cookie = 'give_content=' + get_parameter('utm_content') + ';path=/';

                    // If gtag function is ready. Let's proceed.
                    if ('function' === typeof gtag) {
                        var give_forms = $('form.give-form');

                        // Loop through each form on page and provide an impression.
                        give_forms.each(function (index, form) {
                            form  = jQuery(form);
                            var form_id = form.find('input[name="give-form-id"]').val();
                            var form_title = form.find('input[name="give-form-title"]').val();
                            var decimal_separator = Give.form.fn.getInfo('decimal_separator', form.get(0));
                            var currency_code = form.attr('data-currency_code');
                            var default_donation_amount = Give.fn.unFormatCurrency(
                                form.get(0).querySelector('.give-final-total-amount').getAttribute('data-total'),
                                decimal_separator
                            );

                            gtag('event', 'view_item', {
                                currency: currency_code,
                                value: default_donation_amount,
                                items: [
                                    {
                                        item_id: form_id,
                                        item_name: form_title,
                                        item_brand: 'Fundraising',
                                        affiliation: '<?php echo esc_js(
                                            $this->settingRepository->getTrackAffiliation()
                                        )?>',
                                        item_category: '<?php echo esc_js(
                                            $this->settingRepository->getTrackCategory()
                                        )?>',
                                        item_list_name: '<?php echo esc_js(
                                            $this->settingRepository->getTrackListName()
                                        )?>',
                                    }
                                ]
                            });
                        });

                        // More code using $ as alias to jQuery
                        give_forms.on('submit', function (event) {
                            var form = jQuery(event.target);
                            var form_id = form.find('input[name="give-form-id"]').val();
                            var form_title = form.find('input[name="give-form-title"]').val();
                            var form_gateway = form.find('input[name="give-gateway"]').val();
                            var currency_code = form.attr('data-currency_code');
                            var decimal_separator = Give.form.fn.getInfo('decimal_separator', form.get(0));
                            var donation_amount = Give.fn.unFormatCurrency(
                                form.get(0).querySelector('.give-final-total-amount').getAttribute('data-total'),
                                decimal_separator
                            );
                            var isRecurring = '1' === form.find( 'input[name="_give_is_donation_recurring"]' ).val()

                            gtag('event', 'begin_checkout', {
                                currency: currency_code,
                                value: donation_amount,
                                items: [
                                    {
                                        item_id: form_id,
                                        item_name: form_title,
                                        item_brand: 'Fundraising',
                                        affiliation: '<?php echo esc_js(
                                            $this->settingRepository->getTrackAffiliation()
                                        )?>',
                                        item_category: '<?php echo esc_js(
                                            $this->settingRepository->getTrackCategory()
                                        )?>',
                                        item_category2: form_gateway,
                                        item_category3: isRecurring ? 'Subscription' : 'One-Time',
                                        item_list_name: '<?php echo esc_js(
                                            $this->settingRepository->getTrackListName()
                                        )?>',
                                        price: donation_amount,
                                        quantity: 1
                                    }
                                ]
                            });
                        });
                    }

                }, false);

                /**
                 * Get specific parameter value from Query string.
                 * @param {string} parameter Parameter of query string.
                 * @param {object} data Set of data.
                 * @return bool
                 */
                function get_parameter(parameter, data) {

                    if (!parameter) {
                        return false;
                    }

                    if (!data) {
                        data = window.location.href;
                    }

                    var parameter = parameter.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
                    var expr = parameter + "=([^&#]*)";
                    var regex = new RegExp(expr);
                    var results = regex.exec(data);

                    if (null !== results) {
                        return results[1];
                    } else {
                        return '';
                    }
                }

            })(jQuery); //
        </script>
        <?php
    }

    /**
     * @since 2.0.0
     * @return void
     */
    public function handleGiveEmbedFooter()
    {
        if (!$this->canPrintScript()) {
            return;
        }
        ?>
        <script type="text/javascript" id="give-google-analitics-addon-give-embed-footer-js">
            var gtag = window.parent[window.parent['GoogleAnalyticsObject'] || 'gtag'];

            if ('function' === typeof gtag) {
                var form = document.querySelector('form.give-form')
                var form_id = form.querySelector('input[name="give-form-id"]').value;
                var form_title = form.querySelector('input[name="give-form-title"]').value;
                var decimal_separator = Give.form.fn.getInfo('decimal_separator', form);
                var currency_code = form.getAttribute('data-currency_code');
                var default_donation_amount = Give.fn.unFormatCurrency(
                    form.querySelector('.give-final-total-amount').getAttribute('data-total'),
                    decimal_separator
                );

                gtag('event', 'view_item', {
                    currency: currency_code,
                    value: default_donation_amount,
                    items: [
                        {
                            item_id: form_id,
                            item_name: form_title,
                            item_brand: 'Fundraising',
                            affiliation: '<?php echo esc_js(
                                $this->settingRepository->getTrackAffiliation()
                            )?>',
                            item_category: '<?php echo esc_js(
                                $this->settingRepository->getTrackCategory()
                            )?>',
                            item_list_name: '<?php echo esc_js(
                                $this->settingRepository->getTrackListName()
                            )?>',
                        }
                    ]
                });

                jQuery(form).on('submit', function (event) {
                    var form = event.target;
                    var form_gateway = form.querySelector('input[name="give-gateway"]').value;
                    var decimal_separator = Give.form.fn.getInfo('decimal_separator', form);
                    var currency_code = form.getAttribute('data-currency_code');
                    var donation_amount = Give.fn.unFormatCurrency(
                        form.querySelector('.give-final-total-amount').getAttribute('data-total'),
                        decimal_separator
                    );
                    var isRecurring = '1' === jQuery(form).find( 'input[name="_give_is_donation_recurring"]' ).val()

                    gtag('event', 'begin_checkout', {
                        currency: currency_code,
                        value: donation_amount,
                        items: [
                            {
                                item_id: form_id,
                                item_name: form_title,
                                item_brand: 'Fundraising',
                                affiliation: '<?php echo esc_js(
                                    $this->settingRepository->getTrackAffiliation()
                                )?>',
                                item_category: '<?php echo esc_js(
                                    $this->settingRepository->getTrackCategory()
                                )?>',
                                item_category2: form_gateway,
                                item_category3: isRecurring ? 'Subscription' : 'One-Time',
                                item_list_name: '<?php echo esc_js(
                                    $this->settingRepository->getTrackListName()
                                )?>',
                                price: donation_amount,
                                quantity: 1
                            }
                        ]
                    });
                });
            }
        </script>
        <?php
    }

    /**
     * @unreleased
     */
    public function recordPageViewInGoogleAnalyticsWithGA4(){
        if (!$this->canPrintScript()) {
            return;
        }

        $tracking_id = $this->settingRepository->getGoogleAnalytics4WebStreamMeasurementId();
        $encoded_tracking_id = base64_encode($tracking_id);

        $script = "
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '$encoded_tracking_id');
        gtag('event', 'page_view', {
            'page_path': window.parent.location.pathname,
            'page_title': window.parent.document.title
        });
    ";
        wp_enqueue_script(
            'google-analytics',
            'https://www.googletagmanager.com/gtag/js?id=' . $encoded_tracking_id,
            [],
            null,
            false
        );

        wp_add_inline_script('google-analytics', $script);
    }

    /**
     * @since 2.0.0
     */
    private function canPrintScript(): bool
    {
        // Don't track site admins
        if (is_user_logged_in() && current_user_can('administrator')) {
            return false;
        }

        if (!$this->settingRepository->canSendEvent(TrackingMode::GOOGLE_ANALYTICS_4)) {
            return false;
        }

        // Not needed on the success page.
        if (give_is_success_page()) {
            return false;
        }

        return true;
    }
}
