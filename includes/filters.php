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

	// Check if refund tracking is enabled.
	if ( ! give_is_setting_enabled( give_get_option( 'google_analytics_refunds_option' ) ) ) {
		return $do_change;
	}

	// Check for UA code.
	$ua_code = give_get_option( 'google_analytics_ua_code' );
	if ( empty( $ua_code ) ) {
		give_insert_payment_note( $donation_id, __( 'Google Analytics refund tracking beacon could not send because the UA code is missing in Give\'s settings', 'give-google-analytics' ) );

		return $do_change;
	}

	// Important to always return.
	return $do_change;

}

add_filter( 'give_should_update_payment_status', 'give_google_analytics_refund_tracking', 10, 4 );