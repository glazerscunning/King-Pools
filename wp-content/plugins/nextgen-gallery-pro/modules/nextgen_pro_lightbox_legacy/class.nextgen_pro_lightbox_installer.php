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

    function install_pro_lightbox()
    {
        $router = C_Router::get_instance();

        // Install or update the lightbox library
        $mapper = $this->get_registry()->get_utility('I_Lightbox_Library_Mapper');
        $lightbox = $mapper->find_by_name(NGG_PRO_LIGHTBOX);
        if (!$lightbox)
            $lightbox = new stdClass;

        // Set properties
        $lightbox->name	= NGG_PRO_LIGHTBOX;
        $this->set_attr($lightbox, 'title', __("NextGEN Pro Lightbox", 'nggallery'));
        $this->set_attr($lightbox, 'code', "class='nextgen_pro_lightbox' data-nplmodal-gallery-id='%PRO_LIGHTBOX_GALLERY_ID%'");
        $this->set_attr(
            $lightbox,
            'css_stylesheets',
            implode("\n", array(
                'photocrati-nextgen_pro_lightbox_legacy#style.css'
            ))
        );
        $this->set_attr(
            $lightbox,
            'styles',
            implode("\n", array(
                'photocrati-nextgen_pro_lightbox_legacy#style.css'
            ))
        );
        $this->set_attr(
            $lightbox,
            'scripts',
            implode("\n", array(
                'photocrati-nextgen_pro_lightbox_legacy#jquery.mobile_browsers.js',
                "photocrati-nextgen_pro_lightbox_legacy#nextgen_pro_lightbox.js"
            ))
        );
        $this->set_attr(
            $lightbox,
            'display_settings',
            array(
                'icon_color' => '',
                'icon_background' => '',
                'icon_background_enabled' => '0',
                'icon_background_rounded' => '1',
                'overlay_icon_color' => '',
                'sidebar_button_color' => '',
                'sidebar_button_background' => '',
                'carousel_text_color' => '',
                'background_color' => '',
                'carousel_background_color' => '',
                'sidebar_background_color' => '',
                'router_slug' => 'gallery',
                'transition_effect' => 'slide',
                'enable_routing' => '1',
                'enable_comments' => '1',
                'enable_sharing' => '1',
                'display_comments' => '0',
                'display_captions' => '0',
                'display_carousel' => '1',
                'transition_speed' => '0.4',
                'slideshow_speed' => '5',
                'style' => '',
                'touch_transition_effect' => 'slide',
                'image_pan' => '0',
                'interaction_pause' => '1',
                'image_crop' => 'false'
            )
        );

        $mapper->save($lightbox);
    }

	function install($reset=FALSE)
	{
        $this->install_pro_lightbox();
	}

    function uninstall_nextgen_pro_lightbox($hard = FALSE)
    {
        $mapper = $this->get_registry()->get_utility('I_Lightbox_Library_Mapper');
        if (($lightbox = $mapper->find_by_name(NGG_PRO_LIGHTBOX)))
            $mapper->destroy($lightbox);
    }
}
