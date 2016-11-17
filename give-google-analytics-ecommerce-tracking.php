<?php
/*
 * Plugin Name:     Give - Google Analytics Ecommerce Tracking
 * Plugin URI:      https://wordpress.org/plugins/give-google-analytics-ecommerce-tracking
 * Description:     Add Google Analytics ecommerce tracking code to the donation confirmation page for Give Donations.
 * Version:         1.0
 * Author:          WordImpress
 * Author URI:      https://wordimpress.com
 * Text Domain:     give-google-analytics-ecommerce-tracking
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Give_Google_Analytics_Ecommerce_Tracking class.
 *
 * @since       1.0
 */
if ( ! class_exists( 'Give_Google_Analytics_Ecommerce_Tracking' ) ) {
	class Give_Google_Analytics_Ecommerce_Tracking {

		/**
		 * @var         Give_Google_Analytics_Ecommerce_Tracking $instance The one true Give_Google_Analytics_Ecommerce_Tracking.
		 *
		 * @since       1.0
		 */
		private static $instance;

		/**
		 * Get active instance.
		 *
		 * @access      public
		 * @since       1.0
		 * @return      object self::$instance The one true Give_Google_Analytics_Ecommerce_Tracking
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new Give_Google_Analytics_Ecommerce_Tracking();
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				self::$instance->includes();
			}

			return self::$instance;
		}

		/**
		 * Setup plugin constants.
		 *
		 * @access      private
		 * @since       1.0
		 * @return      void
		 */
		private function setup_constants() {

			// Plugin version.
			if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_VERSION' ) ) {
				define( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_VERSION', '1.0' );
			}

			// Plugin path.
			if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_DIR' ) ) {
				define( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin URL.
			if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_URL' ) ) {
				define( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_URL', plugin_dir_url( __FILE__ ) );
			}

			//Basename
			if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_BASENAME' ) ) {
				define( 'GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_BASENAME', plugin_basename( __FILE__ ) );
			}

		}

		/**
		 * Include necessary files.
		 *
		 * @access      private
		 * @since       1.0
		 * @return      void
		 */
		private function includes() {
			require_once GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_DIR . 'includes/functions.php';
			require_once GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_DIR . 'includes/give-google-analytics-ecommerce-tracking-activation.php';
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
			$lang_dir = GIVE_GOOGLE_ANALYTICS_ECOMMERCE_TRACKING_DIR . '/languages/';
			$lang_dir = apply_filters( 'give_google_analytics_ecommerce_tracking_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'give-google-analytics-ecommerce-tracking' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'give-google-analytics-ecommerce-tracking', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/give-google-analytics-ecommerce-tracking/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/give-google-analytics-ecommerce-tracking/ folder
				load_textdomain( 'give-google-analytics-ecommerce-tracking', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/give-google-analytics-ecommerce-tracking/languages/ folder
				load_textdomain( 'give-google-analytics-ecommerce-tracking', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'give-google-analytics-ecommerce-tracking', false, $lang_dir );
			}
		}

	}
}

function Give_Google_Analytics_Ecommerce_Tracking_load() {
	return Give_Google_Analytics_Ecommerce_Tracking::instance();
}

add_action( 'plugins_loaded', 'Give_Google_Analytics_Ecommerce_Tracking_load' );