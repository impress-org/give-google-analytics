<?php

namespace GiveGoogleAnalytics\Donations\Actions;

use Give\Donations\Models\Donation;
use Give\Donations\Models\DonationNote;
use Give\Donations\ValueObjects\DonationStatus;
use GiveGoogleAnalytics\Addon\Repositories\DonationRepository;
use GiveGoogleAnalytics\Addon\Repositories\SettingRepository;
use GiveGoogleAnalytics\GoogleAnalytics\GA4\Client;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;

/**
 * @unreleased
 */
class RecordDonationInGoogleAnalyticsWithGA4
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var DonationRepository
     */
    private $donationRepository;
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @unreleased
     */
    public function __construct(
        SettingRepository $settingRepository,
        DonationRepository $donationRepository,
        Client $client
    ) {
        $this->settingRepository = $settingRepository;
        $this->donationRepository = $donationRepository;
        $this->client = $client;
    }

    /**
     * @unreleased
     * @return void
     */
    public function __invoke($donationId, $newDonationStatus)
    {
        if (
            DonationStatus::COMPLETE !== $newDonationStatus ||
            !$this->settingRepository->canSendEvent(TrackingMode::GOOGLE_ANALYTICS_4) ||
            $this->donationRepository->isGoogleAnalyticEventSent($donationId)
        ) {
            return;
        }

        if (!($donation = Donation::find($donationId))) {
            return;
        }

        try {
            $response = $this->client->postEvent(
                json_encode($this->getEventData($donation))
            );

            // Check if beacon sent successfully.
            if (!is_wp_error($response) || 204 === wp_remote_retrieve_response_code($response)) {
                $this->donationRepository->setGoogleAnalyticEventSent($donationId);

                DonationNote::create([
                        'donationId' => $donation->id,
                        'content' => esc_html__(
                            'Google Analytics ecommerce tracking beacon sent.',
                            'give-google-analytics'
                        )
                    ]
                );
            }
        } catch (\Exception $exception) {
        }
    }

    /**
     * @unreleased
     */
    private function getEventData(Donation $donation): array
    {
        $eventData = [
            'client_id' => $this->donationRepository->getGoogleAnalyticsClientTrackingId($donation->id),
            'events' => [
                [
                    'name' => 'purchase',
                    'params' => [
                        'currency' => $donation->amount->getCurrency()->getCode(),
                        'value' => $donation->amount->formatToDecimal(),
                        'transaction_id' => $donation->id,
                        'engagement_time_msec' => 100,
                        'session_id' => $this->donationRepository
                            ->getGoogleAnalyticsClientSession($donation->id)->gaSessionId,
                        'items' => [
                            [
                                'item_id' => $donation->formId,
                                'item_name' => $donation->formTitle,
                                'item_brand' => 'Fundraising',
                                'affiliation' => $this->settingRepository->getTrackAffiliation(),
                                'item_category' => $this->settingRepository->getTrackCategory(),
                                'item_category2' => $donation->gatewayId,
                                'item_list_name' => $this->settingRepository->getTrackListName(),
                                'price' => $donation->amount->formatToDecimal(),
                                'quantity' => 1
                            ]
                        ]
                    ]
                ]
            ]
        ];

        /**
         * Use this filter hooke to add additional data to Google Analytics purchase event.
         *
         * @unreleased
         */
        return apply_filters('give_google_analytics_ga4_purchase_event_data', $eventData, $donation);
    }
}
