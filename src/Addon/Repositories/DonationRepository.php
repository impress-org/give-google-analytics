<?php

namespace GiveGoogleAnalytics\Addon\Repositories;

use GiveGoogleAnalytics\Addon\DataTransferObjects\CampaignDTO;
use GiveGoogleAnalytics\Donations\ValueObjects\DonationMetaKeys;
use GiveGoogleAnalytics\GoogleAnalytics\GA4\DataTransferObjects\GoogleAnalyticsSession;

/**
 * @unreleased
 */
class DonationRepository
{
    /**
     * This function returns whether event sent to google analytics.
     *
     */
    public function isGoogleAnalyticEventSent(int $donationId): bool
    {
        return '1' === give_get_meta($donationId, DonationMetaKeys::GA_EVENT_SENT, true);
    }

    /**
     * This function returns whether event sent to google analytics.
     *
     */
    public function setGoogleAnalyticEventSent(int $donationId): bool
    {
        return give_update_payment_meta($donationId, DonationMetaKeys::GA_EVENT_SENT, true);
    }

    /**
     * This function returns stored google analytics campaign data from donation metadata.
     *
     * @unreleased
     */
    public function getGoogleAnalyticsCampaign(int $donationId): CampaignDTO
    {
        $campaign = new CampaignDTO();

        $campaign->campaignName = give_get_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_NAME, true);
        $campaign->campaignSource = give_get_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_SOURCE, true);
        $campaign->campaignMedium = give_get_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_MEDIUM, true);
        $campaign->campaignContent = give_get_meta($donationId, DonationMetaKeys::GA_CAMPAIGN_CONTENT, true);

        return $campaign;
    }

    /**
     * This function returns google tracking id for donor.
     *
     * @unreleased
     */
    public function getGoogleAnalyticsClientTrackingId(int $donationId): string
    {
        return give_get_meta($donationId, DonationMetaKeys::GA_CLIENT_ID, true);
    }

    /**
     * @unreleased
     */
    public function getGoogleAnalyticsClientSession(int $donationId): GoogleAnalyticsSession
    {
        return new GoogleAnalyticsSession(give_get_meta($donationId, DonationMetaKeys::GA_CLIENT_SESSION_ID, true));
    }
}
