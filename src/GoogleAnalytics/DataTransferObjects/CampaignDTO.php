<?php

namespace GiveGoogleAnalytics\GoogleAnalytics\DataTransferObjects;

/**
 * This class object represents campaign data stores in donation meta. Google Analytics supports utm_* data in url.
 * Read more: https://support.google.com/analytics/answer/10917952
 *
 * @since 2.0.0
 */
class CampaignDTO
{
    /**
     * @since 2.0.0
     * @var string
     */
    public $campaignName;

    /**
     * @since 2.0.0
     * @var string
     */
    public $campaignSource;

    /**
     * @since 2.0.0
     * @var string
     */
    public $campaignMedium;

    /**
     * @since 2.0.0
     * @var string
     */
    public $campaignContent;
}
