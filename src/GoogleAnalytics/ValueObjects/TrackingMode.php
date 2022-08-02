<?php

namespace GiveGoogleAnalytics\GoogleAnalytics\ValueObjects;

use Give\Framework\Support\ValueObjects\Enum;

/**
 * @unreleased
 */
class TrackingMode extends Enum
{
    const UNIVERSAL_ANALYTICS = 'universal-analytics';
    const GOOGLE_ANALYTICS_4 = 'google-analytics-4';
}
