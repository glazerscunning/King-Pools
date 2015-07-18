<?php

class A_NplModal_Ecommerce_Overrides extends Mixin
{
    function enqueue_lightbox_resources($displayed_gallery)
    {
        $settings = C_NextGen_Settings::get_instance();
        if ($settings->thumbEffect == NGG_PRO_LIGHTBOX) {
            wp_enqueue_script('ngg_nplmodal_ecommerce', $this->get_static_url('photocrati-nextgen_pro_ecommerce#nplmodal_overrides.js'));
        }

        $this->call_parent('enqueue_lightbox_resources', $displayed_gallery);
    }
}