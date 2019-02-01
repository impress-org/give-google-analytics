<?php
/**
 * GA Refund tracking.
 *
 * @param $do_change
 * @param $donation_id
 * @param $new_status
 * @param $old_status
 *
 * @return mixed
 */
function give_google_analytics_refund_tracking( $do_change, $donation_id, $new_status, $old_status ) {

	// Bailout.
	if ( 'refunded' !== $new_status ) {
		return $do_change;
	}

	// Check if refund tracking is enabled. If not, return original change.
	if ( ! give_is_setting_enabled( give_get_option( 'google_analytics_refunds_option' ) ) ) {
		return $do_change;
	}

	// Only send refund if initial donation was sent to GA.
	// For instance, a donation may have been added manually.
	$beacon_sent = give_get_meta( $donation_id, '_give_ga_beacon_sent', true );
	if ( empty( $beacon_sent ) ) {
		return $do_change;
	}

	// Check for UA code.
	$ua_code = give_get_option( 'google_analytics_ua_code' );
	if ( empty( $ua_code ) ) {
		give_insert_payment_note( $donation_id, __( 'Google Analytics refund tracking beacon could not send because the UA code is missing in Give\'s settings', 'give-google-analytics' ) );

		return $do_change;
	}

	// Important to always return.
	return apply_filters( 'give_google_analytics_refund_tracking_beacon', $do_change, $donation_id );

}

add_filter( 'give_should_update_payment_status', 'give_google_analytics_refund_tracking', 10, 4 );
