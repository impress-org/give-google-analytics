import { useEffect } from "react";
import { gtag, install as loadGoogleTag } from "ga-gtag";

interface GoogleAnalyticsFieldProps {
    trackingId: string;
    affiliation: string;
    trackCategory: string;
    trackListName: string;
    trackingMode: boolean;
    administrator: boolean;
}

export default function GoogleAnalyticsField({
    trackingId,
    affiliation,
    trackCategory,
    trackListName,
    trackingMode,
    administrator,
}: GoogleAnalyticsFieldProps) {
    const { useFormContext } = window.givewp.form.hooks;
    const { formTitle } = window.givewp.form.hooks.useDonationFormSettings();
    const shouldEnableTracking = !administrator || !trackingMode;

    const {
        formState: {
            defaultValues: { formId, amount, currency },
            isSubmitting,
        },
        getValues,
    } = useFormContext();

    useEffect(() => {
        if (shouldEnableTracking) {
            loadGoogleTag(trackingId);

            gtag("event", "page_view", {
                page_title: window.parent.document.title,
            });

            gtag("event", "view_item", {
                currency: currency,
                value: amount,
                items: [
                    {
                        item_id: formId,
                        item_name: formTitle,
                        item_brand: "Fundraising",
                        affiliation: affiliation,
                        item_category: trackCategory,
                        item_list_name: trackListName,
                    },
                ],
            });
        }
    }, []);

    useEffect(() => {
        if (isSubmitting) {
            const submittedValues = getValues();
            const { amount, currency, donationType, gatewayId } =
                submittedValues;

            gtag("event", "begin_checkout", {
                currency: currency,
                value: amount,
                items: [
                    {
                        item_id: formId,
                        item_name: formTitle,
                        item_brand: "Fundraising",
                        affiliation: affiliation,
                        item_category: trackCategory,
                        item_category2: gatewayId,
                        item_category3: donationType,
                        item_list_name: trackListName,
                        price: amount,
                        quantity: 1,
                    },
                ],
            });
        }
    }, [isSubmitting]);

    return <div id={"givewp-google-analytics-hidden-element"}>test</div>;
}
