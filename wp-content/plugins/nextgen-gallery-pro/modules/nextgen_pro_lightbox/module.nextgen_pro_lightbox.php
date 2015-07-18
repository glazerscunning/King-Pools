<?php
/*
{
    Module: photocrati-nextgen_pro_lightbox,
    Depends: { photocrati-lightbox }
}
 */

define('NGG_PRO_LIGHTBOX', 'photocrati-nextgen_pro_lightbox');
define('NGG_PRO_LIGHTBOX_TRIGGER', NGG_PRO_LIGHTBOX);
define('NGG_PRO_LIGHTBOX_COMMENT_TRIGGER', 'photocrati-nextgen_pro_lightbox_comments');

class M_NextGen_Pro_Lightbox extends C_Base_Module
{
    function define($context=FALSE)
    {
        parent::define(
            'photocrati-nextgen_pro_lightbox',
            'NextGEN Pro Lightbox',
            'Provides a lightbox with integrated commenting, social sharing, and e-commerce functionality',
            '0.26',
            'http://www.nextgen-gallery.com',
            'Photocrati Media',
            'http://www.photocrati.com',
            $context
        );

        include_once('class.nextgen_pro_lightbox_installer.php');
        C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Lightbox_Installer');
    }

    function initialize()
    {
        parent::initialize();

        if (!is_admin()) {

            // Add triggers
            $triggers = C_Displayed_Gallery_Trigger_Manager::get_instance();
            $triggers->add(NGG_PRO_LIGHTBOX_TRIGGER,           'C_NextGen_Pro_Lightbox_Trigger');
            $triggers->add(NGG_PRO_LIGHTBOX_COMMENT_TRIGGER,   'C_NextGen_Pro_Lightbox_Trigger');
        }
    }

