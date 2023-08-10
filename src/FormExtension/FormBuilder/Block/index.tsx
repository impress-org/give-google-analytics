import type { BlockConfiguration } from "@wordpress/blocks";
import metadata from "./metadata";

const { name } = metadata;

const settings = {
    ...metadata,
    save,
    edit,
};

const edit = () => null;
const save = () => null;

const googleAnalytics: { name: string; settings: BlockConfiguration } = {
    name,
    settings,
};

export default googleAnalytics;
