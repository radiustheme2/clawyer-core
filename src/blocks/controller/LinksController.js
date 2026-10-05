const {__} = wp.i18n;
import {SelectControl, PanelBody} from "@wordpress/components";
import {POST_LINK_TYPE, CLAWYER_IS_PRO} from "../../components/Constants";

function LinksController(props) {
    const {attributes, setAttributes} = props.data;
    //All attribute
    const {post_link_type, link_target, is_thumb_linked} = attributes;

    return (
        <PanelBody title={__('Links', 'clawyer-core')} initialOpen={false}>
            <SelectControl
                label={__("Post link type", "clawyer-core")}
                className="rttpg-control-field label-inline rttpg-expand"
                options={POST_LINK_TYPE}
                value={post_link_type}
                onChange={(post_link_type) => setAttributes({post_link_type})}
            />

            {CLAWYER_IS_PRO === 'rttpg-is-pro' &&
                <p className="rttpg-help">{__("NB. Please upgrade to pro for popup options", "clawyer-core")}</p>}

            {post_link_type === 'default' && (
                <SelectControl
                    label={__("Link Target", "clawyer-core")}
                    className="rttpg-control-field label-inline rttpg-expand"
                    options={[
                        {value: '_self', label: __('Same Window', 'clawyer-core')},
                        {value: '_blank', label: __('New Window', 'clawyer-core')}
                    ]}
                    value={link_target}
                    onChange={(link_target) => setAttributes({link_target})}
                />)}

            <SelectControl
                label={__("Thumbnail Link", "clawyer-core")}
                className="rttpg-control-field label-inline rttpg-expand"
                options={[{value: 'yes', label: __('Yes', 'clawyer-core')}, {
                    value: 'no',
                    label: __('No', 'clawyer-core')
                }]}
                value={is_thumb_linked}
                onChange={(is_thumb_linked) => setAttributes({is_thumb_linked})}
            />
        </PanelBody>
    );
}

export default LinksController;
