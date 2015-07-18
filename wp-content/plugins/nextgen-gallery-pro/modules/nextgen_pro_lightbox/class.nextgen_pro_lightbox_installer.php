<?php

if (class_exists('C_NextGen_Pro_Lightbox_Installer')) return;
class C_NextGen_Pro_Lightbox_Installer
{
	function get_registry()
	{
		return C_Component_Registry::get_instance();
	}

    function set_attr(&$obj, $key, $val)
    {
        if (!isset($obj->$key))
            $obj->$key = $val;
    }

    function install_pro_lightbox_settings(C_Photocrati_Settings_Manager $settings, $reset=FALSE)
    {
        $defaults   = array(
            'background_color'          =>  1,
            'enable_routing'            =>  1,
            'icon_color'                =>  '',
            'icon_background'           =>  '',
            'icon_background_enabled'   =>  0,
            'icon_background_rounded'   =>  1,
            'overlay_icon_color'        =>  '',
            'sidebar_button_color'      =>  '',
            'sidebar_button_background' =>  '',
            'router_slug'               =>  'gallery',
            'carousel_background_color' =>  '',
            'carousel_text_color'       =>  '',
            'enable_comments'           =>  1,
            'enable_sharing'            =>  1,
            'display_comments'          =>  0,
            'display_captions'          =>  0,
            'display_carousel'          =>  1,
            'image_crop'                =>  'false', // it is important that this not be a number zero
            'image_pan'                 =>  0,
            'interaction_pause'         =>  1,
            'sidebar_background_color'  =>  '',
            'slideshow_speed'           =>  5,
            'style'                     =>  '',
            'touch_transition_effect'   =>  'slide',
            'transition_effect'         =>  'slide',
            'transition_speed'          =>  0.4
        );

        // Create settings array
        if (!$settings->exists('ngg_pro_lightbox')) $settings->set('ngg_pro_lightbox', array());
        $ngg_pro_lightbox = $settings->get('ngg_pro_lightbox');

        // Need migration logic from custom post type
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = 'lightbox_library' AND post_title = %s", NGG_PRO_LIGHTBOX));
        if ($row) {
            $row->post_content = M_DataMapper::unserialize($row->post_content);
            $ngg_pro_lightbox = $row->post_content['display_settings'];
            @wp_delete_post($row->ID, TRUE);
        }

        // Set defaults
        foreach ($defaults as $key => $value) if (!array_key_exists($key, $ngg_pro_lightbox)) {
            $ngg_pro_lightbox[$key] = $value;
        }

        // Save the data
        $settings->set('ngg_pro_lightbox', $ngg_pro_lightbox);


    }

	function install($reset=FALSE)
	{
        $this->install_pro_lightbox_settings(C_NextGen_Settings::get_instance());
	}
}
