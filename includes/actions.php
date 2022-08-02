<?php
/**
 * Track refund donations within GA.
 *
 * @param int    $donation_id
 * @param string $new_status
 * @param string $old_status
 *
 * @return bool
 */
function give_google_analytics_send_refund_beacon( $donation_id, $new_status, $old_status ) {
	if ( ! give_ga_can_send_event() ) {
		return false;
	}

	// Check if the DONATION beacon has been sent (successful "purchase").
	// If it hasn't then return false; only send refunds for donations tracked in GA.
	$donation_beacon_sent = give_get_meta( $donation_id, '_give_ga_beacon_sent', true );
	if ( empty( $donation_beacon_sent ) ) {
		return false;
	}

	// Check if the REFUND beacon has already been sent.
	// If it hasn't proceed.
	$refund_beacon_sent = give_get_meta( $donation_id, '_give_ga_refund_beacon_sent', true );
	if ( ! empty( $refund_beacon_sent ) ) {
		return false;
	}

	// Bailout.
	if ( 'refunded' === $new_status || 'publish' === $old_status ) {

		$ua_code   = give_get_option( 'google_analytics_ua_code' );
		$client_id = give_get_meta( $donation_id, '_give_ga_client_id', true );

		$args = array(
			'v'   => 1,
			'tid' => $ua_code, // Tracking ID required.
			'cid' => ! empty( $client_id ) ? $client_id : give_analytics_gen_uuid(), // Client ID. Required (Set random if does not find client id ).
			't'   => 'event', // Event hit type.
			'ec'  => 'Fundraising', // Event Category. Required.
			'ea'  => 'Donation Refund', // Event Action. Required.
			'ti'  => $donation_id, // Transaction ID.
			'pa'  => 'refund',
			'ni'  => '1',
		);

		$args    = array_map( 'rawurlencode', $args );
		$url     = add_query_arg( $args, 'https://www.google-analytics.com/collect' );
		$request = wp_remote_post( $url );

		// Check if beacon sent successfully.
		if ( ! is_wp_error( $request ) || 200 == wp_remote_retrieve_response_code( $request ) ) {

			give_update_payment_meta( $donation_id, '_give_ga_refund_beacon_sent', true );

			// All is well, sent beacon.
			give_insert_payment_note( $donation_id, __( 'Google Analytics donation refund tracking beacon sent.', 'give-google-analytics' ) );
		}
	}
}

add_action( 'give_update_payment_status', 'give_google_analytics_send_refund_beacon', 10, 3 );


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

	if ( ! give_ga_can_send_event() ) {
		return false;
	}

	// Not needed on the success page.
	if ( give_is_success_page() ) {
		return false;
	}

	// Add the categories.
	$ga_categories = give_get_option( 'google_analytics_category' );
	$ga_categories = ! empty( $ga_categories ) ? $ga_categories : 'Donations';
	$ga_list       = give_get_option( 'google_analytics_list' );

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
							'category': '<?php echo esc_js( $ga_categories ); ?>',
							'list': '<?php echo ! empty( $ga_list ) ? esc_js( $ga_list ) : 'Donation Forms'; ?>',
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
							'category': '<?php echo esc_js( $ga_categories ); ?>',
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

add_action( 'wp_footer', 'give_google_analytics_donation_form', 99999 );
add_action( 'give_embed_footer', 'give_google_analytics_donation_form', 99999 );
