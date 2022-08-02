<?php

namespace GiveGoogleAnalytics\Donations;

use GiveGoogleAnalytic\Addon\Repositories\SettingRepository;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;

/**
 * @unreleased
 */
class RecordGoogleEventWithGA4OnFrontend
{
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @unreleased
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function __invoke()
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

        // Add the categories.
        $ga_category = give_get_option('google_analytics_category') ?? 'Donations';
        $ga_list = give_get_option('google_analytics_list');

        ?>
        <script type="text/javascript">

            // GA Enhance Ecommerce tracking.
            (function ($) {

                window.addEventListener('load', function give_ga_purchase(event) {

                    window.removeEventListener('load', give_ga_purchase, false);

                    var gtag = window[window['GoogleAnalyticsObject'] || 'gtag'];

                    document.cookie = 'give_source=' + get_parameter('utm_source');
                    document.cookie = 'give_medium=' + get_parameter('utm_medium');
                    document.cookie = 'give_campaign=' + get_parameter('utm_campaign');
                    document.cookie = 'give_content=' + get_parameter('utm_content');

                    // If gtag function is ready. Let's proceed.
                    if ('function' === typeof gtag) {
                        var give_forms = $('form.give-form');

                        // Loop through each form on page and provide an impression.
                        give_forms.each(function (index, value) {

                            var form_id = $(this).find('input[name="give-form-id"]').val();
                            var form_title = $(this).find('input[name="give-form-title"]').val();
                            var decimal_separator = Give.form.fn.getInfo('decimal_separator', $(this).get(0));
                            var currency_code = $(this).attr('data-currency_code');
                            var default_donation_amount = Give.fn.unFormatCurrency(
                                $(this).find('.give-amount-hidden').val(),
                                decimal_separator
                            );

                            gtag('event', 'view_item', {
                                currency: currency_code,
                                value: default_donation_amount,
                                items: [
                                    {
                                        item_id: form_id,
                                        item_name: form_title,
                                        affiliation: <?php echo esc_js(
                                            $this->settingRepository->getTrackAffiliation()
                                        )?>,
                                        item_category: <?php echo esc_js(
                                            $this->settingRepository->getTrackCategory()
                                        )?>,
                                        item_category2: 'Fundraising',
                                        item_list_name: <?php echo esc_js(
                                            $this->settingRepository->getTrackListName()
                                        )?>,
                                    }
                                ]
                            });
                        });

                        // More code using $ as alias to jQuery
                        give_forms.on('submit', function (event) {

                            var form_id = $(this).find('input[name="give-form-id"]').val();
                            var form_title = $(this).find('input[name="give-form-title"]').val();
                            var form_gateway = $(this).find('input[name="give-gateway"]').val();
                            var currency_code = $(this).attr('data-currency_code');
                            var donation_amount = Give.fn.unFormatCurrency(
                                $(this).get(0).find('.give-final-total-amount').attr('data-total'),
                                decimal_separator
                            );

                            gtag('event', 'begin_checkout', {
                                currency: currency_code,
                                value: donation_amount,
                                items: [
                                    {
                                        item_id: form_id,
                                        item_name: form_title,
                                        affiliation: <?php echo esc_js(
                                            $this->settingRepository->getTrackAffiliation()
                                        )?>,
                                        item_category: <?php echo esc_js(
                                            $this->settingRepository->getTrackCategory()
                                        )?>,
                                        item_category2: 'Fundraising',
                                        item_category3: form_gateway,
                                        item_list_name: <?php echo esc_js(
                                            $this->settingRepository->getTrackListName()
                                        )?>,
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
}
