<?php

class A_NextGen_Pro_Proofing_Trigger_Element extends Mixin
{
    function render_object()
    {
        $root_element = $this->call_parent('render_object');
        if (($displayed_gallery = $this->object->get_param('displayed_gallery'))
        &&  !empty($displayed_gallery->display_settings['ngg_proofing_display']))
        {
            foreach ($root_element->find('nextgen_gallery.gallery_container', TRUE) as $container) {
                $div = '<div class="ngg-pro-proofing-trigger-link" data-gallery-id="' . $displayed_gallery->transient_id . '"><a href="#" class="ngg_pro_proofing_btn ngg_pro_btn">';
                $div .= C_NextGen_Settings::get_instance()->proofing_trigger_text;
                $div .= '</a></div>';
                $container->append($div);
            }
        }

        return $root_element;
    }
}