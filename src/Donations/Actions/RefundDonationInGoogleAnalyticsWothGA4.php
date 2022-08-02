<?php

namespace GiveGoogleAnalytics\Donations\Actions;

use Give\Donations\Models\Donation;
use Give\Donations\Models\DonationNote;
use Give\Donations\ValueObjects\DonationStatus;
use GiveGoogleAnalytic\Addon\Repositories\DonationRepository;
use GiveGoogleAnalytic\Addon\Repositories\SettingRepository;
use GiveGoogleAnalytics\GA4\Client;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;

class RefundDonationInGoogleAnalyticsWothGA4
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
     *
     * @param int $donationId
     * @param string $newDonationStatus
     *
     * @return void
     */
    public function __invoke($donationId, $newDonationStatus)
    {
        if (
            !$this->settingRepository->canSendEvent(TrackingMode::GOOGLE_ANALYTICS_4) ||
            DonationStatus::REFUNDED !== $newDonationStatus
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
                json_encode([
                        'client_id' => $this->donationRepository->getGoogleAnalyticsClientTrackingId($donation->id),
                        'events' => [
                            [
                                'name' => 'refund',
                                'params' => [
                                    'currency' => $donation->amount->getCurrency(),
                                    'value' => $donation->amount->formatToDecimal(),
                                    'transaction_id' => $donation->id,
                                    'items' => [
                                        [
                                            'item_id' => $donation->formId,
                                            'item_name' => $donation->formTitle,
                                            'affiliation' => $this->settingRepository->getTrackAffiliation(),
                                            'item_category' => $this->settingRepository->getTrackCategory(),
                                            'item_category2' => 'Fundraising',
                                            'item_category3' => $donation->gatewayId,
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                )
            );

            // Check if beacon sent successfully.
            if (!is_wp_error($response) || 200 == wp_remote_retrieve_response_code($response)) {
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
        } catch (\Exception $exception) {
        }
    }
}
