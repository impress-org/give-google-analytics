<?php

namespace GiveGoogleAnalytics\FormExtension\DonationForm\Actions;

use Give\Framework\FieldsAPI\DonationForm as DonationFormNode;
use GiveGoogleAnalytics\FormExtension\DonationForm\Fields\GoogleAnalytics;
use GiveGoogleAnalytics\GoogleAnalytics\ValueObjects\TrackingMode;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

class ConvertGoogleAnalyticOptionsToField
{
    /**
     * @var SettingRepository
     */
    private $settingRepository;

    /**
     * @since 3.0
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }


    /**
     * Add Google Analytic field to the form.
     * @since 3.0
     */
    public function __invoke(DonationFormNode $form)
    {
        $canPrintScript = $this->canPrintScript();

        if ( ! $canPrintScript) {
            return;
        }

        $googleAnalyticsField = GoogleAnalytics::make('googleAnalytics')
            ->tap(function (GoogleAnalytics $googleAnalytics) {
                $this->setGlobalAttributes($googleAnalytics);
            });

        $lastSection = $form->all()[$form->count() - 1];

        $lastSection->append($googleAnalyticsField);
    }

    /**
     * @since 3.0
     */
    private function setGlobalAttributes(GoogleAnalytics $field)
    {
        $field
            ->trackingId($this->settingRepository->getGoogleAnalytics4WebStreamMeasurementId())
            ->affiliation($this->settingRepository->getTrackAffiliation())
            ->trackCategory($this->settingRepository->getTrackCategory())
            ->trackListName($this->settingRepository->getTrackListName());
    }

    /**
     * @since 3.0
     */
    private function canPrintScript(): bool
    {
        // Don't track site admins
        if (is_user_logged_in() && current_user_can('administrator')) {
            return false;
        }

        if ( ! $this->settingRepository->canSendEvent(TrackingMode::GOOGLE_ANALYTICS_4)) {
            return false;
        }

        return true;
    }
}

