<?php

class A_NextGen_Pro_Proofing_Form extends Mixin
{
    function _get_field_names()
    {
        $fields = $this->call_parent('_get_field_names');
        $fields[] = 'nextgen_pro_proofing_display';
        return $fields;
    }

    function _render_nextgen_pro_proofing_display_field($display_type)
    {
        return $this->_render_radio_field(
            $display_type,
            'ngg_proofing_display',
            __('Enable proofing?', 'nggallery'),
            isset($display_type->settings['ngg_proofing_display']) ? $display_type->settings['ngg_proofing_display'] : FALSE,
            __('Trigger buttons need to be enabled for proofing to work', 'nggallery')
        );
    }
}
