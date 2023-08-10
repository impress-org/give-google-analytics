import type {BlockConfiguration} from '@wordpress/blocks';
import {__} from '@wordpress/i18n';

const metadata: BlockConfiguration = {
    name: 'givewp-google-analytics/google-analytics',
    title: __('Google Analytics', 'give-google-analytics'),
    description: __('Hidden field to manage google analytics.', 'give-google-analytics'),
    category: 'addons',
    supports: {
        multiple: false,
    },
    attributes: {
        trackingId: {
            type: 'string',
            default: '',
        },
        affiliation: {
            type: 'string',
            default: '',
        },
        trackingCategory: {
            type: 'string',
            default: '',
        },
        trackingListName: {
            type: 'string',
            default: '',
        },
    },
};

export default metadata;
