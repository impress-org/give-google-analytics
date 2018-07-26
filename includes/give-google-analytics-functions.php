<?php
/**
 * Works the magic.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper function to check conditions for triggering GA tracking code.
 *
 * @since 1.1
 *
 * @param $payment_id
 *
 * @return bool
 */
function give_should_send_beacon( $payment_id ) {

	$sent_already = get_post_meta( $payment_id, '_give_ga_beacon_sent', true );

	// Check meta beacon flag.
	if ( ! empty( $sent_already ) ) {
		return false;
	}

	// Don't track site admins.
	if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
		return false;
	}

	// Must be publish status.
	if ( 'publish' !== give_get_payment_status( $payment_id ) ) {
		return false;
	}

	// Don't continue if test mode is enabled and test mode tracking is disabled.
	if ( give_is_test_mode() && ! give_google_analytics_track_testing() ) {
		return false;
	}

	// Passed conditions so return true.
	return apply_filters( 'give_should_send_beacon', true, $payment_id );
}


/**
 * Should track testing?
 *
 * @return bool
 */
function give_google_analytics_track_testing() {
	if ( give_is_setting_enabled( give_get_option( 'google_analytics_test_option' ) ) ) {
		return true;
	}

	return false;
}


/**
 * Generate a unique user ID for GA.
 *
 * @return string
 */
function give_analytics_gen_uuid() {
	return sprintf(
		'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		// 32 bits for "time_low"
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		// 16 bits for "time_mid"
		mt_rand( 0, 0xffff ),
		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand( 0, 0x0fff ) | 0x4000,
		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand( 0, 0x3fff ) | 0x8000,
		// 48 bits for "node"
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	);
}


/**
 * Check if tracking is exist or not
 *
 * @since 2.0.0
 * @return bool
 */
function give_ga_has_tracking_id() {
	$tracking_id = give_get_option( 'google_analytics_ua_code', false );

	return ! empty( $tracking_id );
}


/**
 * Check if plugin can send google vent or not.
 *
 * @since 2.0.0
 *
 * @return bool
 */
function give_ga_can_send_event() {
	// Don't continue if test mode is enabled and test mode tracking is disabled.
	if ( give_is_test_mode() && ! give_google_analytics_track_testing() ) {
		return false;
	}

	// Must contain non empty tracking id
	if ( ! give_ga_has_tracking_id() ) {
		return false;
	}

	return true;
}
