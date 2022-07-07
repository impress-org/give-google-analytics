<?php

namespace GiveGoogleAnalytics;

/**
 * This class uses to store donor google analytic data when donor process donation.
 *
 * @unreleased
 */
class StoreDonorGoogleAnalyticsData
{
    public function __invoke(int $donationId)
    {
        if (!$this->canStoreDonorGoogleAnalyticsData()) {
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
    }

    /**
     * This function returns flag whether preserve donor Google Analytics data or not.
     *
     * @unreleased
     */
    private function canStoreDonorGoogleAnalyticsData(): bool
    {
        return isset($_COOKIE['_ga']) && give_ga_can_send_event();
    }
}
