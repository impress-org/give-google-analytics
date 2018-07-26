<?php
/**
 * Give Google Analytics Ecommerce Tracking Activation.a
 *
 * @package     Give
 * @copyright   Copyright (c) 2016, WordImpress
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Give Google Analytics Ecommerce Tracking Activation Banner
 *
 * Includes and initializes Give activation banner class.
 *
 * @since 1.0
 */
function give_google_analytics_activation_banner() {

	// Check for activation banner inclusion.
	if ( ! class_exists( 'Give_Addon_Activation_Banner' )
		 && file_exists( GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php' )
	) {

		include GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php';
	}

	// Initialize activation welcome banner.
	if ( class_exists( 'Give_Addon_Activation_Banner' ) ) {

		// Only runs on admin
		$args = array(
			'file'              => __FILE__,
			'name'              => esc_html__( 'Google Analytics', 'give-google-analytics' ),
			'version'           => GIVE_GOOGLE_ANALYTICS_VERSION,
			'settings_url'      => admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=general&section=google-analytics' ),
			'documentation_url' => 'https://givewp.com/documentation/add-ons/google-analytics/',
			'support_url'       => 'https://givewp.com/support/',
			'testing'           => false, // Never leave as TRUE!
		);

		new Give_Addon_Activation_Banner( $args );

	}
	return false;

}

add_action( 'admin_init', 'give_google_analytics_activation_banner' );


/**
 * Plugins row action links
 *
 * @since 1.0
 *
 * @param array $actions An array of plugin action links.
 *
 * @return array An array of updated action links.
 */
function give_google_analytics_ecommerce_tracking_plugin_action_links( $actions ) {
	$new_actions = array(
		'settings' => sprintf(
			'<a href="%1$s">%2$s</a>',
			admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=general&section=google-analytics' ),
			esc_html__( 'Settings', 'give-google-analytics' )
		),
	);

	return array_merge( $new_actions, $actions );
}

add_filter( 'plugin_action_links_' . GIVE_GOOGLE_ANALYTICS_BASENAME, 'give_google_analytics_ecommerce_tracking_plugin_action_links' );


/**
 * Plugin row meta links
 *
 * @since 1.0
 *
 * @param array  $plugin_meta An array of the plugin's metadata.
 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
 *
 * @return array
 */
function give_google_analytics_ecommerce_tracking_plugin_row_meta( $plugin_meta, $plugin_file ) {
	if ( $plugin_file != GIVE_GOOGLE_ANALYTICS_BASENAME ) {
		return $plugin_meta;
	}

	$new_meta_links = array(
		sprintf(
			'<a href="%1$s" target="_blank">%2$s</a>',
			esc_url(
				add_query_arg(
					array(
						'utm_source'   => 'plugins-page',
						'utm_medium'   => 'plugin-row',
						'utm_campaign' => 'admin',
					), 'https://givewp.com/documentation/add-ons/google-analytics-ecommerce-tracking/'
				)
			),
			esc_html__( 'Documentation', 'give-google-analytics' )
		),
		sprintf(
			'<a href="%1$s" target="_blank">%2$s</a>',
			esc_url(
				add_query_arg(
					array(
						'utm_source'   => 'plugins-page',
						'utm_medium'   => 'plugin-row',
						'utm_campaign' => 'admin',
					), 'https://givewp.com/addons/'
				)
			),
			esc_html__( 'Add-ons', 'give-google-analytics' )
		),
	);

	return array_merge( $plugin_meta, $new_meta_links );
}

add_filter( 'plugin_row_meta', 'give_google_analytics_ecommerce_tracking_plugin_row_meta', 10, 2 );
