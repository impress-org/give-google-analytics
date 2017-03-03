<?php
/**
 * Works the magic.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Send the GA data.
 *
 * @param $payment
 * @param $give_receipt_args
 */
function give_google_analytics_send_data( $payment, $give_receipt_args ) {

	if ( $give_receipt_args['payment_id'] ) {

		// Use a meta value so we only send the beacon once.
		if ( get_post_meta( $payment->ID, 'give_ga_beacon_sent', true ) ) {
			return;
		}

		$total = give_get_payment_amount( $payment->ID );
		$meta  = give_get_payment_meta( $payment->ID );
		$id    = give_get_payment_number( $payment->ID );

		?>
        <script type="text/javascript">

			ga('require', 'ecommerce', 'ecommerce.js');

			ga('ecommerce:addTransaction', {
				'id': '<?php echo esc_js( $id ); ?>', // Transaction ID. Required.
				'affiliation': '<?php echo esc_js( get_bloginfo( 'name' ) ); ?>', // Affiliation or store name.
				'revenue': '<?php echo esc_js( $total ); ?>' // donation amount.
			});

			ga('ecommerce:addItem', {
				'id': '<?php echo esc_js( $id ); ?>',
				'name': '<?php echo give_get_payment_form_title( $meta ); ?>',

			});
			ga('ecommerce:send');

			<?php // TODO: add conditional check for give category and add it as a 'category' key to the addItem command ?>

        </script>
		<?php
		// Add Payment note.
		give_insert_payment_note( $payment->ID, __( 'Google Analytics Ecommerce tac' ) );
		update_post_meta( $payment->ID, 'give_ga_beacon_sent', true );

	}
}

add_action( 'give_payment_receipt_after_table', 'give_google_analytics_send_data', 10, 2 );


/**
 * Check if GA is activated and ready.
 *
 * @return bool|mixed
 */
function give_google_analytics_check() {

	$setup_option = give_get_option( 'give_google_analytics_not_setup' );

	if ( empty( $setup_option ) ) {
		// Properly setup, return true.
		return true;
	}
	?>
    <script>
        alert('here');
		if ('undefined' === typeof window.ga) {
			// analytics does not exist.
			<?php give_update_option( 'give_google_analytics_not_setup', '1' ); ?>
		} else {
			// analytics does exist.
			<?php give_delete_option( 'give_google_analytics_not_setup' ); ?>
		}
    </script>
	<?php
	// Not properly setup, return false.
	return false;

}

add_action( 'wp_print_footer_scripts', 'give_google_analytics_check', 9999 );


/**
 *
 */
function give_google_analytics_maybe_show_notice() {

    echo "<pre>";
    var_dump(give_google_analytics_check());
    echo "</pre>";

    // If check returns false, not correctly setup.
	if ( ! give_google_analytics_check() ) {

	}

}

add_action( 'admin_notices', 'give_google_analytics_maybe_show_notice' );