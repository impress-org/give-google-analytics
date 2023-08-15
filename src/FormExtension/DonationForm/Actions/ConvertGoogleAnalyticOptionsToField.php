<?php

namespace GiveGoogleAnalytics\FormExtension\DonationForm\Actions;

use Give\Framework\FieldsAPI\DonationForm as DonationFormNode;
use GiveGoogleAnalytics\Donations\Actions\RecordDonationInGoogleAnalyticsWithGA4;
use GiveGoogleAnalytics\FormExtension\DonationForm\Fields\GoogleAnalytics;
use GiveGoogleAnalytics\Settings\Repositories\SettingRepository;

class ConvertGoogleAnalyticOptionsToField
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
     * Add the Google Analytic field to the form.
     */
    public function __invoke(DonationFormNode $form)
    {
        $canPrintScript = RecordDonationInGoogleAnalyticsWithGA4::class->canPrintScript();

        if ( ! $canPrintScript) {
            return false;
        }

        $googleAnalyticsField = GoogleAnalytics::make('googleAnalytics')
            ->tap(function (GoogleAnalytics $googleAnalytics) {
                $this->setGlobalAttributes($googleAnalytics);
            });

        $lastSection = $form->all()[$form->count() - 1];

        $lastSection->append($googleAnalyticsField);
    }

    private function setGlobalAttributes(GoogleAnalytics $field)
    {
        $field
            ->trackingId($this->settingRepository->getGoogleAnalytics4WebStreamMeasurementId())
            ->affiliation($this->settingRepository->getTrackAffiliation())
            ->trackCategory($this->settingRepository->getTrackCategory())
            ->trackListName($this->settingRepository->getTrackListName());
    }
}

