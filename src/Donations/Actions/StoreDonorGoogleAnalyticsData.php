<?php

namespace GiveGoogleAnalytics\Donations\Actions;

use Give\Donations\Models\Donation;
use GiveGoogleAnalytics\Donations\ValueObjects\DonationMetaKeys;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

/**
 * This class uses to store donor google analytic data when donor process donation.
 *
 * @since 2.0.0
 */
class StoreDonorGoogleAnalyticsData
{
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @since 2.0.0
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @since 2.0.0
     *
     * @return void
     */
    public function __invoke(int $donationId)
    {
        $donation = Donation::find($donationId);

        if (!$donation || !$this->canStoreDonorGoogleAnalyticsData($donation)) {
            return;
        }

        $clientId = explode('.', give_clean($_COOKIE['_ga']), 3);
        $clientId = array_pop($clientId);

        $campaign = empty($_COOKIE['give_campaign']) ? '' : give_clean($_COOKIE['give_campaign']);
        $campaignSource = empty($_COOKIE['give_source']) ? '' : give_clean($_COOKIE['give_source']);
        $campaignMedium = empty($_COOKIE['give_medium']) ? '' : give_clean($_COOKIE['give_medium']);
        $campaignContent = empty($_COOKIE['give_content']) ? '' : give_clean($_COOKIE['give_content']);

        give_update_payment_meta($donationId, DonationMetaKeys::GA_CLIENT_ID, $clientId);
        give_update_payment_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_NAME, $campaign);
        give_update_payment_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_SOURCE, $campaignSource);
        give_update_payment_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_MEDIUM, $campaignMedium);
        give_update_payment_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_CONTENT, $campaignContent);

        give_update_payment_meta(
            $donationId,
            DonationMetaKeys::GA_CLIENT_SESSION_ID,
            $this->getGoogleAnalyticsClientSession()
        );
    }

    /**
     * This function returns flag whether preserve donor Google Analytics data or not.
     *
     * @since 2.0.0
     */
    private function canStoreDonorGoogleAnalyticsData(Donation $donation): bool
    {
        return isset($_COOKIE['_ga']) &&
            $donation->gatewayId !== 'manual_donation' &&
            $this->settingRepository->canSendEvent();
    }

    /**
     * This function returns session value of client. Session id generates by google Analytics.
     *
     * @since 2.0.0
     *
     * @return string
     */
    private function getGoogleAnalyticsClientSession(): string
    {
        $webStreamMeasurementId = $this->settingRepository->getGoogleAnalytics4WebStreamMeasurementId();
        $id = str_replace('G-', '', $webStreamMeasurementId);
        $sessionKeyName = "_ga_$id";

        return !empty($_COOKIE[$sessionKeyName]) ? give_clean($_COOKIE[$sessionKeyName]) : '';
    }
}
