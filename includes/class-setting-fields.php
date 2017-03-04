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
				'desc' => '<hr>',
				'id'   => 'give_google_analytics_title',
				'type' => 'give_title',
			),
			array(
				'name'    => __( 'Affiliation', 'give-google-analytics' ),
				'id'      => 'google_analytics_affiliate',
				'type'    => 'text',
				'default' => get_bloginfo( 'name' ),
				'desc'    => __( 'The site from which this transaction occurred. Typically this is your site or organization\'s name', 'give-google-analytics' ),
			),
			array(
				'name'    => __( 'List Name', 'give-google-analytics' ),
				'id'      => 'google_analytics_list',
				'type'    => 'text',
				'default' => 'Donation Forms',
				'desc'    => __( 'The list that the donations belong to and are organized under in Google Analytics.', 'give-google-analytics' ),
			),
		);

		return array_merge( $settings, $give_ga__settings );
	}

}

Give_Google_Analytics_Settings::get_instance()->setup_hooks();
