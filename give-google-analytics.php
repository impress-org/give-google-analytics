<?php
/**
 * Plugin Name:     Give - Google Analytics Donation Tracking
 * Plugin URI:      https://givewp.com/addons/google-analytics/
 * Description:     Add Google Analytics Enhanced eCommerce tracking functionality to Give to track donations.
 * Version:         1.0
 * Author:          WordImpress
 * Author URI:      https://wordimpress.com
 * Text Domain:     give-google-analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin version.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_VERSION' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_VERSION', '1.0' );
}

// Min. Give version.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION', '1.8.4' );
}

// Plugin path.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_DIR' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin URL.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_URL' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_URL', plugin_dir_url( __FILE__ ) );
}

// Basename.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_BASENAME' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * Main Give_Google_Analytics class.
 *
 * @since       1.0
 */
if ( ! class_exists( 'Give_Google_Analytics' ) ) {

	/**
	 * Class Give_Google_Analytics
	 */
	class Give_Google_Analytics {

		/**
		 * @var         Give_Google_Analytics $instance The one true Give_Google_Analytics.
		 *
		 * @since       1.0
		 */
		private static $instance;

		/**
		 * Get active instance.
		 *
		 * @access      public
		 * @since       1.0
		 * @return      object self::$instance The one true Give_Google_Analytics
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new Give_Google_Analytics();
				self::$instance->load_textdomain();
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 * Include necessary files.
		 *
		 * @access      private
		 * @since       1.0
		 * @return      void
		 */
		private function includes() {
			require_once GIVE_GOOGLE_ANALYTICS_DIR . 'includes/class-setting-fields.php';
			require_once GIVE_GOOGLE_ANALYTICS_DIR . 'includes/give-google-analytics-functions.php';
			require_once GIVE_GOOGLE_ANALYTICS_DIR . 'includes/give-google-analytics-activation.php';
		}

		/**
		 * Internationalization.
		 *
		 * @access      public
		 * @since       1.0
		 * @return      void
		 */
		public function load_textdomain() {

			// Set filter for language directory
			$lang_dir = GIVE_GOOGLE_ANALYTICS_DIR . '/languages/';
			$lang_dir = apply_filters( 'give_google_analytics_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'give-google-analytics' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'give-google-analytics', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/give-google-analytics/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/give-google-analytics/ folder.
				load_textdomain( 'give-google-analytics', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/give-google-analytics/languages/ folder.
				load_textdomain( 'give-google-analytics', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'give-google-analytics', false, $lang_dir );
			}
		}

	}
}

/**
 * Google Analytics Load.
 *
 * @return object|bool Give_Google_Analytics
 */
function give_google_analytics_load() {

	if ( give_google_analytics_check_environment() ) {
		return Give_Google_Analytics::instance();
	}

	return false;
}

add_action( 'plugins_loaded', 'give_google_analytics_load' );

/**
 * Give - GA Add-on Licensing
 */
function give_add_google_analytics_licensing() {

	if ( class_exists( 'Give_License' ) ) {
		new Give_License( __FILE__, 'Google Analytics Donation Tracking', GIVE_GOOGLE_ANALYTICS_VERSION, 'WordImpress' );
	}
}

add_action( 'plugins_loaded', 'give_add_google_analytics_licensing' );

/**
 * Check the environment before starting up.
 *
 * @since 1.0
 *
 * @return bool
 */
function give_google_analytics_check_environment() {

	// Check for if give plugin activate or not.
	$is_give_active = defined( 'GIVE_PLUGIN_BASENAME' ) ? true : false;

	// Check to see if Give is activated, if it isn't deactivate and show a banner
	if ( current_user_can( 'activate_plugins' ) && ! $is_give_active ) {
		add_action( 'admin_notices', 'give_google_analytics_activation_notice' );
		add_action( 'admin_init', 'give_google_analytics_deactivate_self' );

		return false;
	}

	// Check minimum Give version.
	if ( defined( 'GIVE_VERSION' ) && version_compare( GIVE_VERSION, GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION, '<' ) ) {
		add_action( 'admin_notices', 'give_google_analytics_min_version_notice' );
		add_action( 'admin_init', 'give_google_analytics_deactivate_self' );

		return false;
	}

	return true;

}

/**
 * Deactivate self. Must be hooked with admin_init.
 *
 * Currently hooked via give_google_analytics_check_environment()
 */
function give_google_analytics_deactivate_self() {
	deactivate_plugins( GIVE_GOOGLE_ANALYTICS_BASENAME );
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}


/**
 * Notice for No Core Activation
 *
 * @since 1.0
 */
function give_google_analytics_activation_notice() {
	echo '<div class="error"><p>' . __( '<strong>Activation Error:</strong> You must have the <a href="https://givewp.com/" target="_blank">Give</a> plugin installed and activated for the Google Analytics add-on to activate.', 'give-google-analytics' ) . '</p></div>';
}

/**
 * Notice for No Core Activation
 *
 * @since 1.0
 */
function give_google_analytics_min_version_notice() {
	echo '<div class="error"><p>' . sprintf( __( '<strong>Activation Error:</strong> You must have <a href="%1$s" target="_blank">Give</a> version %2$s+ for the Google Analytics add-on to activate.', 'give-google-analytics' ), 'https://givewp.com', GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION ) . '</p></div>';
}

