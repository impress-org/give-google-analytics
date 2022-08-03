<?php

namespace GiveGoogleAnalytics\Donations;

use Give\Helpers\Hooks;
use GiveGoogleAnalytics\Donations\Actions\RecordDonationInGoogleAnalyticsWithGA4;
use GiveGoogleAnalytics\Donations\Actions\RefundDonationInGoogleAnalyticsWithGA4;
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

        Hooks::addAction(
            'wp_footer',
            RecordGoogleEventWithGA4OnFrontend::class,
            '__invoke',
            99999
        );

        Hooks::addAction(
            'give_embed_footer',
            RecordGoogleEventWithGA4OnFrontend::class,
            '__invoke',
            99999
        );

        Hooks::addAction(
            'give_update_payment_status',
            RecordDonationInGoogleAnalyticsWithGA4::class,
            '__invoke',
            110,
            2
        );

        Hooks::addAction(
            'give_update_payment_status',
            RefundDonationInGoogleAnalyticsWithGA4::class,
            '__invoke',
            10,
            2
        );
    }
}
