<?php
namespace GiveGoogleAnalytics\FormExtension\DonationForm\Actions;

use Give\Framework\Blocks\BlockModel;
use Give\Framework\FieldsAPI\Exceptions\EmptyNameException;
use GiveGoogleAnalytics\FormExtension\DonationForm\Fields\GoogleAnalytics;

class ConvertGoogleAnalyticsBlockToFieldsApi
{
    /**
     * @unreleased
     *
     * @throws EmptyNameException
     */
    public function __invoke(BlockModel $block, int $formId)
    {
        return GoogleAnalytics::make('googleAnalytics')
            ->tap(function (GoogleAnalytics $googleAnalytics) use ($block) {
                $this->setPerFormAttributes($googleAnalytics, $block);
                return $googleAnalytics;
            });
    }

    /**
     * @unreleased
     *
     * @return void
     */
    private function setPerFormAttributes(GoogleAnalytics $field, BlockModel $block)
    {
        $field
            ->trackingId($block->getAttribute('trackingId'))
            ->affiliation($block->getAttribute('affiliation'))
            ->trackCategory($block->getAttribute('trackCategory'))
            ->trackListName($block->getAttribute('trackListName'))
            ->trackingMode($block->getAttribute('trackingMode'))
            ->administrator($block->getAttribute('administrator'));
    }
}
