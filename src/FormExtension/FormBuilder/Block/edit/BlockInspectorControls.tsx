import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, PanelRow, SelectControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

import GlobalSettingsLink from "./GlobalSettingsLink";

export default function BlockInspectorControls({ attributes }) {
    const { useGlobalSettings } = attributes;

    return (
        <InspectorControls>
            <PanelBody
                title={__("Field Settings", "give-fee-recovery")}
                initialOpen={true}
            >
                <PanelRow>
                    <SelectControl
                        label={__("Google Analytics 4", "give")}
                        onChange={null}
                        value={useGlobalSettings}
                        options={[
                            { label: __("Global", "give"), value: "true" },
                        ]}
                    />
                </PanelRow>
                <GlobalSettingsLink
                    href={
                        "/wp-admin/edit.php?post_type=give_forms&page=give-settings&tab=general&section=google-analytics"
                    }
                />
            </PanelBody>
        </InspectorControls>
    );
}
