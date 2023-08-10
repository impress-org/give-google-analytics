<?php

namespace GiveGoogleAnalytics\FormExtension;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as ServiceProviderInterface;
use GiveGoogleAnalytics\FormExtension\Hooks\DonationFormBlockRender;
use GiveGoogleAnalytics\FormExtension\Hooks\DonationFormScriptsEnqueue;
use GiveGoogleAnalytics\FormExtension\Hooks\FormBuilderScriptsEnqueue;
use GiveGoogleAnalytics\FormExtension\Hooks\GoogleTagScripts;
use GiveGoogleAnalytics\FormExtension\Hooks\NewFormDefaultBlocks;

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
        Hooks::addAction('givewp_form_builder_new_form', NewFormDefaultBlocks::class);
        Hooks::addAction('givewp_form_builder_enqueue_scripts', FormBuilderScriptsEnqueue::class);
        Hooks::addAction('givewp_donation_form_enqueue_scripts', DonationFormScriptsEnqueue::class);
        Hooks::addFilter(
            'givewp_donation_form_block_render_givewp-google-analytics/google-analytics',
            DonationFormBlockRender::class,
            '__invoke',
            10,
            4
        );
    }
}
