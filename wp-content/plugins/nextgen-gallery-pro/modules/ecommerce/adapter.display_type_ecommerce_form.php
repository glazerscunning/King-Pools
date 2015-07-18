<?php

class A_Display_Type_Ecommerce_Form extends Mixin
{
    function _get_field_names()
    {
        $fields = $this->call_parent('_get_field_names');

        // Add an option to enable e-commerce only if there are pricelists created
        if (C_Pricelist_Mapper::get_instance()->count() > 0) {
            if (is_array($fields)) {
                $fields[] = 'is_ecommerce_enabled';
            }
        }

        return $fields;
    }

    function _render_is_ecommerce_enabled_field($display_type)
    {
        $output = $this->object->_render_radio_field(
            $display_type,
            'is_ecommerce_enabled',
            __('Enable ecommerce?', 'nggallery'),
            isset($display_type->settings['is_ecommerce_enabled']) ? $display_type->settings['is_ecommerce_enabled'] : FALSE
        );

        // Add instructions link
        $label = esc_attr(__('see instructions', 'nggallery'));
        $href = esc_attr(admin_url('/admin.php?page=ngg-ecommerce-instructions-page'));
        if (($index = strpos($output, '</label>')) !== FALSE) {
            $start  = substr($output, 0, $index);
            $end    = substr($output, $index);
            $output = $start."<em style='font-size: smaller; display: block; font-style: italic'><a href='{$href}' target='_blank'>({$label})</a></em>".$end;

        }

        return $output;
    }
}
