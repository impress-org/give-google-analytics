<?php

namespace GiveGoogleAnalytics\GoogleAnalytics\DataTransferObjects;

/**
 * This class object represents campaign data stores in donation meta. Google Analytics supports utm_* data in url.
 * Read more: https://support.google.com/analytics/answer/10917952
 *
 * @unreleased
 */
class CampaignDTO
{
    /**
     * @unreleased
     * @var string
     */
    public $campaignName;

    /**
     * @unreleased
     * @var string
     */
    public $campaignSource;

    /**
     * @unreleased
     * @var string
     */
    public $campaignMedium;

    /**
     * @unreleased
     * @var string
     */
    public $campaignContent;
}
