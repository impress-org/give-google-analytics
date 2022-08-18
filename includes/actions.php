<?php

use Give\Donations\ValueObjects\DonationStatus;
use GiveGoogleAnalytics\Donations\Repositories\DonationRepository;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

/**
 * Triggers when a payment is updated from pending to complete.
 *
 * Support on-site and offsite gateways. Since donors often don't return from offsite gateways we need to watch for
 * payments updating from "pending" to "completed" statuses. When it does we then check the date of the donation and if
 * a beacon has been sent along with other checks before sending.
 *
 * Uses the Measurement Protocol within GA's API
 * https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
 *
 * @since 1.1
 *
 * @param string $donation_id The donation payment ID.
 * @param        $new_status
 * @param        $old_status
 *
 * @return string|bool
 */
function give_google_analytics_send_donation_success($donation_id, $new_status, $old_status)
{
    if (
        DonationStatus::COMPLETE !== $new_status ||
        !give(SettingRepository::class)->canSendEvent(TrackingMode::UNIVERSAL_ANALYTICS) ||
        give(DonationRepository::class)->isGoogleAnalyticEventSent($donation_id)
    ) {
        return false;
    }

    // Going from "pending" to "Publish" -> like PayPal Standard when receiving a successful payment IPN.

    $ua_code = give_get_option('google_analytics_ua_code');

    // Set vars.
    $form_id = give_get_payment_form_id($donation_id);
    $client_id = give_get_meta($donation_id, '_give_ga_client_id', true);
    $form_title = get_the_title($form_id);
    $total = give_donation_amount($donation_id, false);

    $campaign = give_get_meta($donation_id, '_give_ga_campaign', true);
    $campaign_source = give_get_meta($donation_id, '_give_ga_campaign_source', true);
    $campaign_medium = give_get_meta($donation_id, '_give_ga_campaign_medium', true);

    // utm_content
    $campaign_content = give_get_meta($donation_id, '_give_ga_campaign_content', true);

    $affiliation = give_get_option('google_analytics_affiliate');

    // Add the categories.
    $ga_categories = give_get_option('google_analytics_category', 'Donations');
    $ga_list = give_get_option('google_analytics_list');

    $args = [
        'v' => 1,
        'tid' => $ua_code,
        // Tracking ID required.
        'cid' => !empty($client_id) ? $client_id : give_analytics_gen_uuid(),
        // Client ID. Required (Set random if does not find client id ).
        't' => 'event',
        // Event hit type.
        'ec' => 'Fundraising',
        // Event Category. Required.
        'ea' => 'Donation Success',
        // Event Action. Required.
        'el' => $form_title,
        // Event Label.
        'ti' => $donation_id,
        // Transaction ID.
        'ta' => $affiliation,
        // Affiliation.
        'cu' => give_get_payment_currency_code($donation_id),
        // Currency code.
        'pal' => $ga_list,
        // Product Action List.
        'pa' => 'purchase',
        'pr1id' => $form_id,
        // Product 1 ID. Either ID or name must be set.
        'pr1nm' => $form_title,
        // Product 1 name. Either ID or name must be set.
        'pr1ca' => $ga_categories,
        // Product 1 category.
        'pr1br' => 'Fundraising',
        'pr1qt' => 1,
        // Product 1 quantity.
        'pr1pr' => $total,
        // Product price
        'tr' => $total,
        // Transaction Revenue
    ];

    // Campaign Name.
    if ($campaign) {
        $args['cn'] = $campaign;
    }

    // Campaign Source.
    if ($campaign_source) {
        $args['cs'] = $campaign_source;
    }

    // Campaign Medium
    if ($campaign_medium) {
        $args['cm'] = $campaign_medium;
    }

    // utm_content
    if ($campaign_content) {
        $args['cc'] = $campaign_content;
    }

    /**
     * Filter the google analytics query params.
     *
     * @since 1.0.0
     */
    $args = apply_filters('give_google_analytics_record_offsite_payment_hit_args', $args);

    $args = array_map('rawurlencode', $args);
    $url = add_query_arg($args, 'https://www.google-analytics.com/collect');
    $request = wp_remote_post($url);

    // Check if beacon sent successfully.
    if (!is_wp_error($request) || 200 == wp_remote_retrieve_response_code($request)) {
        give_update_payment_meta($donation_id, '_give_ga_beacon_sent', true);
        give_insert_payment_note(
            $donation_id,
            __('Google Analytics ecommerce tracking beacon sent.', 'give-google-analytics')
        );
    }
}

add_action('give_update_payment_status', 'give_google_analytics_send_donation_success', 110, 3);

/**
 * Track refund donations within GA.
 *
 * @param int $donation_id
 * @param string $new_status
 * @param string $old_status
 *
 * @return bool
 */
