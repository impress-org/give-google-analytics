<?php

namespace GiveGoogleAnalytics\Addon\ValueObjects;

use Give\Framework\Support\ValueObjects\Enum;

/**
 * This class represent add-on specific setting names as enum values.
 *
 * @unreleased
 *
 * @method static SettingNames TRACKING_MODE()
 * @method static SettingNames UNIVERSAL_ANALYTICS_TRACKING_ID()
 * @method static SettingNames GOGGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_ID()
 * @method static SettingNames GOOGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_PROTOCOL_API_SECRET()
 * @method static SettingNames TRACK_TEST_DONATIONS()
 * @method static SettingNames TRACK_REFUNDS()
 * @method static SettingNames TRACK_VALUES()
 * @method static SettingNames TRACK_CATEGORY()
 * @method static SettingNames TRACK_AFFILIATION()
 * @method static SettingNames TRACKING_LIST_NAME()
 */
class SettingNames extends Enum
{
    const TRACKING_MODE = 'google_tracking_mode';
    const UNIVERSAL_ANALYTICS_TRACKING_ID = 'google_analytics_ua_code';
    const GOOGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_ID = 'google_analytics_ga4_web_stream_measurement_id';
    const GOOGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_PROTOCOL_API_SECRET = 'google_analytics_ga4_measurement_protocol_api_secret';
    const TRACK_TEST_DONATIONS = 'google_analytics_test_option';
    const TRACK_REFUNDS = 'google_analytics_refunds_option';
    const TRACK_VALUES = 'google_analytics_tracking_vals';
    const TRACK_CATEGORY = 'google_analytics_category';
    const TRACK_AFFILIATION = 'google_analytics_affiliate';
    const TRACKING_LIST_NAME = 'google_analytics_list';
}
