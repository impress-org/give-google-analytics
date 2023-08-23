<?php

namespace GiveGoogleAnalytics\FormExtension\Hooks;

/**
 * Class DonationFormScriptsEnqueue
 *
 * @since 3.0
 */
class DonationFormScriptsEnqueue
{
    /**
     * Enqueues the Google Analytic donation form extension scripts and styles.
     *
     * @since 3.0
     * @return void
     */
    public function __invoke()
    {
        $scriptAsset = require GIVE_GOOGLE_ANALYTICS_DIR . '/build/googleAnalyticsDonationFormExtension.asset.php';

        wp_enqueue_script(
            'givewp-form-extension-google-analytics',
            GIVE_GOOGLE_ANALYTICS_URL . 'build/googleAnalyticsDonationFormExtension.js',
            $scriptAsset['dependencies'],
            false,
            true
        );
    }
}
