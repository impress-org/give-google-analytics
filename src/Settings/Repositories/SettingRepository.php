<?php

namespace GiveGoogleAnalytics\Settings\Repositories;

use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Settings\ValueObjects\SettingNames;

/**
 * This class use to get add-on settings values from database.
 *
 * @since 2.0.0
 */
class SettingRepository
{
    /**
     * This function returns Google Analytics tracking mode.
     *
     * @since 2.0.0
     */
    public function getTrackingMode(): string
    {
        return give_get_option(SettingNames::TRACKING_MODE, '');
    }

    /**
     * This function returns Universal Analytics tracking id
     *
     * @since 2.0.0
     */
    public function getUniversalAnalyticsTrackingId(): string
    {
        return give_get_option(SettingNames::UNIVERSAL_ANALYTICS_TRACKING_ID, '');
    }

    /**
     * This function returns Google Analytics 4 Web stream measurement id
     *
     * @since 2.0.0
     */
    public function getGoogleAnalytics4WebStreamMeasurementId(): string
    {
        return give_get_option(SettingNames::GOOGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_ID, '');
    }

    /**
     * This function returns Google Analytics 4 Web stream measurement protocol api secret
     *
     * @since 2.0.0
     */
    public function getGoogleAnalytics4WebStreamMeasurementProtocolApiSecret(): string
    {
        return give_get_option(SettingNames::GOOGLE_ANALYTICS_4_WEB_STREAM_MEASUREMENT_PROTOCOL_API_SECRET, '');
    }

    /**
     * This function returns flag whether track test donations.
     *
     * @since 2.0.0
     */
    public function canTrackTestDonations(): bool
    {
        return give_is_setting_enabled(give_get_option(SettingNames::TRACK_TEST_DONATIONS, 'disabled'));
    }

    /**
     * This function returns flag whether track refunded donations.
     *
     * @since 2.0.0
     */
    public function canTrackRefunds(): string
    {
        return give_is_setting_enabled(give_get_option(SettingNames::TRACK_REFUNDS, 'disabled'));
    }

    /**
     * This function returns flag whether track values.
     * TODO: add explaining about what are tracking values
     *
     * @since 2.0.0
     */
    public function canTrackValues(): string
    {
        return give_is_setting_enabled(give_get_option(SettingNames::TRACK_VALUES, 'disabled'));
    }

    /**
     * This function returns track category name.
     *
     * @since 2.0.0
     */
    public function getTrackCategory(): string
    {
        return give_get_option(SettingNames::TRACK_CATEGORY, 'Donations');
    }

    /**
     * This function returns track affiliation name.
     *
     * @since 2.0.0
     */
    public function getTrackAffiliation(): string
    {
        return give_get_option(SettingNames::TRACK_AFFILIATION, get_bloginfo('name'));
    }

    /**
     * This function returns track list name.
     *
     * @since 2.0.0
     */
    public function getTrackListName(): string
    {
        return give_get_option(SettingNames::TRACKING_LIST_NAME, 'Donation Forms');
    }

    /**
     * This function returns boolean value whether add-on track analytics with Universal Analytics.
     *
     * @since 2.0.0
     */
    public function isSupportTrackingUniversalAnalytics(): bool
    {
        return 'universal-analytics' === $this->getTrackingMode();
    }

    /**
     * This function returns boolean value whether add-on track analytics with Google Analytics 4.
     *
     * @since 2.0.0
     */
    public function isSupportTrackingGoogleAnalytics4(): bool
    {
        return 'google-analytics-4' === $this->getTrackingMode();
    }

    /**
     * This function returns whether record google event.
     *
     * @since 2.0.0
     */
    public function canSendEvent(string $trackingMode = null): bool
    {
        // Don't continue if test mode is enabled and test mode tracking is disabled.
        if (give_is_test_mode() && !give_google_analytics_track_testing()) {
            return false;
        }

        $trackingMode = $trackingMode ?? $this->getTrackingMode();

        if ($trackingMode === TrackingMode::UNIVERSAL_ANALYTICS) {
            return (bool)$this->getUniversalAnalyticsTrackingId();
        }

        if ($trackingMode === TrackingMode::GOOGLE_ANALYTICS_4) {
            return (bool)(
                $this->getGoogleAnalytics4WebStreamMeasurementId() &&
                $this->getGoogleAnalytics4WebStreamMeasurementProtocolApiSecret()
            );
        }

        return false;
    }
}
