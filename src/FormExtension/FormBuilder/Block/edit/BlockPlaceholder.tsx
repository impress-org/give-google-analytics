import { __ } from "@wordpress/i18n";
import Icon from "./Icon";

export default function BlockPlaceholder() {
    return (
        <div
            style={{
                padding: "30px 20px",
                display: "flex",
                gap: ".75rem",
                fontSize: "1rem",
                border: " 1px dashed var(--givewp-gray-100)",
                borderRadius: "5px",
                backgroundColor: "var(--givewp-gray-10)",
            }}
        >
            <Icon />
            {__(
                "Google Analytics 4 is enabled and tracking data for this form."
            )}
        </div>
    );
}
