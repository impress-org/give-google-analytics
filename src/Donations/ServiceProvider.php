<?php

namespace GiveGoogleAnalytics\Donations;

use Give\Helpers\Hooks;
use GiveGoogleAnalytics\Donations\Actions\StoreDonorGoogleAnalyticsData;

/**
 * @unreleased
 */
class ServiceProvider implements \Give\ServiceProviders\ServiceProvider
{

    /**
     * @inerhitDoc
     * @unreleased
     * @return void
     */
    public function register()
    {
    }

    /**
     * @unreleased
     * @return void
     */
    public function boot()
    {
        Hooks::addAction('give_insert_payment', StoreDonorGoogleAnalyticsData::class);
    }
}
