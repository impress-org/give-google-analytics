import type { BlockConfiguration } from "@wordpress/blocks";
import metadata from "./metadata";

const { name } = metadata;
const edit = () => null;
const save = () => null;

const settings = {
    ...metadata,
    save,
    edit,
};

const googleAnalytics: { name: string; settings: BlockConfiguration } = {
    name,
    settings,
};

export default googleAnalytics;
