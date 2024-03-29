import { useEffect } from "react";
import { install as loadGoogleTag } from "ga-gtag";
import { trackBeginCheckout, trackPageView, trackViewItem } from "./Utilities";

interface GoogleAnalyticsFieldProps {
    trackingId: string;
    affiliation: string;
    trackCategory: string;
    trackListName: string;
}

export default function GoogleAnalyticsField({
    trackingId,
    affiliation,
    trackCategory,
    trackListName,
}: GoogleAnalyticsFieldProps) {
    const { useFormContext } = window.givewp.form.hooks;
    const { formTitle } = window.givewp.form.hooks.useDonationFormSettings();

    const {
        formState: {
            defaultValues: { formId, amount, currency },
            isSubmitting,
        },
        getValues,
    } = useFormContext();

    useEffect(() => {
        loadGoogleTag(trackingId);
        trackPageView();

        trackViewItem(
            formId,
            formTitle,
            amount,
            currency,
            affiliation,
            trackCategory,
            trackListName
        );
    }, []);

    useEffect(() => {
        if (isSubmitting) {
            const submittedValues = getValues();
            trackBeginCheckout(
                submittedValues,
                formId,
                formTitle,
                affiliation,
                trackCategory,
                trackListName
            );
        }
    }, [isSubmitting, getValues]);

    return <div id={"givewp-google-analytics-hidden-element"} />;
}
