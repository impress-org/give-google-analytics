<?php

namespace GiveGoogleAnalytic\Addon\Repositories;

use GiveGoogleAnalytics\Addon\ValueObjects\SettingNames;

/**
 * This class use to get add-on settings values from database.
 *
 * @unreleased
 */
class SettingRepository
{
    /**
     * This function returns Google Analytics tracking mode.
     *
     * @unreleased
     */
    public function getTrackingMode(): string
    {
        return give_get_option(SettingNames::TRACKING_MODE, '');
    }

    /**
     * This function returns Google Analytics Web stream measurement id
     *
     * @unreleased
     */
    public function getTrackingId(): string
    {
        return give_get_option(SettingNames::TRACKING_ID, '');
    }

    /**
     * This function returns flag whether track test donations.
     *
     * @unreleased
     */
    public function canTrackTestDonations(): bool
    {
        return give_is_setting_enabled(give_get_option(SettingNames::TRACK_TEST_DONATIONS, 'disabled'));
    }

    /**
     * This function returns flag whether track refunded donations.
     *
     * @unreleased
     */
    public function canTrackRefunds(): string
    {
        return give_is_setting_enabled(give_get_option(SettingNames::TRACK_REFUNDS, 'disabled'));
    }

    /**
     * This function returns flag whether track values.
     * TODO: add explaining about what are tracking values
     *
     * @unreleased
     */
    public function canTrackValues(): string
    {
        return give_is_setting_enabled(give_get_option(SettingNames::TRACK_VALUES, 'disabled'));
    }

    /**
     * This function returns track category name.
     *
     * @unreleased
     */
    public function getTrackCategory(): string
    {
        return give_get_option(SettingNames::TRACK_CATEGORY, '');
    }

    /**
     * This function returns track affiliation name.
     *
     * @unreleased
     */
    public function getTrackAffiliation(): string
    {
        return give_get_option(SettingNames::TRACK_AFFILIATION, '');
    }

    /**
     * This function returns track list name.
     *
     * @unreleased
     */
    public function getTrackListName(): string
    {
        return give_get_option(SettingNames::TRACKING_LIST_NAME, '');
    }

    /**
     * This function returns boolean value whether add-on track analytics with Universal Analytics.
     *
     * @unreleased
     */
    public function isSupportTrackingUniversalAnalytics(): bool
    {
        return 'universal-analytics' === $this->getTrackingMode();
    }

    /**
     * This function returns boolean value whether add-on track analytics with Google Analytics 4.
     *
     * @unreleased
     */
    public function isSupportTrackingGoogleAnalytics4(): bool
    {
        return 'google-analytics-4' === $this->getTrackingMode();
    }
}
