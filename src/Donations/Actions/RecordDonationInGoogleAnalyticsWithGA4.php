<?php

namespace GiveGoogleAnalytics\Donations\Actions;

use Give\Donations\Models\Donation;
use Give\Donations\Models\DonationNote;
use Give\Donations\ValueObjects\DonationStatus;
use Give_Payment;
use GiveGoogleAnalytics\Donations\Repositories\DonationRepository;
use GiveGoogleAnalytics\GoogleAnalytics\GA4\Client;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

/**
 * @since 2.0.0
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
     * @since 2.0.0
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
     * @since 2.0.0
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

        if ($donation = Donation::find($donationId)) {
            $this->sendEvent($donation);
        }
    }

    /**
     * This function triggers Google Analytic event for renewal payment.
     *
     * @since 2.1.0 use the new donation type property to identify renewals
     * @since 2.0.0
     */
    public function handleRenewal(Give_Payment $givePayment)
    {
        if (
            !$this->settingRepository->canSendEvent(TrackingMode::GOOGLE_ANALYTICS_4) ||
            $this->donationRepository->isGoogleAnalyticEventSent($givePayment->ID)
        ) {
            return;
        }

        $donation = Donation::find($givePayment->ID);

        if ($donation !== null && $donation->type->isRenewal()) {
            $this->sendEvent($donation);
        }
    }

    /**
     * This function sends event data to Google analytics.
     *
     * @since 2.0.0
     *
     * @return void
     */
    private function sendEvent(Donation $donation)
    {
        try {
            $response = $this->client->postEvent(
                json_encode($this->getEventData($donation))
            );

            // Check if beacon sent successfully.
            if (!is_wp_error($response) || 204 === wp_remote_retrieve_response_code($response)) {
                $this->donationRepository->setGoogleAnalyticEventSent($donation->id);

                DonationNote::create([
                        'donationId' => $donation->id,
                        'content' => esc_html__(
                            'Google Analytics ecommerce tracking beacon sent.',
                            'give-google-analytics'
                        ),
                    ]
                );
            }
        } catch (\Exception $exception) {
        }
    }

    /**
     * @since 2.0.0
     */
    private function getEventData(Donation $donation): array
    {
        $eventData = [
            'client_id' => $this->getGoogleAnalyticsClientTrackingId($donation),
            'events' => [
                [
                    'name' => 'purchase',
                    'params' => [
                        'currency' => $donation->amount->getCurrency()->getCode(),
                        'value' => $donation->amount->formatToDecimal(),
                        'transaction_id' => $donation->id,
                        'engagement_time_msec' => 1,
                        'session_id' => $this->getGoogleAnalyticsClientSession($donation),
                        'items' => [
                            [
                                'item_id' => $donation->formId,
                                'item_name' => $donation->formTitle,
                                'item_brand' => 'Fundraising',
                                'affiliation' => $this->settingRepository->getTrackAffiliation(),
                                'item_category' => $this->settingRepository->getTrackCategory(),
                                'item_category2' => $donation->gatewayId,
                                'item_category3' => $this->getDonationTypeLabel($donation),
                                'item_list_name' => $this->settingRepository->getTrackListName(),
                                'price' => $donation->amount->formatToDecimal(),
                                'quantity' => 1,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        /**
         * Use this filter hooke to add additional data to Google Analytics purchase event.
         *
         * @since 2.0.0
         */
        return apply_filters('give_google_analytics_ga4_purchase_event_data', $eventData, $donation);
    }

    /**
     * This function returns donation type label.
     * This label used as product category which help to differentiate revenue in Google Analytics Dashboard.
     *
     * @since 2.1.0 use the new donation type property to identify renewals
     * @since 2.0.0
     */
    private function getDonationTypeLabel(Donation $donation): string
    {
        if ($donation->type->isRenewal()) {
            return 'Renewal';
        }

        if ($donation->type->isSubscription()) {
            return 'Subscription';
        }

        return 'One-Time';
    }

    /**
     * This function return Google Analytics client session key.
     *
     * @since 2.1.0 use the new donation type property to identify renewals
     * @since 2.0.0
     */
    private function getGoogleAnalyticsClientSession(Donation $donation): string
    {
        if ($donation->type->isRenewal()) {
            return $this->donationRepository
                ->getGoogleAnalyticsClientSession(give()->subscriptions->getInitialDonationId($donation->subscriptionId))
                ->gaSessionId;
        }

        return $this->donationRepository
            ->getGoogleAnalyticsClientSession($donation->id)
            ->gaSessionId;
    }

    /**
     * This function returns the Google Analytics client id which generates on frontend when donor process/view donation form or which website.
     *
     * @since 2.1.0 use the new donation type property to identify renewals
     * @since 2.0.0
     */
    private function getGoogleAnalyticsClientTrackingId(Donation $donation): string
    {
        if ($donation->type->isRenewal()) {
            return $this->donationRepository->getGoogleAnalyticsClientTrackingId(give()->subscriptions->getInitialDonationId($donation->subscriptionId));
        }

        return $this->donationRepository->getGoogleAnalyticsClientTrackingId($donation->id);
    }
}
