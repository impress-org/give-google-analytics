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
 * @return bool
 */
function give_google_analytics_donation_form() {

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
				$('form.give-form').on('submit', function (event) {

					var ga = window[window['GoogleAnalyticsObject'] || 'ga'];

					// If ga function is ready. Let's proceed.
					if ('function' === typeof ga) {

						var form_id = $(this).find('input[name="give-form-id"]').val();
						var form_title = $(this).find('input[name="give-form-id"]').val();
						var form_gateway = $(this).find('input[name="give-gateway"]').val();

						// Load the Ecommerce plugin.
						ga('require', 'ec');

						ga('ec:addProduct', {
							'id': form_id,
							'name': form_title,
							<?php if ( ! empty( $ga_categories ) ) : ?>
							'category': '<?php echo esc_js( $ga_categories ); ?>',
							<?php endif; ?>
							'price': form_id.find('.give-amount-hidden').val(),
							'quantity': 1
						});

						ga('ec:setAction', 'checkout', {
							'option': form_gateway  // Payment method
						});

						ga('send', 'event');

					}
				});
			});
		})(jQuery);
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

	// Need GA code to continue.
	$ua_code = give_get_option( 'google_analytics_ua_code' );
	if ( empty( $ua_code ) ) {
		return $do_change;
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

		// Refund an entire transaction.
		ga('ec:setAction', 'refund', {
			'id': '<?php echo $donation_id; ?>',
		});

		ga('send', 'event');

    </script>

<?php }


add_filter( 'give_should_update_payment_status', 'give_google_analytics_refund_tracking', 10, 4 );