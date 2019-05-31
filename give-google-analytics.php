<?php
/**
 * Plugin Name:     Give - Google Analytics Donation Tracking
 * Plugin URI:      https://givewp.com/addons/google-analytics/
 * Description:     Add Google Analytics Enhanced eCommerce tracking functionality to Give to track donations.
 * Version:         1.2.4
 * Author:          GiveWP
 * Author URI:      https://givewp.com
 * Text Domain:     give-google-analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin version.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_VERSION' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_VERSION', '1.2.4' );
}

// Min. Give version.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION', '2.2.0' );
}

// Plugin File.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_PLUGIN_FILE' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_PLUGIN_FILE', __FILE__ );
}

// Plugin path.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_DIR' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_DIR', plugin_dir_path( GIVE_GOOGLE_ANALYTICS_PLUGIN_FILE ) );
}

// Plugin URL.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_URL' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_URL', plugin_dir_url( GIVE_GOOGLE_ANALYTICS_PLUGIN_FILE ) );
}

// Basename.
if ( ! defined( 'GIVE_GOOGLE_ANALYTICS_BASENAME' ) ) {
	define( 'GIVE_GOOGLE_ANALYTICS_BASENAME', plugin_basename( GIVE_GOOGLE_ANALYTICS_PLUGIN_FILE ) );
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
		 * Notices (array)
		 *
		 * @since 1.2.2
		 *
		 * @var array
		 */
		public $notices = array();

		/**
		 * Get active instance.
		 *
		 * @access      public
		 * @since       1.0
		 * @return      object self::$instance The one true Give_Google_Analytics
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
				self::$instance->setup();
			}

			return self::$instance;
		}

		/**
		 * Setup Give Google Analytics.
		 *
		 * @since 1.2.2
		 * @access private
		 */
		private function setup() {
			// Give init hook.
			add_action( 'give_init', array( $this, 'init' ), 10 );
			add_action( 'admin_init', array( $this, 'check_environment' ), 999 );
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
		}

		/**
		 * Include necessary files.
		 *
		 * @access      private
		 * @since 1.2.2
		 * @return      void
		 */
		public function init() {

			if ( ! $this->get_environment_warning() ) {
				return;
			}

			$this->licensing();
			$this->load_textdomain();
			$this->activation_banner();

			require_once GIVE_GOOGLE_ANALYTICS_DIR . 'includes/class-give-google-analytics-settings.php';
			require_once GIVE_GOOGLE_ANALYTICS_DIR . 'includes/give-google-analytics-functions.php';
			require_once GIVE_GOOGLE_ANALYTICS_DIR . 'includes/filters.php';
			require_once GIVE_GOOGLE_ANALYTICS_DIR . 'includes/actions.php';
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

		/**
		 * Check plugin environment.
		 *
		 * @since 1.2.2
		 * @access public
		 *
		 * @return bool
		 */
		public function check_environment() {
			// Flag to check whether plugin file is loaded or not.
			$is_working = true;

			// Load plugin helper functions.
			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			/* Check to see if Give is activated, if it isn't deactivate and show a banner. */
			// Check for if give plugin activate or not.
			$is_give_active = defined( 'GIVE_PLUGIN_BASENAME' ) ? is_plugin_active( GIVE_PLUGIN_BASENAME ) : false;

			if ( empty( $is_give_active ) ) {
				// Show admin notice.
				$this->add_admin_notice( 'prompt_give_activate', 'error', sprintf( __( '<strong>Activation Error:</strong> You must have the <a href="%s" target="_blank">Give</a> plugin installed and activated for Google Analytics Donation Tracking to activate.', 'give-google-analytics' ), 'https://givewp.com' ) );
				$is_working = false;
			}

			return $is_working;
		}

		/**
		 * Check plugin for Give environment.
		 *
		 * @since 1.2.2
		 * @access public
		 *
		 * @return bool
		 */
		public function get_environment_warning() {
			// Flag to check whether plugin file is loaded or not.
			$is_working = true;

			// Verify dependency cases.
			if (
				defined( 'GIVE_VERSION' )
				&& version_compare( GIVE_VERSION, GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION, '<' )
			) {

				/* Min. Give. plugin version. */
				// Show admin notice.
				$this->add_admin_notice( 'prompt_give_incompatible', 'error', sprintf( __( '<strong>Activation Error:</strong> You must have the <a href="%s" target="_blank">Give</a> core version %s for the Google Analytics Donation Tracking add-on to activate.', 'give-google-analytics' ), 'https://givewp.com', GIVE_GOOGLE_ANALYTICS_MIN_GIVE_VERSION ) );

				$is_working = false;
			}

			return $is_working;
		}

		/**
		 * Allow this class and other classes to add notices.
		 *
		 * @since 1.2.2
		 *
		 * @param $slug
		 * @param $class
		 * @param $message
		 */
		public function add_admin_notice( $slug, $class, $message ) {
			$this->notices[ $slug ] = array(
				'class'   => $class,
				'message' => $message,
			);
		}

		/**
		 * Display admin notices.
		 *
		 * @since 1.2.2
		 */
		public function admin_notices() {

			$allowed_tags = array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
					'class' => array(),
					'id'    => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'span'   => array(
					'class' => array(),
				),
				'strong' => array(),
			);

			foreach ( (array) $this->notices as $notice_key => $notice ) {
				echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
				echo wp_kses( $notice['message'], $allowed_tags );
				echo '</p></div>';
			}

		}

		/**
		 * Implement Give Licensing for Give Google Analytics Add On.
		 *
		 * @since 1.2.2
		 * @access private
		 */
		private function licensing() {
			if ( class_exists( 'Give_License' ) ) {
				new Give_License( GIVE_GOOGLE_ANALYTICS_DIR, 'Google Analytics Donation Tracking', GIVE_GOOGLE_ANALYTICS_VERSION, 'WordImpress' );
			}
		}

		/**
		 * Give Google Analytics Ecommerce Tracking Activation Banner
		 *
		 * Includes and initializes Give activation banner class.
		 *
		 * @since 1.2.2
		 */
		public function activation_banner() {

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
	}

	/**
	 * Returns class object instance.
	 *
	 * @since 1.2.2
	 *
	 * @return Give_Google_Analytics bool|object
	 */
	function Give_Google_Analytics() {
		return Give_Google_Analytics::instance();
	}

	Give_Google_Analytics();
}
