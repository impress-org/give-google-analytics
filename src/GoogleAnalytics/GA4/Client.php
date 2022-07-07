<?php

namespace GiveGoogleAnalytics\GA4;

use Give\Framework\Exceptions\Primitives\Exception;

/**
 * This class provides the Google Analytics client which uses to post event data.
 *
 * @unreleased
 */
class Client
{
    /**
     * This function uses to send event to Google Analytics 4
     * @see https://developers.google.com/analytics/devguides/collection/protocol/ga4/sending-events?client_type=gtag
     *
     * @return void
     * @throws Exception
     */
    public static function postEvent($jsonData)
    {
        $googleCollectEventUrl = sprintf(
            'https://www.google-analytics.com/mp/collect?measurement_id=G-%1$s&api_secret=%2$s',
            give_get_option('google_analytics_ga4_measurement_id'),
            give_get_option('google_analytics_ga4_measurement_protocol_api_secret')
        );

        /* @var \WP_Error|array $response */
        $response = wp_remote_post(
            $googleCollectEventUrl,
            [
                'body' => $jsonData,
                'data_format' => 'body'
            ]
        );

        if (is_wp_error($response)) {
            throw new Exception(
                sprintf(
                    'Google Analytic post event request failed. Details: %1$s',
                    $response->get_error_message()
                )
            );
        }
    }
}
