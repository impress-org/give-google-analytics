<?php
/**
 * Works the magic.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
function give_google_analytics_donation_form() {

	// Don't track site admins
	if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
		return false;
	}

	// Don't continue if test mode is enabled and test mode tracking is disabled.
	if ( give_is_test_mode() && ! give_google_analytics_track_testing() ) {
		return false;
	}

	// Add the categories.
	$ga_categories = give_get_option( 'google_analytics_category' );
	$ga_categories = ! empty( $ga_categories ) ? $ga_categories : 'Donations';
	$ga_list       = give_get_option( 'google_analytics_list' );
	?>
	<script type="text/javascript">

		// Give - Google Analytics Enhanced Ecommerce tracking script.
		jQuery.noConflict();
		(function ($) {

			window.addEventListener("load", function give_ga_purchase(event) {

				window.removeEventListener("load", give_ga_purchase, false);

				var ga = window[window['GoogleAnalyticsObject'] || 'ga'];

				// If ga function is ready. Let's proceed.
				if ('function' === typeof ga) {

					var give_forms = $('form.give-form');

					// Loop through each form on page and provide an impression.
					give_forms.each(function (index, value) {

						var form_id = $(this).find('input[name="give-form-id"]').val();
						var form_title = $(this).find('input[name="give-form-title"]').val();

						ga('ec:addImpression', {            // Provide product details in an impressionFieldObject.
							'id': form_id,                   // Product ID (string).
							'name': form_title,
							'category': '<?php echo esc_js( $ga_categories ); ?>',
							'list': '<?php echo ! empty( $ga_list ) ? esc_js( $ga_list ) : 'Donation Forms'; ?>',
							'position': index + 1                     // Product position (number).
						});

						ga('ec:setAction', 'detail');

						ga('send', 'event');

					});

					// More code using $ as alias to jQuery
					give_forms.on('submit', function (event) {

						var ga = window[window['GoogleAnalyticsObject'] || 'ga'];

						// If ga function is ready. Let's proceed.
						if ('function' === typeof ga) {

							var form_id = $(this).find('input[name="give-form-id"]').val();
							var form_title = $(this).find('input[name="give-form-title"]').val();
							var form_gateway = $(this).find('input[name="give-gateway"]').val();

							// Load the Ecommerce plugin.
							ga('require', 'ec');

							ga('ec:addProduct', {
								'id': form_id,
								'name': form_title,
								'category': '<?php echo esc_js( $ga_categories ); ?>',
								'price': $(this).find('.give-amount-hidden').val(),
								'quantity': 1
							});

							ga('ec:setAction', 'checkout', {
								'option': form_gateway  // Payment method
							});

							ga('send', 'event');

						}
					});

				} // end if

			}, false); // end win load

		})(jQuery); //
	</script>
	<?php

}

add_action( 'wp_footer', 'give_google_analytics_donation_form', 99999 );

/**
 * Donation success page: Send the GA data.
 *
 * @see: https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce
 *
 * @param $payment
 * @param $give_receipt_args
 *
 * @return bool
 */
function give_google_analytics_completed_donation( $payment, $give_receipt_args ) {

	// Need Payment ID to continue.
	if ( empty( $payment->ID ) ) {
		return false;
	}

	// Use a meta value so we only send the beacon once.
	if ( get_post_meta( $payment->ID, '_give_ga_beacon_sent', true ) ) {
		return false;
	}

	// Don't track site admins.
	if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
		return false;
	}

	// Don't continue if test mode is enabled and test mode tracking is disabled.
	if ( give_is_test_mode() && ! give_google_analytics_track_testing() ) {
		return false;
	}

	$form_id     = give_get_payment_form_id( $payment->ID );
	$total       = give_get_payment_amount( $payment->ID );
	$affiliation = give_get_option( 'google_analytics_affiliate' );

	// Add the categories.
	$ga_categories = give_get_option( 'google_analytics_category' );
	$ga_categories = ! empty( $ga_categories ) ? $ga_categories : 'Donations';
	$ga_list       = give_get_option( 'google_analytics_list' );
	?>
	<script type="text/javascript">
		window.addEventListener("load", function give_ga_purchase(event) {

			window.removeEventListener("load", give_ga_purchase, false);

			var ga = window[window['GoogleAnalyticsObject'] || 'ga'];

			// If ga function is ready. Let's proceed.
			if ('function' === typeof ga) {

				// Load the Ecommerce plugin.
				ga('require', 'ec');

				ga('ec:addProduct', {
					'id': '<?php echo esc_js( $form_id ); ?>',
					'name': '<?php echo esc_js( html_entity_decode( get_the_title( $form_id ) ) ); ?>',
					'category': '<?php echo esc_js( $ga_categories ); ?>',
					'price': '<?php echo esc_js( $total ); ?>',
					'quantity': 1
				});

				ga('ec:setAction', 'purchase', {
					'id': '<?php echo esc_js( $payment->ID ); ?>',
					'affiliation': '<?php echo ! empty( $affiliation ) ? esc_js( $affiliation ) : esc_js( get_bloginfo( 'name' ) ); ?>',
					'category': '<?php echo esc_js( $ga_categories ); ?>',
					'revenue': '<?php echo esc_js( $total ); ?>', // Donation amount.
					'list': '<?php echo ! empty( $ga_list ) ? esc_js( $ga_list ) : 'Donation Forms'; ?>'
				});

				ga('send', 'event');
			}

		}, false);

	</script>
	<?php
	// Add Payment note.
	give_insert_payment_note( $payment->ID, __( 'Google Analytics ecommerce tracking beacon sent.', 'give-google-analytics' ) );
	update_post_meta( $payment->ID, '_give_ga_beacon_sent', true );

}

add_action( 'give_payment_receipt_after_table', 'give_google_analytics_completed_donation', 10, 2 );


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

	// All is well, sent beacon.
	give_insert_payment_note( $donation_id, __( 'Google Analytics donation refund tracking beacon sent.', 'give-google-analytics' ) );

	// Important to always return.
	return $do_change;

}


add_filter( 'give_should_update_payment_status', 'give_google_analytics_refund_tracking', 10, 4 );


/**
 * @param $donation_id
 *
 * @return bool
 */
function give_google_analytics_send_refund_beacon( $donation_id ) {

	$donation = new Give_Payment( $donation_id );
	$status = give_get_payment_status( $donation );

	// Bailout.
	if ( 'refunded' !== $status ) {
		return false;
	}
	// Check for UA code.
	$ua_code = give_get_option( 'google_analytics_ua_code' );
	if ( empty( $ua_code ) ) {
		return false;
	}

	// Check if the beacon has already been sent.
	$beacon_sent = get_post_meta( $donation_id, '_give_ga_refund_beacon_sent' );
	if ( ! empty( $beacon_sent ) ) {
		return false;
	}
	?>
	<script>
		(function (i, s, o, g, r, a, m) {
			i['GoogleAnalyticsObject'] = r;
			i[r] = i[r] || function () {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
			a = s.createElement(o),
				m = s.getElementsByTagName(o)[0];
			a.async = 1;
			a.src = g;
			m.parentNode.insertBefore(a, m)
		})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

		ga('create', '<?php echo $ua_code; ?>', 'auto');

		ga('require', 'ec');

		// Refund an entire transaction.
		ga('ec:setAction', 'refund', {
			'id': '<?php echo $donation_id; ?>',
		});

		ga('send', 'event');
	
	</script> <?php

	update_post_meta( $donation_id, '_give_ga_refund_beacon_sent', 'true' );
}

add_action( 'give_view_order_details_after', 'give_google_analytics_send_refund_beacon', 10, 1 );


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
