<?php
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
function give_google_analytics_send_donation_success( $donation_id, $new_status, $old_status ) {
	if ( ! give_ga_can_send_event() ) {
		return false;
	}

	// Check conditions.
	$sent_already = get_post_meta( $donation_id, '_give_ga_beacon_sent', true );

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
		$total      = give_donation_amount(
			$donation_id, array(
				'currency' => false,
				'amount'   => array(
					'decimal' => true,
				),
			)
		);

		$campaign        = give_get_meta( $donation_id, '_give_ga_campaign', true );
		$campaign_source = give_get_meta( $donation_id, '_give_ga_campaign_source', true );
		$cpmpaign_medium = give_get_meta( $donation_id, '_give_ga_campaign_medium', true );

		$affiliation = give_get_option( 'google_analytics_affiliate' );

		// Add the categories.
		$ga_categories = give_get_option( 'google_analytics_category', 'Donations' );
		$ga_list       = give_get_option( 'google_analytics_list' );

		$args = apply_filters(
			'give_google_analytics_record_offsite_payment_hit_args', array(
				'v'     => 1,
				'tid'   => $ua_code, // Tracking ID required.
				'cid'   => ! empty( $client_id ) ? $client_id : give_analytics_gen_uuid(), // Client ID. Required (Set random if does not find client id ).
				't'     => 'event', // Event hit type.
				'ec'    => 'Fundraising', // Event Category. Required.
				'ea'    => 'Donation Success', // Event Action. Required.
				'el'    => $form_title, // Event Label.
				'ti'    => $donation_id, // Transaction ID.
				'ta'    => $affiliation,  // Affiliation.
				'cn'    => $campaign,  // Campaign Name.
				'cs'    => $campaign_source,  // Campaign Source.
				'cm'    => $cpmpaign_medium,  // Campaign Medium.
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
			)
		);

		$args    = array_map( 'rawurlencode', $args );
		$url     = add_query_arg( $args, 'https://www.google-analytics.com/collect' );
		$request = wp_remote_post( $url );

		// Check if beacon sent successfully.
		if ( ! is_wp_error( $request ) || 200 === wp_remote_retrieve_response_code( $request ) ) {

			add_post_meta( $donation_id, '_give_ga_beacon_sent', true );
			give_insert_payment_note( $donation_id, __( 'Google Analytics ecommerce tracking beacon sent.', 'give-google-analytics' ) );
		}
	} // End if().

}

add_action( 'give_update_payment_status', 'give_google_analytics_send_donation_success', 110, 3 );


/**
 * Save google analytic session data
 *
 * @since 1.2.0
 *
 * @param int $payment_id Donation ID.
 */
function give_ga_preserve_google_session_data( $payment_id ) {
	// Save client session id
	if (
		isset( $_COOKIE['_ga'] )
		&& give_ga_can_send_event()
	) {
		$client_id = explode( '.', $_COOKIE['_ga'], 3 );
		$client_id = array_pop( $client_id );

		add_post_meta( $payment_id, '_give_ga_client_id', $client_id );

		$campaign        = empty( $_COOKIE['give_campaign'] ) ? 'undefined' : $_COOKIE['give_campaign'];
		$campaign_source = empty( $_COOKIE['give_source'] ) ? 'undefined' : $_COOKIE['give_source'];
		$campaign_medium = empty( $_COOKIE['give_medium'] ) ? 'undefined' : $_COOKIE['give_medium'];

		add_post_meta( $payment_id, '_give_ga_campaign', $campaign );
		add_post_meta( $payment_id, '_give_ga_campaign_source', $campaign_source );
		add_post_meta( $payment_id, '_give_ga_campaign_medium', $campaign_medium );
	}
}

add_action( 'give_insert_payment', 'give_ga_preserve_google_session_data' );


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

	// Bailout.
	if ( 'refunded' === $new_status || 'publish' === $old_status ) {
		// Check if the beacon has already been sent.
		$beacon_sent = get_post_meta( $donation_id, '_give_ga_refund_beacon_sent', true );

		if ( ! empty( $beacon_sent ) ) {
			return false;
		}

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

			add_post_meta( $donation_id, '_give_ga_refund_beacon_sent', true );

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

				// If ga function is ready. Let's proceed.
				if ('function' === typeof ga) {

					// Save campaign source for donation completion page.
					// It's sent serverside via stored cookie.
					ga(function (tracker) {
						var campaignSource = tracker.get('campaignSource');
						document.cookie = 'give_source=' + campaignSource;

						var campaignMedium = tracker.get('campaignMedium');
						document.cookie = 'give_medium=' + campaignMedium;
					});

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
				;
			}

		})(jQuery); //
	</script>
	<?php

}

add_action( 'wp_footer', 'give_google_analytics_donation_form', 99999 );
