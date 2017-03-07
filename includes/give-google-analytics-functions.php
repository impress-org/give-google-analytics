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
 * @param $form_id
 * @param $args
 *
 * @return bool
 */
function give_google_analytics_donate_click( $form_id, $args ) {

	// Don't track site admins
	if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
		return false;
	}

	// Add the categories.
	$ga_categories = give_get_option( 'google_analytics_category' ); ?>

    <script type="text/javascript">
		//GA Enhance Ecommerce tracking.
		jQuery.noConflict();
		(function ($) {
			$(function () {

				// More code using $ as alias to jQuery
				$('#give-form-<?php echo $form_id; ?>').on('submit', function (event) {

					var ga = window[window['GoogleAnalyticsObject'] || 'ga'];

					// If ga function is ready. Let's proceed.
					if ('function' === typeof ga) {

						// Load the Ecommerce plugin.
						ga('require', 'ec');

						ga('ec:addProduct', {
							'id': '<?php echo esc_js( $form_id ); ?>',
							'name': '<?php echo esc_js( html_entity_decode( get_the_title( $form_id ) ) ); ?>',
							<?php if ( ! empty( $ga_categories ) ) : ?>
							'category': '<?php echo esc_js( $ga_categories ); ?>',
							<?php endif; ?>
							'price': $(this).find('.give-amount-hidden').val(),
							'quantity': 1
						});

						ga('ec:setAction', 'checkout', {
							'option': $(this).find('input[name="give-gateway"]').val()   // Payment method
						});

						ga('send', 'event');

					}
				});
			});
		})(jQuery);
    </script>
	<?php

}

add_action( 'wp_footer', 'give_google_analytics_donate_click', 10, 2 );

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
function give_google_analytics_send_data( $payment, $give_receipt_args ) {

	// Need Payment ID to continue.
	if ( empty( $payment->ID ) ) {
		return false;
	}

	// Use a meta value so we only send the beacon once.
	if ( get_post_meta( $payment->ID, 'give_ga_beacon_sent', true ) ) {
		return false;
	}

	// Don't track site admins
	if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
		return false;
	}

	$form_id = give_get_payment_form_id( $payment->ID );
	$total   = give_get_payment_amount( $payment->ID );
	// $meta        = give_get_payment_meta( $payment->ID );
	$id          = give_get_payment_number( $payment->ID );
	$affiliation = give_get_option( 'google_analytics_affiliate' );

	// Add the categories.
	$ga_categories = give_get_option( 'google_analytics_category' );

	$ga_list = give_get_option( 'google_analytics_list' );
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
					<?php if ( ! empty( $ga_categories ) ) : ?>
					'category': '<?php echo esc_js( $ga_categories ); ?>',
					<?php endif; ?>
					'price': '<?php echo esc_js( $total ); ?>',
					'quantity': 1
				});

				ga('ec:setAction', 'purchase', {
					'id': '<?php echo esc_js( $id ); ?>',
					'affiliation': '<?php echo ! empty( $affiliation ) ? esc_js( $affiliation ) : esc_js( get_bloginfo( 'name' ) ); ?>',
					<?php if ( ! empty( $ga_categories ) ) : ?>
					'category': '<?php echo esc_js( $ga_categories ); ?>',
					<?php endif; ?>
					'revenue': '<?php echo esc_js( $total ); ?>', // Donation amount.
					'list': '<?php echo ! empty( $ga_list ) ? esc_js( $ga_list ) : 'Donation Forms'; ?>'
				});

				ga('send', 'event');
			}

		}, false);

    </script>
	<?php
	// Add Payment note.
	give_insert_payment_note( $payment->ID, __( 'Google Analytics ecommerce tracking sent.' ) );
	update_post_meta( $payment->ID, 'give_ga_beacon_sent', true );

}

add_action( 'give_payment_receipt_after_table', 'give_google_analytics_send_data', 10, 2 );


/**
 * @param $do_change
 * @param $donation_id
 * @param $new_status
 * @param $old_status
 */
function give_google_analytics_refund_tracking( $do_change, $donation_id, $new_status, $old_status ) {

	$donation = new Give_Payment( $donation_id );

	// Bailout.
	if ( 'refunded' !== $new_status ) {
		return $do_change;
	} ?>

    <script>
		// Refund an entire transaction.
		ga('ec:setAction', 'refund', {
			'id': '<?php echo $donation_id; ?>',
		});
    </script>

<?php }


add_filter( 'give_should_update_payment_status', 'give_google_analytics_refund_tracking', 10, 4 );


/**
 * Check if GA is activated and ready.
 *
 * @return bool|mixed
 */
function give_google_analytics_check() {

	$setup_option = get_option( 'give_google_analytics_setup' );

	if ( ! empty( $setup_option ) ) {
		// Properly setup, return true.
		return true;
	}

	// Only output on frontend
	if ( is_user_logged_in() ) {
		return false;
	}
	?>
    <script>
		window.addEventListener("load", function give_ga_winload(event) {

			//remove listener, no longer needed.
			window.removeEventListener("load", give_ga_winload, false);

			// GA Check.
			var ga = window[window['GoogleAnalyticsObject'] || 'ga'];

			if ('function' !== typeof ga) {
				// analytics does not exist.
				<?php update_option( 'give_google_analytics_setup', 'not_setup' ); ?>
			} else {
				// analytics does exist.
				<?php delete_option( 'give_google_analytics_setup' ); ?>
			}

		}, false);
    </script>
	<?php

	// Not properly setup, return false.
	return false;

}

add_action( 'wp_footer', 'give_google_analytics_check', 9999 );


/**
 * Show notice if GA not tracking properly
 */
function give_google_analytics_maybe_show_notice() {

    
}

add_action( 'admin_notices', 'give_google_analytics_maybe_show_notice' );