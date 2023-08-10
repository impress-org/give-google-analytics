import type { BlockConfiguration } from "@wordpress/blocks";
import googleAnalytics from "./Block";

declare global {
    interface Window {
        gtag: any;
        givewp: {
            form: {
                blocks: {
                    register: (
                        name: string,
                        settings: BlockConfiguration
                    ) => void;
                };
                slots: any;
            };
        };
    }
}

// @ts-ignore
window.givewp.form.blocks.register(
    googleAnalytics.name,
    googleAnalytics.settings
);
