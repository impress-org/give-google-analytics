<?php

namespace GiveGoogleAnalytics\FormExtension;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as ServiceProviderInterface;
use GiveGoogleAnalytics\FormExtension\DonationForm\Actions\ConvertGoogleAnalyticOptionsToField;
use GiveGoogleAnalytics\FormExtension\Hooks\DonationFormScriptsEnqueue;
use GiveGoogleAnalytics\FormExtension\Hooks\GoogleTagScripts;

/**
 * Class ServiceProvider
 *
 * @package GiveGoogleAnalytics
 * @unreleased
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @unreleased
     */
    public function register()
    {
    }

    /**
     * @unreleased
     */
    public function boot()
    {
        // Form Extension hooks
        Hooks::addAction(
            'givewp_donation_form_schema',
            ConvertGoogleAnalyticOptionsToField::class,
            '__invoke',
            10,
            2
        );

        Hooks::addAction('givewp_donation_form_enqueue_scripts', DonationFormScriptsEnqueue::class);
    }
}
