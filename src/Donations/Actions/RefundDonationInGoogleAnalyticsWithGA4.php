<?php

namespace GiveGoogleAnalytics\Donations\Actions;

use Exception;
use Give\Donations\Models\Donation;
use Give\Donations\Models\DonationNote;
use Give\Donations\ValueObjects\DonationStatus;
use GiveGoogleAnalytics\Donations\Repositories\DonationRepository;
use GiveGoogleAnalytics\GoogleAnalytics\GA4\Client;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Log\Log;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

class RefundDonationInGoogleAnalyticsWithGA4
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
     * @since 2.1.0 use the new donation type property to identify renewals
     * @since 2.0.0
     *
     * @param int $donationId
     * @param string $newDonationStatus
     *
     * @return void
     */
    public function __invoke($donationId, $newDonationStatus, $oldDonationStatus)
    {
        if (
            DonationStatus::REFUNDED !== $newDonationStatus ||
            !$this->settingRepository->canSendEvent(TrackingMode::GOOGLE_ANALYTICS_4) ||
            !$this->settingRepository->canTrackRefunds()
        ) {
            return;
        }

        if (!($donation = Donation::find($donationId))) {
            return;
        }

        // Check if the DONATION beacon has been sent (successful "purchase").
        // If it hasn't then return false; only send refunds for donations tracked in GA.
        if (!$this->donationRepository->isGoogleAnalyticEventSent($donationId)) {
            return;
        }

        try {
            $response = $this->client->postEvent(
                json_encode(
                    $this->getEventData(
                        $donation,
                        $this->getGoogleAnalyticsClientTrackingId($donation, $donation->type->isRenewal()),
                        $this->getGoogleAnalyticsClientSession($donation, $donation->type->isRenewal())
                    )
                )
            );

            // Check if beacon sent successfully.
            if (!is_wp_error($response) || 204 === wp_remote_retrieve_response_code($response)) {
                $this->donationRepository->setGoogleAnalyticEventSent($donationId);

                DonationNote::create([
                        'donationId' => $donation->id,
                        'content' => esc_html__(
                            'Google Analytics donation refund tracking beacon sent.',
                            'give-google-analytics'
                        )
                    ]
                );
            }
        } catch (Exception $exception) {
            Log::error('Google Analytics refund beacon failed to send.', [
                'donationId' => $donationId,
                'exception' => $exception,
            ]);
        }
    }

    /**
     * @since 2.0.0
     */
    private function getEventData(
        Donation $donation,
        string $clientId,
        string $sessionId
    ): array {
        $eventData = [
            'client_id' => $clientId,
            'events' => [
                [
                    'name' => 'refund',
                    'params' => [
                        'currency' => $donation->amount->getCurrency()->getCode(),
                        'value' => $donation->amount->formatToDecimal(),
                        'transaction_id' => $donation->id,
                        'engagement_time_msec' => 1,
                        'session_id' => $sessionId,
                    ]
                ]
            ]
        ];

        /**
         * Use this filter hooke to add additional data to Google Analytics refund event.
         *
         * @since 2.0.0
         */
        return apply_filters('give_google_analytics_ga4_refund_event_data', $eventData, $donation);
    }

    /**
     * This function returns the Google Analytics client id which generates on frontend when donor process/view donation form or which website.
     *
     * @since 2.1.0 switch to new method for retrieving initial donation
     * @since 2.0.0
     */
    private function getGoogleAnalyticsClientTrackingId(Donation $donation, bool $isRenewal): string
    {
        if ($isRenewal) {
            return $this->donationRepository->getGoogleAnalyticsClientTrackingId(give()->subscriptions->getInitialDonationId($donation->subscriptionId));
        }

        return $this->donationRepository->getGoogleAnalyticsClientTrackingId($donation->id);
    }

    /**
     * This function return Google Analytics client session key.
     *
     * @since 2.1.0 switch to new method for retrieving initial donation
     * @since 2.0.0
     */
    private function getGoogleAnalyticsClientSession(Donation $donation, bool $isRenewal): string
    {
        if ($isRenewal) {
            return $this->donationRepository
                ->getGoogleAnalyticsClientSession(give()->subscriptions->getInitialDonationId($donation->subscriptionId))
                ->gaSessionId;
        }

        return $this->donationRepository
            ->getGoogleAnalyticsClientSession($donation->id)
            ->gaSessionId;
    }
}
