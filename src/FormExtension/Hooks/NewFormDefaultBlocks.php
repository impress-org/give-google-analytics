<?php

namespace GiveGoogleAnalytics\FormExtension\Hooks;

use Give\DonationForms\Models\DonationForm;
use Give\Framework\Blocks\BlockModel;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

/**
 * Class NewFormDefaultBlocks
 *
 * @unreleased
 */
class NewFormDefaultBlocks
{
    /**
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
     * Add the Google Analytic to the default blocks for new forms.
     */
    public function __invoke(DonationForm $form)
    {
        $block = BlockModel::make([
            'name' => 'givewp-google-analytics/google-analytics',
            'attributes' => [
                'administrator' => is_user_logged_in() && current_user_can('administrator'),
                'trackingMode' => $this->settingRepository->canSendEvent(TrackingMode::GOOGLE_ANALYTICS_4),
                'trackingId' => $this->settingRepository->getGoogleAnalytics4WebStreamMeasurementId(),
                'affiliation' => $this->settingRepository->getTrackAffiliation(),
                'trackCategory' => $this->settingRepository->getTrackCategory(),
                'trackListName' => $this->settingRepository->getTrackListName()
            ]
        ]);

        $form->blocks->insertAfter('givewp/payment-gateways', $block);
    }
}


