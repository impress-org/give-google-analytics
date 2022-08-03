<?php

namespace GiveGoogleAnalytics\GoogleAnalytics\GA4\DataTransferObjects;

/**
 * @unreleased
 */
class GoogleAnalyticsSession
{
    /**
     * @unreleased
     * @var string
     */
    public $gaSessionId = '';

    /**
     * @unreleased
     * @var string
     */
    public $gaSessionNumber = '';

    /**
     * @param string $session
     */
    public function __construct(string $session)
    {
        $sessionData = explode('.', $session);

        if (2 < count($sessionData)) {
            $this->gaSessionId = $session[2];
            $this->gaSessionNumber = $session[3];
        }
    }
}
