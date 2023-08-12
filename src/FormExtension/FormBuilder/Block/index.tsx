import type { BlockConfiguration } from "@wordpress/blocks";
import metadata from "./metadata";
import index from "./edit";

const { name } = metadata;
const save = () => null;

const settings = {
    ...metadata,
    save,
    edit: index,
};

const googleAnalytics: { name: string; settings: BlockConfiguration } = {
    name,
    settings,
};

export default googleAnalytics;
