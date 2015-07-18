<?php

/*
 * See adapter.nextgen_pro_lightbox_controller.php for the settings controller
 */

class C_NextGen_Pro_Lightbox_Controller extends C_MVC_Controller
{
    static $_instance = NULL;
    var $_components = array();

    function define($context = FALSE)
    {
        parent::define($context);
        $this->add_mixin('Mixin_NextGen_Pro_Lightbox_Controller');
        $this->implement('C_NextGen_Pro_Lightbox_Controller');
    }

    static function get_instance($context = FALSE)
    {
        if (!isset(self::$_instance)) {
            $klass = get_class();
            self::$_instance = new $klass;
        }
        return self::$_instance;
    }

    function add_component($name, $handler)
    {
        $this->_components[$name] = $handler;
    }

    function remove_component($name, $handler)
    {
        unset($this->_components[$name]);
    }
}

class Mixin_NextGen_Pro_Lightbox_Controller extends C_MVC_Controller
{
    function index_action()
    {
        $factory = C_Component_Factory::get_instance();
        $router  = C_Router::get_instance();
        $gallery_mapper = C_Gallery_Mapper::get_instance();
        $lightbox_mapper = $this->object->get_registry()->get_utility('I_Lightbox_Library_Mapper');

        // retrieve by transient id
        $transient_id = $this->object->param('id');

        // ! denotes a non-nextgen gallery -- skip processing them
        if ($transient_id !== '!')
        {
            $displayed_gallery = $factory->create('displayed_gallery', array(), $gallery_mapper);
            if (!$displayed_gallery->apply_transient($transient_id))
            {
				$response = array();

                // if the transient does not exist we make an HTTP request to the referer to rebuild the transient
                if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], home_url()) !== FALSE) {
					$referrer = $_SERVER['HTTP_REFERER'];
					if (strpos($referrer, '?') === FALSE) $referrer .= '?ngg_no_resources=1';
					else $referrer .= '&ngg_no_resources=1';
					$response = wp_remote_get($referrer);
				}

                // WP has cached the results of our last get_transient() calls and must be flushed
                global $wp_object_cache;
                $wp_object_cache->flush();

                // and try again to retrieve the transient
                if (!$displayed_gallery->apply_transient($transient_id))
                {
					$displayed_gallery->id($transient_id);
                }
            }
            $displayed_gallery_id = $displayed_gallery->id();
        }
        else {
            $displayed_gallery_id = '!';
        }

        // TODO: (possibly?) find a better solution, This feels too hackish.
        // Remove all currently enqueued CSS & JS. Resources needed by the pro-lightbox incidentally happen
        // to be enqueued after this particular code is run anyway.
        global $wp_styles;
        global $wp_scripts;
        $wp_styles->queue  = array();
        $wp_scripts->queue = array();

        // our only necessary script
        wp_enqueue_script(
            'galleria',
            $this->object->get_static_url('photocrati-galleria#galleria-1.2.9.min.js'),
            array('jquery'),
            FALSE,
            FALSE
        );
        wp_enqueue_script(
            'pro-lightbox-galleria-init',
            $this->object->get_static_url('photocrati-nextgen_pro_lightbox_legacy#galleria_init.js'),
            array('galleria'),
            FALSE,
            FALSE
        );

        if (!wp_style_is('fontawesome', 'registered'))
            C_Display_Type_Controller::get_instance()->enqueue_displayed_gallery_trigger_buttons_resources();
        wp_enqueue_style('fontawesome');

        wp_enqueue_script(
            'velocity',
            $this->object->get_static_url('photocrati-nextgen_pro_lightbox_legacy#jquery.velocity.min.js')
        );

        // retrieve and add some fields to the lightbox settings
        $library = $lightbox_mapper->find_by_name(NGG_PRO_LIGHTBOX, TRUE);
        $ls = &$library->display_settings;
        $ls['theme']           = $this->object->get_static_url('photocrati-nextgen_pro_lightbox_legacy#theme/galleria.nextgen_pro_lightbox.js');
        $ls['load_images_url'] = $router->get_url('/nextgen-pro-lightbox-load-images/' . $transient_id, TRUE, 'root');
        $ls['gallery_url']     = $router->get_url('/nextgen-pro-lightbox-gallery/{gallery_id}/', TRUE, 'root');
        $ls['share_url']       = $router->get_url('/nextgen-share/{gallery_id}/{image_id}/{named_size}', TRUE, 'root');
        $ls['wp_site_url']     = $router->get_base_url();
        $ls['image_protect']   = (!empty(C_NextGen_Settings::get_instance()->protect_images) ? TRUE :  FALSE);

        if (!empty($ls['style']))
            wp_enqueue_style('nextgen_pro_lightbox_user_style', $router->get_static_url('photocrati-nextgen_pro_lightbox_legacy#styles/' . $ls['style']));

        // this should come after all other enqueue'ings
        $settings = C_NextGen_Settings::get_instance();
        if ((!is_multisite() || (is_multisite() && $settings->wpmuStyle)) && $settings->activateCSS)
            wp_enqueue_style('nggallery', C_NextGen_Style_Manager::get_instance()->get_selected_stylesheet_url());

        // The Pro Lightbox can be extended with components that enqueue their own resources
        // and render some markup
        $component_markup = array();
        foreach ($this->object->_components as $name => $handler) {
            $handler = new $handler();
            $handler->name = $name;
            if (!empty($displayed_gallery))
                $handler->displayed_gallery = $displayed_gallery;
            $handler->lightbox_library  = $library;
            $handler->enqueue_static_resources();
            $component_markup[] = $handler->render();
        }

        $params = array(
            'displayed_gallery_id' => $displayed_gallery_id,
            'lightbox_settings'    => $library->display_settings,
            'component_markup'     => implode("\n", $component_markup)
        );

        return $this->object->render_view('photocrati-nextgen_pro_lightbox_legacy#index', $params, FALSE);
    }

    /**
     * Provides a Galleria-formatted JSON array of get_included_entities() results
     */
    function load_images_action()
    {
        // Prevent displaying any warnings or errors
        ob_start();
        $this->set_content_type('json');

        $retval = array();

        if ($id = $this->param('id'))
        {
            $factory = C_Component_Factory::get_instance();
            $storage = C_Gallery_Storage::get_instance();
            $gallery_mapper = C_Displayed_Gallery_Mapper::get_instance();

            $transient_id = $this->object->param('id');
            $displayed_gallery = $factory->create('displayed_gallery', $gallery_mapper);
            if ($displayed_gallery->apply_transient($transient_id))
            {
                $images = $displayed_gallery->get_included_entities();
                if (!empty($images))
                {
                    foreach ($images as $image) {
                        $retval[] = array(
                            'image'       => $storage->get_image_url($image),
                            'title'       => $image->alttext,
                            'description' => $image->description,
                            'image_id'    => $image->{$image->id_field},
                            'thumb'       => $storage->get_image_url($image, 'thumb')
                        );
                    }
                }
            }
        }

        ob_end_clean();
        print json_encode($retval);
    }
}
