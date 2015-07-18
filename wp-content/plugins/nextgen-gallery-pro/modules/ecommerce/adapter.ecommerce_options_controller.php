<?php

class A_Ecommerce_Options_Controller extends Mixin
{
	function get_page_title()
	{
		return __('Ecommerce Options', 'nggallery');
	}

	function get_page_heading()
	{
		return $this->get_page_title();
	}

	function get_required_permission()
	{
		return 'NextGEN Change options';
	}

    function save_action()
    {
        if (($updates = $this->param('ecommerce'))){
            $settings = C_NextGen_Settings::get_instance();
            foreach ($updates as $key => $value) {
                $key = "ecommerce_{$key}";
                $settings->$key = $value;
            }
            $settings->save();
        }
    }
}