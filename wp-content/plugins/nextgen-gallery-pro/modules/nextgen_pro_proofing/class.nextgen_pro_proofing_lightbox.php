<?php

class C_NextGen_Pro_Proofing_Lightbox
{
    /**
     * lightbox_overrides.js holds all of the pro-lightbox related proofing features
     */
    function enqueue_static_resources()
    {
        $router = C_Router::get_instance();
        wp_enqueue_script('ngg-pro-lightbox-proofing-js', $router->get_static_url('photocrati-nextgen_pro_proofing#lightbox_overrides.js'));
        wp_localize_script(
            'ngg-pro-lightbox-proofing-js',
            'ngg_proofing_settings',
            array(
                'active_color' => C_NextGen_Settings::get_instance()->proofing_lightbox_active_color
            )
        );
    }

    /**
     * Required by the interface but there's just nothing to render
     *
     * @return string
     */
    function render()
    {
        return '';
    }
}