<?php

/**
 * Class Give_Google_Analytics_Settings
 *
 * @since 1.0
 */
class Give_Google_Analytics_Settings {
	/**
	 * @access private
	 * @var Give_Google_Analytics_Settings $instance
	 */
	static private $instance;

	/**
	 * @access private
	 * @var string $section_id
	 */
	private $section_id;

	/**
	 * @access private
	 * @var string $section_label
	 */
	private $section_label;

	/**
	 * Give_Google_Analytics_Settings constructor.
	 */
	private function __construct() {
	}

	/**
	 * get class object.
	 *
	 * @return Give_Google_Analytics_Settings
	 */
	static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Setup hooks.
	 */
	public function setup_hooks() {

		// Add settings.
		add_filter( 'give_settings_general', array( $this, 'add_settings' ), 99999 );

		add_filter( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );

		add_action( 'give_admin_field_ga_description', array( $this, 'description_field' ) );
	}

	/**
	 * Add scripts.
	 */
	public function add_scripts( $hook ) {

		// Load only on settings pages.
		if ( $hook !== 'give_forms_page_give-settings' ) {
			return;
		}
		wp_register_style( 'give-ga-settings-css', GIVE_GOOGLE_ANALYTICS_URL . 'assets/css/give-ga-settings.css' );
		wp_enqueue_style( 'give-ga-settings-css' );

		wp_register_script( 'give-ga-settings-js', GIVE_GOOGLE_ANALYTICS_URL . 'assets/js/give-ga-settings.js', array('jquery'), GIVE_GOOGLE_ANALYTICS_VERSION, false );
		wp_enqueue_script( 'give-ga-settings-js' );

	}

	/**
	 * Add setting section.
	 *
	 * @param array $sections Array of section.
	 *
	 * @return array
	 */
	public function add_section( $sections ) {
		$sections[ $this->section_id ] = $this->section_label;

		return $sections;
	}

	/**
	 * Add plugin settings.
	 *
	 * @param array $settings Array of setting fields.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {

		$give_ga__settings = array(
			array(
				'name' => __( 'Google Analytics', 'give-google-analytics' ),
				'desc' => '',
				'id'   => 'give_google_analytics_title',
				'type' => 'give_title',
			),
			array(
				'name' => '',
				'desc' => '',
				'id'   => 'ga_description',
				'type' => 'ga_description',
			),
			array(
				'name'    => __( 'Track Test Donations', 'give-google-analytics' ),
				'id'      => 'google_analytics_test_option',
				'type'    => 'radio_inline',
				'desc'    => __( 'Do you want to track donations that are made when Give is in test mode?', 'give-google-analytics' ),
				'default' => 'disabled',
				'options' => array(
					'enabled'  => __( 'Enabled', 'give' ),
					'disabled' => __( 'Disabled', 'give' ),
				)
			),
			array(
				'name'    => __( 'Track Refunds', 'give-google-analytics' ),
				'id'      => 'google_analytics_refunds_option',
				'type'    => 'radio_inline',
				'desc'    => __( 'When a donation changes status to refunded in Give then the refund will also be reflected in Google Analytics as a refund.', 'give-google-analytics' ),
				'default' => 'disabled',
				'options' => array(
					'enabled'  => __( 'Enabled', 'give' ),
					'disabled' => __( 'Disabled', 'give' ),
				)
			),
			array(
				'name'       => __( 'Tracking ID', 'give-google-analytics' ),
				'id'         => 'google_analytics_ua_code',
				'type'       => 'text',
				'attributes' => array( 'placeholder' => 'UA-XXXXXXXX-XX' ),
                'row_classes' => 'give-ga-tracking-id',
				'desc'       => __( 'Since refunds are processed on the backend, Give requires your Google Analytics GA code to properly send refund event data to Google.', 'give-google-analytics' ),
			),
			array(
				'name'    => __( 'Tracking Values', 'give-google-analytics' ),
				'id'      => 'google_analytics_tracking_vals',
				'type'    => 'radio_inline',
				'desc'    => __( 'Adjust some of the values sent to Google Analytics Enhanced Ecommerce.', 'give-google-analytics' ),
				'default' => 'default',
				'options' => array(
					'customized'  => __( 'Customize', 'give' ),
					'default' => __( 'Default', 'give' ),
				)
			),
			array(
				'name'    => __( 'Category', 'give-google-analytics' ),
				'id'      => 'google_analytics_category',
				'type'    => 'text',
				'row_classes' => 'give-ga-advanced-field',
				'default' => __( 'Donations', 'give-google-analytics' ),
				'desc'    => __( 'The category to which the "product" belongs to within Google Analytics.', 'give-google-analytics' ),
			),
			array(
				'name'    => __( 'Affiliation', 'give-google-analytics' ),
				'id'      => 'google_analytics_affiliate',
				'type'    => 'text',
				'row_classes' => 'give-ga-advanced-field',
				'default' => get_bloginfo( 'name' ),
				'desc'    => __( 'The site from which this transaction occurred. Typically this is your site or organization\'s name', 'give-google-analytics' ),
			),
			array(
				'name'    => __( 'List Name', 'give-google-analytics' ),
				'id'      => 'google_analytics_list',
				'type'    => 'text',
				'row_classes' => 'give-ga-advanced-field',
				'default' => 'Donation Forms',
				'desc'    => __( 'The list that the donations belong to and are organized under in Google Analytics.', 'give-google-analytics' ),
			),
		);

		return array_merge( $settings, $give_ga__settings );
	}


	/**
	 * Description Field.
	 */
	function description_field() { ?>

        <div class="give-ga-settings-description">
            <p style="margin:20px 0 0;"><img src="<?php echo GIVE_GOOGLE_ANALYTICS_URL .'assets/img/ga-logo-small.png'; ?>"></p>
        </div>

	<?php }

}

Give_Google_Analytics_Settings::get_instance()->setup_hooks();