    function _register_adapters()
    {
        if (!is_admin()) {
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Lightbox_Effect_Code');
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            // add additional settings to each supported display type
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_THUMBNAILS);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_SLIDESHOW);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_IMAGEBROWSER);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_BASIC_SINGLEPIC);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_SLIDESHOW);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_HORIZONTAL_FILMSTRIP);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_THUMBNAIL_GRID);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_BLOG_GALLERY);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_FILM);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Triggers_Form', NGG_PRO_MASONRY);

            // lightbox settings form
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Form', NGG_PRO_LIGHTBOX);
        }
    }

    function _register_utilities()
    {
        if (!is_admin()) {
            $this->get_registry()->add_utility('I_NextGen_Pro_Lightbox_Controller', 'C_NextGen_Pro_Lightbox_Controller');
            $this->get_registry()->add_utility('I_OpenGraph_Controller', 'C_OpenGraph_Controller');
        }
    }

    function _register_hooks()
    {
        add_action('admin_init', array(&$this, 'register_forms'));
        add_action('ngg_registered_default_lightboxes', array(&$this, 'register_lightbox'));
        if (!is_admin()) add_action('init', array(&$this, 'define_routes'), 2);
    }

    function define_routes()
    {
        $router = C_Router::get_instance();

        $app = $router->create_app('/nextgen-pro-lightbox-gallery');
        $app->rewrite("/{id}", "/id--{id}");
        $app->route('/', 'I_NextGen_Pro_Lightbox_Controller#index');

        $app = $router->create_app('/nextgen-pro-lightbox-load-images');
        $app->rewrite("/{id}", "/id--{id}");
        $app->route('/', 'I_NextGen_Pro_Lightbox_Controller#load_images');

        $app = $router->create_app('/nextgen-share');
        $app->rewrite("/{displayed_gallery_id}/{image_id}", '/displayed_gallery_id--{displayed_gallery_id}/image_id--{image_id}/named_size--thumb', FALSE, TRUE);
        $app->rewrite('/{displayed_gallery_id}/{image_id}/{named_size}', '/displayed_gallery_id--{displayed_gallery_id}/image_id--{image_id}/named_size--{named_size}');
        $app->route('/', 'I_OpenGraph_Controller#index');
    }

    function register_lightbox()
    {
        $router             = C_Router::get_instance();
        $settings           = C_NextGen_Settings::get_instance()->get('ngg_pro_lightbox', array());
        $lightboxes         = C_Lightbox_Library_Manager::get_instance();

        // Define the Pro Lightbox
        $lightbox           = new stdClass();
        $lightbox->title    = __('NextGEN Pro Lightbox', 'nggallery');
        $lightbox->code     = "class='nextgen_pro_lightbox' data-nplmodal-gallery-id='%PRO_LIGHTBOX_GALLERY_ID%'";
        $lightbox->styles   = array(
            'photocrati-nextgen_pro_lightbox#style.css'
        );
        $lightbox->scripts  = array(
            'wordpress#underscore',
            'wordpress#backbone',
            'photocrati-nextgen_pro_lightbox#jquery.velocity.min.js',
            'photocrati-nextgen_pro_lightbox#jquery.mobile_browsers.js',
            "photocrati-nextgen_pro_lightbox#nextgen_pro_lightbox.js"
        );

        // Set lightbox display properties
        $settings['is_front_page']  = is_front_page() ? 1 : 0;
        $settings['gallery_url']    = $router->get_url('/nextgen-pro-lightbox-gallery/{gallery_id}/', TRUE, 'root');
        $settings['share_url']      = $router->get_url('/nextgen-share/{gallery_id}/{image_id}/{named_size}', TRUE, 'root');
        $settings['wp_site_url']    = $router->get_base_url('site');
        $settings['theme']          = $router->get_static_url('photocrati-nextgen_pro_lightbox#theme/galleria.nextgen_pro_lightbox.js');

        // provide the current language so ajax requests can request translations in the same locale
        if (defined('ICL_LANGUAGE_CODE'))
        {
            $lang = $router->param('lang', NULL, FALSE) ? $router->param('lang') : ICL_LANGUAGE_CODE;
            $settings['lang'] = $lang;
            if (parse_url($settings['gallery_url'], PHP_URL_QUERY))
                $settings['gallery_url'] .= '&lang=' . $lang;
            else
                $settings['gallery_url'] .= '?lang=' . $lang;
        }

        $lightbox->values = array('nplModalSettings' => $settings);

        // Register the lightbox
        $lightboxes->register(NGG_PRO_LIGHTBOX, $lightbox);
    }

    function register_forms()
    {
        // Add forms
        $forms = C_Form_Manager::get_instance();
        $forms->add_form(NGG_LIGHTBOX_OPTIONS_SLUG, NGG_PRO_LIGHTBOX);
    }

    function get_type_list()
    {
        return array(
            'A_Pro_Lightbox_Mapper'                 => 'adapter.pro_lightbox_mapper.php',
            'A_NextGen_Pro_Lightbox_Pages'          =>  'adapter.nextgen_pro_lightbox_pages.php',
            'A_Nextgen_Pro_Lightbox_Effect_Code'    => 'adapter.nextgen_pro_lightbox_effect_code.php',
            'A_Nextgen_Pro_Lightbox_Form'           => 'adapter.nextgen_pro_lightbox_form.php',
            'C_NextGen_Pro_Lightbox_Installer'      => 'class.nextgen_pro_lightbox_installer.php',
            'A_Nextgen_Pro_Lightbox_Triggers_Form'  => 'adapter.nextgen_pro_lightbox_triggers_form.php',
            'C_NextGen_Pro_Lightbox_Trigger'        =>  'class.nextgen_pro_lightbox_trigger.php',
            'C_Nextgen_Pro_Lightbox_Controller'     => 'class.nextgen_pro_lightbox_controller.php',
            'C_Opengraph_Controller'                => 'class.opengraph_controller.php',
            'I_Nextgen_Pro_Lightbox_Controller'     => 'interface.nextgen_pro_lightbox_controller.php',
            'I_Opengraph_Controller'                => 'interface.opengraph_controller.php',
            'M_NextGen_Pro_Lightbox'                => 'module.nextgen_pro_lightbox.php',
        );
    }
}

new M_NextGen_Pro_Lightbox;
