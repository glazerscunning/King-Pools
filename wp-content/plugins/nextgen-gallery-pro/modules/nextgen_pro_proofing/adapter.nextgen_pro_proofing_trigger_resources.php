<?php
/**
 * Class A_NextGen_Pro_Proofing_Trigger_Resources
 * Provides frontend resources for image proofing
 */
class A_NextGen_Pro_Proofing_Trigger_Resources extends Mixin
{
    protected $run_once = FALSE;

    function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
        return $this->enqueue_nextgen_proofing_trigger_buttons_resources($displayed_gallery);
    }

    function enqueue_nextgen_proofing_trigger_buttons_resources($displayed_gallery = FALSE)
    {
        $retval = FALSE;

        if (!$this->run_once
        &&  !empty($displayed_gallery)
        &&  !empty($displayed_gallery->display_settings['ngg_proofing_display']))
        {
            $router = C_Component_Registry::get_instance()->get_utility('I_Router');

            wp_enqueue_script(
                'jquery-placeholder',
                $router->get_static_url('photocrati-nextgen_admin#jquery.placeholder.min.js'),
                'jquery',
                FALSE,
                FALSE
            );

            wp_enqueue_script(
                'ngg-pro-proofing-script',
                $router->get_static_url('photocrati-nextgen_pro_proofing#nextgen_pro-proofing.js'),
                array('jquery', 'underscore', 'jquery-placeholder'),
                FALSE,
                FALSE
            );

            $deps = false;

            if (wp_script_is('ngg-trigger-buttons', 'registered'))
                $deps = array('ngg-trigger-buttons');

            wp_enqueue_style(
                'ngg-pro-proofing-style',
                $router->get_static_url('photocrati-nextgen_pro_proofing#nextgen_pro-proofing.css'),
                $deps
            );

            $this->run_once = TRUE;
        }

        return $retval;
    }
}

