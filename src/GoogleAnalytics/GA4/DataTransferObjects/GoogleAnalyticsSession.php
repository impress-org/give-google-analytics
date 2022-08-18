<?php

namespace GiveGoogleAnalytics\GoogleAnalytics\GA4\DataTransferObjects;

/**
 * @since 2.0.0
 */
class GoogleAnalyticsSession
{
    /**
     * @since 2.0.0
     * @var string
     */
    public $gaSessionId = '';

    /**
     * @since 2.0.0
     * @var string
     */
    public $gaSessionNumber = '';

    /**
     * This function modify Google Analytics client session value into predictable object and return object.
     * This value stores in donation metadata.
     *
     * @since 2.0.0
     */
    public static function fromDonationMetaDataValue(string $session): self
    {
        $self = new self();
        $sessionData = explode('.', $session);

        if (2 < count($sessionData)) {
            $self->gaSessionId = $sessionData[2];
            $self->gaSessionNumber = $sessionData[3];
        }

        return $self;
    }
}
