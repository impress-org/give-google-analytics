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
     * @unreleased
     */
    public function trackingId(string|null $trackingId): GoogleAnalytics
    {
        $this->trackingId = $trackingId;

        return $this;
    }

    /**
     * @unreleased
     */
    public function affiliation(string|null $affiliation): GoogleAnalytics
    {
        $this->affiliation = $affiliation;

        return $this;
    }

    /**
     * @unreleased
     */
    public function trackCategory(string|null $trackCategory): GoogleAnalytics
    {
        $this->trackCategory = $trackCategory;

        return $this;
    }

    /**
     * @unreleased
     */
    public function trackListName(string|null $trackListName): GoogleAnalytics
    {
        $this->trackListName = $trackListName;

        return $this;
    }

    /**
     * @unreleased
     */
    public function trackingMode(string|null $trackingMode): GoogleAnalytics
    {
        $this->trackingMode = $trackingMode;

        return $this;
    }

    /**
     * @unreleased
     */
    public function administrator(string|null $administrator): GoogleAnalytics
    {
        $this->administrator = $administrator;

        return $this;
    }

}
