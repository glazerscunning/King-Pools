<?php
/*
{
    Module: photocrati-nextgen_pro_lightbox_legacy,
    Depends: { photocrati-lightbox }
}
 */

define('NGG_PRO_LIGHTBOX', 'photocrati-nextgen_pro_lightbox');
define('NGG_PRO_LIGHTBOX_TRIGGER', NGG_PRO_LIGHTBOX);
define('NGG_PRO_LIGHTBOX_COMMENT_TRIGGER', 'photocrati-nextgen_pro_lightbox_comments');

/**
 * This version of the Pro Lightbox is compatible with 2.0.66 and earlier. For 2.0.67, see the
 * photocrati-nextgen_pro_lightbox module
 */
class M_NextGen_Pro_Lightbox_Legacy extends C_Base_Module
{
    var $resources = NULL;

    function define($context=FALSE)
    {
        parent::define(
            'photocrati-nextgen_pro_lightbox_legacy',
            'NextGEN Pro Lightbox',
            'Provides a lightbox with integrated commenting, social sharing, and e-commerce functionality',
            '0.25',
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

        $this->resources = new A_Nextgen_Pro_Lightbox_Resources();
    }

    function _register_adapters()
    {
        if (!is_admin()) {
            // controllers & their helpers
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_Nextgen_Pro_Lightbox_Resources');
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
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Lightbox_Form', NGG_PRO_LIGHTBOX.'_basic');
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
        add_action('wp_enqueue_scripts', array(&$this->resources, 'enqueue_pro_lightbox_resources'));
        add_action('wp_enqueue_scripts', array(&$this, 'use_legacy_resources'), PHP_INT_MAX-5);
        if (!is_admin()) add_action('init', array(&$this, 'define_routes'));
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

    /**
     * In 1.0.17, there was no legacy module. Therefore, all scripts are registered from the
     * nextgen_pro_lightbox module directory. We apply a hotfix for any registered libraries to point
     * to the correct path
     */
    function use_legacy_resources()
    {
        if (isset(M_Lightbox::$_registered_lightboxes)) {
            global $wp_scripts;
            foreach (M_Lightbox::$_registered_lightboxes as $handle) {
                $script = $wp_scripts->registered[$handle];
                $script->src = str_replace('/nextgen_pro_lightbox/', '/nextgen_pro_lightbox_legacy/', $script->src);
            }
        }
    }

    function register_forms()
    {
        // Add forms
        $forms = C_Form_Manager::get_instance();
        $forms->add_form(NGG_LIGHTBOX_OPTIONS_SLUG, NGG_PRO_LIGHTBOX.'_basic');
    }

    function get_type_list()
    {
        return array(
            'A_Pro_Lightbox_Mapper'                 => 'adapter.pro_lightbox_mapper.php',
            'A_Nextgen_Pro_Lightbox_Resources'      =>  'adapter.nextgen_pro_lightbox_resources.php',
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
            'M_NextGen_Pro_Lightbox_Legacy'         => 'module.nextgen_pro_lightbox.php',
        );
    }
}

new M_NextGen_Pro_Lightbox_Legacy;
