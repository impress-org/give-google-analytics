import BlockPlaceholder from "./BlockPlaceholder";
import BlockInspectorControls from "./BlockInspectorControls";

export default function index({ attributes }) {
    return (
        <>
            <BlockPlaceholder />
            <BlockInspectorControls attributes={attributes} />
        </>
    );
}
