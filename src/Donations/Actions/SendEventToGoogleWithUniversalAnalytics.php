<?php

namespace GiveGoogleAnalytics\Donations\Actions;

/**
 * @unreleased
 */
class SendEventToGoogleWithUniversalAnalytics
{
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
     * @param string     $donation_id The donation payment ID.
     * @param        $new_status
     * @param        $old_status
     *
     * @return string|bool
     */
    public function __invoke($donation_id, $new_status, $old_status)
    {
        if ( ! give_ga_can_send_event() ) {
            return false;
        }

        // Check conditions.
        $sent_already = give_get_meta( $donation_id, '_give_ga_beacon_sent', true );

        if ( ! empty( $sent_already ) ) {
            return false;
        }

        // Going from "pending" to "Publish" -> like PayPal Standard when receiving a successful payment IPN.
        if ( 'pending' === $old_status && 'publish' === $new_status ) {

            $ua_code = give_get_option( 'google_analytics_ua_code' );

            // Set vars.
            $form_id    = give_get_payment_form_id( $donation_id );
            $client_id  = give_get_meta( $donation_id, '_give_ga_client_id', true );
            $form_title = get_the_title( $form_id );
            $total      = give_donation_amount( $donation_id, false );

            $campaign        = give_get_meta( $donation_id, '_give_ga_campaign', true );
            $campaign_source = give_get_meta( $donation_id, '_give_ga_campaign_source', true );
            $campaign_medium = give_get_meta( $donation_id, '_give_ga_campaign_medium', true );

            // utm_content
            $campaign_content = give_get_meta( $donation_id, '_give_ga_campaign_content', true );

            $affiliation = give_get_option( 'google_analytics_affiliate' );

            // Add the categories.
            $ga_categories = give_get_option( 'google_analytics_category', 'Donations' );
            $ga_list       = give_get_option( 'google_analytics_list' );

            $args = array(
                'v'     => 1,
                'tid'   => $ua_code, // Tracking ID required.
                'cid'   => ! empty( $client_id ) ? $client_id : give_analytics_gen_uuid(), // Client ID. Required (Set random if does not find client id ).
                't'     => 'event', // Event hit type.
                'ec'    => 'Fundraising', // Event Category. Required.
                'ea'    => 'Donation Success', // Event Action. Required.
                'el'    => $form_title, // Event Label.
                'ti'    => $donation_id, // Transaction ID.
                'ta'    => $affiliation,  // Affiliation.
                'cu'    => give_get_payment_currency_code( $donation_id ),  // Currency code.
                'pal'   => $ga_list,   // Product Action List.
                'pa'    => 'purchase',
                'pr1id' => $form_id,  // Product 1 ID. Either ID or name must be set.
                'pr1nm' => $form_title, // Product 1 name. Either ID or name must be set.
                'pr1ca' => $ga_categories, // Product 1 category.
                'pr1br' => 'Fundraising',
                'pr1qt' => 1, // Product 1 quantity.
                'pr1pr' => $total, // Product price
                'tr'    => $total, // Transaction Revenue
            );

            // Campaign Name.
            if ( $campaign ) {
                $args['cn'] = $campaign;
            }

            // Campaign Source.
            if ( $campaign_source ) {
                $args['cs'] = $campaign_source;
            }

            // Campaign Medium
            if ( $campaign_medium ) {
                $args['cm'] = $campaign_medium;
            }

            // utm_content
            if ( $campaign_content ) {
                $args['cc'] = $campaign_content;
            }

            /**
             * Filter the google analytics query params.
             *
             * @since 1.0.0
             */
            $args = apply_filters( 'give_google_analytics_record_offsite_payment_hit_args', $args );

            $args    = array_map( 'rawurlencode', $args );
            $url     = add_query_arg( $args, 'https://www.google-analytics.com/collect' );
            $request = wp_remote_post( $url );

            // Check if beacon sent successfully.
            if ( ! is_wp_error( $request ) || 200 == wp_remote_retrieve_response_code( $request ) ) {

                give_update_payment_meta( $donation_id, '_give_ga_beacon_sent', true );
                give_insert_payment_note( $donation_id, __( 'Google Analytics ecommerce tracking beacon sent.', 'give-google-analytics' ) );
            }
        }// End if().
    }
}
