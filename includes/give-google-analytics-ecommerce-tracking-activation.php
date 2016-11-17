<?php
/**
 * Give Google Analytics Ecommerce Tracking Activation
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
function give_google_analytics_ecommerce_tracking_activation_banner() {

	// Check for if give plugin activate or not.
	$is_give_active = defined( 'GIVE_PLUGIN_BASENAME' ) ? is_plugin_active( GIVE_PLUGIN_BASENAME ) : false ;

	//Check to see if Give is activated, if it isn't deactivate and show a banner
	if ( is_admin() && current_user_can( 'activate_plugins' ) && ! $is_give_active ) {

		add_action( 'admin_notices', 'give_google_analytics_ecommerce_tracking_activation_notice' );

		//Don't let this plugin activate
		deactivate_plugins( plugin_basename( GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_BASENAME ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		return false;

	}

	//Check minimum Give version
	if ( defined( 'GIVE_VERSION' ) && version_compare( GIVE_VERSION, '1.6', '<' ) ) {

		add_action( 'admin_notices', 'give_google_analytics_ecommerce_tracking_min_version_notice' );

		//Don't let this plugin activate
		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		return false;

	}

	//Check for activation banner inclusion
	$activation_banner_file = GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php';
	if ( ! class_exists( 'Give_Addon_Activation_Banner' ) && file_exists( $activation_banner_file ) ) {
		include $activation_banner_file;
	}

	//Only runs on admin
	$args = array(
		'file'              => __FILE__,
		'name'              => esc_html__( 'Google Analytics Ecommerce Tracking', 'give-google-analytics-ecommerce-tracking' ),
		'version'           => GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_VERSION,
		'settings_url'      => admin_url( 'edit.php?post_type=give_forms&page=give-settings' ),
		'documentation_url' => 'https://givewp.com/documentation/add-ons/google-analytics-ecommerce-tracking/',
		'support_url'       => 'https://givewp.com/support/',
		'testing'           => false //Never leave as TRUE!
	);

	new Give_Addon_Activation_Banner( $args );

	return false;

}

add_action( 'admin_init', 'give_google_analytics_ecommerce_tracking_activation_banner' );

/**
 * Notice for No Core Activation
 *
 * @since 1.0
 */
function give_google_analytics_ecommerce_tracking_activation_notice() {
	echo '<div class="error"><p>' . __( '<strong>Activation Error:</strong> We noticed Give is not active. Please activate Give in order to use the Google Analytics Ecommerce Tracking Add-on.', 'give-google-analytics-ecommerce-tracking' ) . '</p></div>';
}

/**
 * Notice for No Core Activation
 *
 * @since 1.0
 */
function give_google_analytics_ecommerce_tracking_min_version_notice() {
	echo '<div class="error"><p>' . __( '<strong>Activation Error:</strong> We noticed Give is not up to date. Please update Give in order to use Google Analytics Ecommerce Tracking Add-on.', 'give-google-analytics-ecommerce-tracking' ) . '</p></div>';
}



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
			admin_url( 'edit.php?post_type=give_forms&page=give-settings' ),
			esc_html__( 'Settings', 'give-google-analytics-ecommerce-tracking' )
		),
	);

	return array_merge( $new_actions, $actions );
}

add_filter( 'plugin_action_links_' . GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_BASENAME, 'give_google_analytics_ecommerce_tracking_plugin_action_links' );


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
	if ( $plugin_file != GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_BASENAME ) {
		return $plugin_meta;
	}

	$new_meta_links = array(
		sprintf(
			'<a href="%1$s" target="_blank">%2$s</a>',
			esc_url( add_query_arg( array(
					'utm_source'   => 'plugins-page',
					'utm_medium'   => 'plugin-row',
					'utm_campaign' => 'admin',
				), 'https://givewp.com/documentation/add-ons/google-analytics-ecommerce-tracking/' )
			),
			esc_html__( 'Documentation', 'give-google-analytics-ecommerce-tracking' )
		),
		sprintf(
			'<a href="%1$s" target="_blank">%2$s</a>',
			esc_url( add_query_arg( array(
					'utm_source'   => 'plugins-page',
					'utm_medium'   => 'plugin-row',
					'utm_campaign' => 'admin',
				), 'https://givewp.com/addons/' )
			),
			esc_html__( 'Add-ons', 'give-google-analytics-ecommerce-tracking' )
		),
	);

	return array_merge( $plugin_meta, $new_meta_links );
}

add_filter( 'plugin_row_meta', 'give_google_analytics_ecommerce_tracking_plugin_row_meta', 10, 2 );