function give_google_analytics_send_refund_beacon($donation_id, $new_status, $old_status)
{
    if (
        DonationStatus::REFUNDED !== $new_status ||
        !give(SettingRepository::class)->canSendEvent(TrackingMode::UNIVERSAL_ANALYTICS)
    ) {
        return false;
    }

    // Check if the DONATION beacon has been sent (successful "purchase").
    // If it hasn't then return false; only send refunds for donations tracked in GA.
    if (!give(DonationRepository::class)->isGoogleAnalyticEventSent($donation_id)) {
        return false;
    }

    // Bailout.
    $ua_code = give_get_option('google_analytics_ua_code');
    $client_id = give_get_meta($donation_id, '_give_ga_client_id', true);

    $args = [
        'v' => 1,
        'tid' => $ua_code,
        // Tracking ID required.
        'cid' => !empty($client_id) ? $client_id : give_analytics_gen_uuid(),
        // Client ID. Required (Set random if does not find client id ).
        't' => 'event',
        // Event hit type.
        'ec' => 'Fundraising',
        // Event Category. Required.
        'ea' => 'Donation Refund',
        // Event Action. Required.
        'ti' => $donation_id,
        // Transaction ID.
        'pa' => 'refund',
        'ni' => '1',
    ];

    $args = array_map('rawurlencode', $args);
    $url = add_query_arg($args, 'https://www.google-analytics.com/collect');
    $request = wp_remote_post($url);

    // Check if beacon sent successfully.
    if (!is_wp_error($request) || 200 == wp_remote_retrieve_response_code($request)) {
        give_update_payment_meta($donation_id, '_give_ga_refund_beacon_sent', true);

        // All is well, sent beacon.
        give_insert_payment_note(
            $donation_id,
            __('Google Analytics donation refund tracking beacon sent.', 'give-google-analytics')
        );
    }
}

add_action('give_update_payment_status', 'give_google_analytics_send_refund_beacon', 10, 3);


/**
 * Measuring a Donation button Click
 *
 * Called when the user begins the checkout process.
 *
 * @see http://stackoverflow.com/questions/25140579/tracking-catalog-product-impressions-enhanced-ecommerce-google-analytics
 * @see http://stackoverflow.com/questions/24482056/when-and-how-often-do-you-call-gasend-pageview-when-using-enhanced-ecomme
 *
 * @return bool
 */
function give_google_analytics_donation_form()
{
    // Don't track site admins
    if (is_user_logged_in() && current_user_can('administrator')) {
        return false;
    }

    if (!give(SettingRepository::class)->canSendEvent(TrackingMode::UNIVERSAL_ANALYTICS)) {
        return false;
    }

    // Not needed on the success page.
    if (give_is_success_page()) {
        return false;
    }

    // Add the categories.
    $ga_categories = give_get_option('google_analytics_category');
    $ga_categories = !empty($ga_categories) ? $ga_categories : 'Donations';
    $ga_list = give_get_option('google_analytics_list');

    ?>
    <script type="text/javascript">

        // GA Enhance Ecommerce tracking.
        (function ($) {

            window.addEventListener('load', function give_ga_purchase(event) {

                window.removeEventListener('load', give_ga_purchase, false);

                var ga = window[window['GoogleAnalyticsObject'] || 'ga'];

                document.cookie = 'give_source=' + get_parameter('utm_source');
                document.cookie = 'give_medium=' + get_parameter('utm_medium');
                document.cookie = 'give_campaign=' + get_parameter('utm_campaign');

                //utm_content
                document.cookie = 'give_content=' + get_parameter('utm_content');

                // If ga function is ready. Let's proceed.
                if ('function' === typeof ga) {
                    // Load the Ecommerce plugin.
                    ga('require', 'ec');

                    var give_forms = $('form.give-form');

                    // Loop through each form on page and provide an impression.
                    give_forms.each(function (index, value) {

                        var form_id = $(this).find('input[name="give-form-id"]').val();
                        var form_title = $(this).find('input[name="give-form-title"]').val();

                        ga('ec:addImpression', {            // Provide product details in an impressionFieldObject.
                            'id': form_id,                   // Product ID (string).
                            'name': form_title,
                            'category': '<?php echo esc_js($ga_categories); ?>',
                            'list': '<?php echo !empty($ga_list) ? esc_js($ga_list) : 'Donation Forms'; ?>',
                            'position': index + 1                     // Product position (number).
                        });

                        ga('ec:setAction', 'detail');

                        ga('send', 'event', 'Fundraising', 'Donation Form View', form_title, {'nonInteraction': 1});

                    });

                    // More code using $ as alias to jQuery
                    give_forms.on('submit', function (event) {

                        var form_id = $(this).find('input[name="give-form-id"]').val();
                        var form_title = $(this).find('input[name="give-form-title"]').val();
                        var form_gateway = $(this).find('input[name="give-gateway"]').val();

                        ga('ec:addProduct', {
                            'id': form_id,
                            'name': form_title,
                            'category': '<?php echo esc_js($ga_categories); ?>',
                            'brand': 'Fundraising',
                            'price': $(this).find('.give-amount-hidden').val(),
                            'quantity': 1
                        });
                        ga('ec:setAction', 'add');

                        ga('send', 'event', 'Fundraising', 'Donation Form Begin Checkout', form_title);

                        ga('ec:setAction', 'checkout', {
                            'option': form_gateway  // Payment method
                        });

                        ga('send', 'event', 'Fundraising', 'Donation Form Submitted', form_title);

                    });

                } // end if

            }, false); // end win load


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

add_action('wp_footer', 'give_google_analytics_donation_form', 99999);
add_action('give_embed_footer', 'give_google_analytics_donation_form', 99999);
