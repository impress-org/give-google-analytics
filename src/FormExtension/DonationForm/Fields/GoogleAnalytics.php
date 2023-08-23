<?php

namespace GiveGoogleAnalytics\FormExtension\DonationForm\Fields;

use Give\Framework\FieldsAPI\Element;

class GoogleAnalytics extends Element
{
    protected $trackingId;
    protected $affiliation;
    protected $trackCategory;
    protected $trackListName;
    protected $trackingMode;
    protected $administrator;

    const TYPE = 'googleAnalytics';

    /**
     * @since 3.0
     */
    public function trackingId(string|null $trackingId): GoogleAnalytics
    {
        $this->trackingId = $trackingId;

        return $this;
    }

    /**
     * @since 3.0
     */
    public function affiliation(string|null $affiliation): GoogleAnalytics
    {
        $this->affiliation = $affiliation;

        return $this;
    }

    /**
     * @since 3.0
     */
    public function trackCategory(string|null $trackCategory): GoogleAnalytics
    {
        $this->trackCategory = $trackCategory;

        return $this;
    }

    /**
     * @since 3.0
     */
    public function trackListName(string|null $trackListName): GoogleAnalytics
    {
        $this->trackListName = $trackListName;

        return $this;
    }

    /**
     * @since 3.0
     */
    public function trackingMode(string|null $trackingMode): GoogleAnalytics
    {
        $this->trackingMode = $trackingMode;

        return $this;
    }

    /**
     * @since 3.0
     */
    public function administrator(string|null $administrator): GoogleAnalytics
    {
        $this->administrator = $administrator;

        return $this;
    }

}
