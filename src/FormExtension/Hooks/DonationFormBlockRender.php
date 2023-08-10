<?php

namespace GiveGoogleAnalytics\FormExtension\Hooks;

use Give\Framework\Blocks\BlockModel;
use Give\Framework\FieldsAPI\Contracts\Node;
use Give\Framework\FieldsAPI\Exceptions\EmptyNameException;
use GiveGoogleAnalytics\FormExtension\DonationForm\Actions\ConvertGoogleAnalyticsBlockToFieldsApi;
use GiveGoogleAnalytics\FormExtension\DonationForm\Fields\GoogleAnalytics;

/**
 * Class DonationFormBlockRender
 *
 * @unreleased
 */
class DonationFormBlockRender
{
    /**
     * Renders the Google Analytic field for the donation form block.
     *
     * @param Node|null $node The node instance.
     * @param BlockModel $block The block model instance.
     * @param int $blockIndex The index of the block.
     *
     * @return GoogleAnalytics The Google Analytic field instance.
     * @throws EmptyNameException
     */
    public function __invoke($node, BlockModel $block, int $blockIndex, int $formId)
    {
        return (new ConvertGoogleAnalyticsBlockToFieldsApi())($block, $formId);
    }
}
