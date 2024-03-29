<?php
/**
 * Give Google Analytics - Settings
 *
 * @package    Give
 * @since      1.2.4
 * @license    https://opensource.org/licenses/gpl-license GNU Public License
 * @copyright  Copyright (c) 2019, GiveWP
 */

// Exit if accessed directly
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Settings\ValueObjects\SettingNames;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Give_Google_Analytics_Settings
 *
 * @since 1.0
 */
class Give_Google_Analytics_Settings
{
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
    private function __construct()
    {
    }

    /**
     * get class object.
     *
     * @return Give_Google_Analytics_Settings
     */
    static function get_instance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Setup hooks.
     */
    public function setup()
    {
        // Setup params.
        $this->section_id = 'google-analytics';
        $this->section_label = __('Google Analytics', 'give-google-analytics');

        // Add settings.
        add_filter('give_get_settings_general', [$this, 'add_settings']);
        add_filter('give_get_sections_general', [$this, 'add_section']);

        add_filter('admin_enqueue_scripts', [$this, 'add_scripts']);

        add_action('give_admin_field_ga_description', [$this, 'description_field']);
    }

    /**
     * Add scripts.
     *
     * @since 2.0.0 Load assets from dist directory
     */
    public function add_scripts($hook)
    {
        // Load only on settings pages.
        if ($hook !== 'give_forms_page_give-settings') {
            return;
        }
        wp_register_style('give-ga-settings-css', GIVE_GOOGLE_ANALYTICS_URL . 'assets/dist/css/give-ga-settings.css');
        wp_enqueue_style('give-ga-settings-css');

        wp_register_script(
            'give-ga-settings-js',
            GIVE_GOOGLE_ANALYTICS_URL . 'assets/dist/js/give-ga-settings.js',
            ['jquery'],
            GIVE_GOOGLE_ANALYTICS_VERSION,
            false
        );
        wp_enqueue_script('give-ga-settings-js');
    }

    /**
     * Add setting section.
     *
     * @param array $sections Array of section.
     *
     * @return array
     */
    public function add_section($sections)
    {
        $sections[$this->section_id] = $this->section_label;

        return $sections;
    }

