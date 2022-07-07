<?php

namespace GiveGoogleAnalytics\Donations\ValueObjects;

use Give\Framework\Support\ValueObjects\Enum;

/**
 * This class represent add-on specific donation meta keys as enum values.
 *
 * @unreleased
 *
 * @method static DonationMetaKeys GA_CLIENT_ID()
 * @method static DonationMetaKeys GA_CAMPAIGN_NAME()
 * @method static DonationMetaKeys GA_CAMPAIGN_SOURCE()
 * @method static DonationMetaKeys GA_CAMPAIGN_MEDIUM()
 * @method static DonationMetaKeys GA_CAMPAIGN_CONTENT()
 */
class DonationMetaKeys extends Enum
{
    const GA_CLIENT_ID = '_give_ga_client_id';
    const GA_CAMPAIGN_NAME = '_give_ga_campaign';
    const GA_CAMPAIGN_SOURCE = '_give_ga_campaign_source';
    const GA_CAMPAIGN_MEDIUM = '_give_ga_campaign_medium';
    const GA_CAMPAIGN_CONTENT = '_give_ga_campaign_content';
}
