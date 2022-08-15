<?php

namespace GiveGoogleAnalytics\GoogleAnalytics\GA4;

use Give\Framework\Exceptions\Primitives\Exception;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

/**
 * This class provides the Google Analytics client which uses to post event data.
 *
 * @unreleased
 */
class Client
{
    /**
     * @unreleased
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @unreleased
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * This function uses to send event to Google Analytics 4
     * @see https://developers.google.com/analytics/devguides/collection/protocol/ga4/sending-events?client_type=gtag
     *
     * @return array|\WP_Error
     * @throws Exception
     */
    public function postEvent($jsonData)
    {
        $googleCollectEventUrl = sprintf(
            'https://www.google-analytics.com/mp/collect?measurement_id=%1$s&api_secret=%2$s',
            $this->settingRepository->getGoogleAnalytics4WebStreamMeasurementId(),
            $this->settingRepository->getGoogleAnalytics4WebStreamMeasurementProtocolApiSecret()
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

        return $response;
    }
}