    /**
     * Add plugin settings.
     *
     * @param array $settings Array of setting fields.
     *
     * @return array
     */
    public function add_settings($settings)
    {
        // Show setting only on section page.
        if ($this->section_id !== give_get_current_setting_section()) {
            return $settings;
        }

        // we are using this param to set default "Google Tracking Mode" and toggle specific setting fields.
        $isSupportUniversalAnalytics = (bool)give_get_option('google_analytics_ua_code', '');

        $give_ga__settings = [
            [
                'id' => 'give_title_google_analytics',
                'type' => 'title',
            ],
            [
                'name' => '',
                'desc' => '',
                'id' => 'ga_description',
                'type' => 'ga_description',
            ],
            [
                'name' => __('Google Analytics', 'give-google-analytics'),
                'desc' => '',
                'id' => 'give_google_analytics_title',
                'type' => 'give_title',
            ],
            [
                'name' => __('Google Tracking Mode', 'give-google-analytics'),
                'id' => SettingNames::TRACKING_MODE,
                'type' => 'radio_inline',
                'desc' => __(
                    'Which Google tracking mode do you want to use for tracking?',
                    'give-google-analytics'
                ),
                // We set default value dynamically to support compatibility for existing universal analytics settings.
                'default' => $isSupportUniversalAnalytics ?
                    TrackingMode::UNIVERSAL_ANALYTICS :
                    TrackingMode::GOOGLE_ANALYTICS_4,
                'options' => [
                    TrackingMode::GOOGLE_ANALYTICS_4 => __('Google Analytics 4', 'give-google-analytics'),
                    TrackingMode::UNIVERSAL_ANALYTICS => __('Universal Analytics', 'give-google-analytics')
                ],
            ],
            [
                'name' => __('Tracking ID', 'give-google-analytics'),
                'id' => SettingNames::UNIVERSAL_ANALYTICS_TRACKING_ID,
                'type' => 'text',
                'attributes' => ['placeholder' => 'UA-XXXXXXXX-XX'],
                'wrapper_class' => $isSupportUniversalAnalytics ?
                    'give-universal-analytics give-ga-tracking-id' :
                    'give-universal-analytics give-ga-tracking-id give-hidden',
                'desc' => sprintf(
                    __(
                        'Enter your Tracking ID to track offsite payment gateways and refunds. Find the Tracking ID by visiting your <a href="%s" target="_blank">Google Analytics</a> dashboard.',
                        'give-google-analytics'
                    ),
                    'https://analytics.google.com/analytics/web/'
                ),
            ],
            [
                'name' => __('Web Stream Measurement ID', 'give-google-analytics'),
                'id' => SettingNames::GOOGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_ID,
                'type' => 'text',
                'attributes' => ['placeholder' => 'G-XXXXXXXX-XX'],
                'wrapper_class' => $isSupportUniversalAnalytics ?
                    'give-google-analytics-4 give-ga4__tracking-id give-hidden' :
                    'give-google-analytics-4 give-ga4__tracking-id',
                'desc' => sprintf(
                    __(
                        'Enter your Measurement ID to track offsite payment gateways and refunds. Find the Measurement ID by visiting your <a href="%s" target="_blank">Google Analytics</a> dashboard and choose <code>Admin > Data Streams > [your stream] > Measurement ID.</code>',
                        'give-google-analytics'
                    ),
                    'https://analytics.google.com/analytics/web/'
                ),
            ],
            [
                'name' => __('Measurement Protocol API Secret', 'give-google-analytics'),
                'id' => SettingNames::GOOGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_PROTOCOL_API_SECRET,
                'type' => 'text',
                'wrapper_class' => $isSupportUniversalAnalytics ?
                    'give-google-analytics-4 give-ga4__measurement-protocol-api-secret give-hidden' :
                    'give-google-analytics-4 give-ga4__measurement-protocol-api-secret',
                'desc' => sprintf(
                    __(
                        'Enter your Measurement Protocol API Secret. Find or create the API Secret by visiting your <a href="%s" target="_blank">Google Analytics dashboard</a> and choose <code>Admin > Data Streams > [your stream] > Measurement Protocol API Secrets </code>.',
                        'give-google-analytics'
                    ),
                    'https://analytics.google.com/analytics/web/'
                ),
            ],
            [
                'name' => __('Track Test Donations', 'give-google-analytics'),
                'id' => SettingNames::TRACK_TEST_DONATIONS,
                'type' => 'radio_inline',
                'desc' => __(
                    'Do you want to track donations that are made when GiveWP is in test mode?',
                    'give-google-analytics'
                ),
                'default' => 'disabled',
                'options' => [
                    'enabled' => __('Enabled', 'give-google-analytics'),
                    'disabled' => __('Disabled', 'give-google-analytics'),
                ],
            ],
            [
                'name' => __('Track Refunds', 'give-google-analytics'),
                'id' => SettingNames::TRACK_REFUNDS,
                'type' => 'radio_inline',
                'desc' => __(
                    'Do you want to track refunds? When a donation is marked as refunded in GiveWP, it will be reflected in Google Analytics.',
                    'give-google-analytics'
                ),
                'default' => 'disabled',
                'options' => [
                    'enabled' => __('Enabled', 'give-google-analytics'),
                    'disabled' => __('Disabled', 'give-google-analytics'),
                ],
            ],
            [
                'name' => __('Tracking Values', 'give-google-analytics'),
                'id' => SettingNames::TRACK_VALUES,
                'type' => 'radio_inline',
                'desc' => __(
                    'Adjust some of the values sent to Google Analytics Enhanced Ecommerce.',
                    'give-google-analytics'
                ),
                'default' => 'default',
                'options' => [
                    'customized' => __('Customize', 'give-google-analytics'),
                    'default' => __('Default', 'give-google-analytics'),
                ],
            ],
            [
                'name' => __('Category', 'give-google-analytics'),
                'id' => SettingNames::TRACK_CATEGORY,
                'type' => 'text',
                'wrapper_class' => 'give-ga-advanced-field',
                'default' => __('Donations', 'give-google-analytics'),
                'desc' => __(
                    'The "product" category within Google Analytics for Donations. This is helpful for filtering in GA to view only donations.',
                    'give-google-analytics'
                ),
            ],
            [
                'name' => __('Affiliation', 'give-google-analytics'),
                'id' => SettingNames::TRACK_AFFILIATION,
                'type' => 'text',
                'wrapper_class' => 'give-ga-advanced-field',
                'default' => get_bloginfo('name'),
                'desc' => __(
                    'The site where the donation occurred. Typically this is your website or organization\'s name.',
                    'give-google-analytics'
                ),
            ],
            [
                'name' => __('List Name', 'give-google-analytics'),
                'id' => SettingNames::TRACKING_LIST_NAME,
                'type' => 'text',
                'wrapper_class' => 'give-ga-advanced-field',
                'default' => 'Donation Forms',
                'desc' => __(
                    'The list that the donations belong to and are organized under in Google Analytics.',
                    'give-google-analytics'
                ),
            ],
            [
                'name' => esc_html__('Google Analytics Docs Link', 'give-google-analytics'),
                'id' => 'google_analytics_docs_link',
                'url' => esc_url('http://docs.givewp.com/addon-google-analytics'),
                'title' => __('Google Analytics Add-on Documentation', 'give-google-analytics'),
                'type' => 'give_docs_link',
            ],
            [
                'id' => 'give_title_google_analytics',
                'type' => 'sectionend',
            ],
        ];

        return $give_ga__settings;
    }


    /**
     * Description Field.
     *
     * @since 2.0.0 Load images from dist directory.
     */
    function description_field()
    {
        ?>

        <div class="give-ga-settings-description">
            <p style="margin:20px 0 0;">
                <img src="<?php echo GIVE_GOOGLE_ANALYTICS_URL . 'assets/dist/img/ga-logo-small.png'; ?>"></p>
        </div>

        <?php
    }

}

Give_Google_Analytics_Settings::get_instance()->setup();
