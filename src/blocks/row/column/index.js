import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import Edit from "./edit";
import Save from "./save";
import attributes from "./attributes";

registerBlockType("rttpg/column", {
	apiVersion: 1,
	title: __("Column", "clawyer"),
	description: "Advanced row columns",
	icon: <img src={rtParams.plugin_url + "/assets/images/gutenberg/column.svg"} alt={__("TPG Column")}/>,
	parent: ['rttpg/row'],
	category: "rttpg-clawyer",
	keywords: [
		__("row", "clawyer"),
		__("column", "clawyer"),
		__("clawyer", "clawyer")
	],
	attributes: attributes,
	save: Save,
	edit: Edit
});