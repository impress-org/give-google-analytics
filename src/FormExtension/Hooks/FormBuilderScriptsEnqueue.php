<?php

namespace GiveGoogleAnalytics\FormExtension\Hooks;

/**
 * Class FormBuilderScriptsEnqueue
 *
 * @unreleased
 */
class FormBuilderScriptsEnqueue
{
    /**
     * Enqueues the Google Analytic form builder extension scripts and styles.
     *
     * @return void
     */
    public function __invoke()
    {
        $scriptAsset = require GIVE_GOOGLE_ANALYTICS_DIR . '/build/googleAnalyticsFormBuilderExtension.asset.php';

        wp_enqueue_script(
            'givewp-form-extension-google-analytics',
            GIVE_GOOGLE_ANALYTICS_URL . 'build/googleAnalyticsFormBuilderExtension.js',
            $scriptAsset['dependencies'],
            false,
            true
        );

        wp_localize_script(
            'givewp-form-extension-google-analytics',
            'GiveGoogleAnalyticsBlock',
            ['test'=>'test']
        );
    }
}
