import { gtag } from "ga-gtag";

export function trackPageView() {
    gtag("event", "page_view", {
        page_title: window.parent.document.title,
    });
}

export function trackViewItem(
    formId: string,
    formTitle: string,
    amount: number,
    currency: string,
    affiliation: string,
    trackCategory: string,
    trackListName: string
) {
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

export function trackBeginCheckout(
    submittedValues,
    formId: string,
    formTitle: string,
    affiliation: string,
    trackCategory: string,
    trackListName: string
) {
    const { amount, currency, donationType, gatewayId } = submittedValues;

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
