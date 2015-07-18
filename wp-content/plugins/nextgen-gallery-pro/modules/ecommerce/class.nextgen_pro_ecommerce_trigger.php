<?php

class C_NextGen_Pro_Ecommerce_Trigger extends C_NextGen_Pro_Lightbox_Trigger
{
    static function is_renderable($name, $displayed_gallery)
    {
        $retval = FALSE;

        if (self::is_pro_lightbox_enabled() && self::are_triggers_enabled($displayed_gallery)) {
            if (self::does_source_return_images($displayed_gallery)) {
                if (isset($displayed_gallery->display_settings['is_ecommerce_enabled'])) {
                    $retval = intval($displayed_gallery->display_settings['is_ecommerce_enabled']) ? TRUE: FALSE;
                }
                if (isset($displayed_gallery->display_settings['original_settings']) && isset($displayed_gallery->display_settings['original_settings']['is_ecommerce_enabled'])) {
                    $retval = intval($displayed_gallery->display_settings['original_settings']['is_ecommerce_enabled']) ? TRUE: FALSE;
                }
            }
        }

        return $retval;
    }

    function get_attributes()
    {
        $attrs = parent::get_attributes();
        $attrs['data-nplmodal-show-cart'] = 1;
        $attrs['data-nplmodal-gallery-id'] = $this->displayed_gallery->transient_id;

        if ($this->view->get_id() == 'nextgen_gallery.image')
        {
            $image = $this->view->get_object();
            $attrs['data-image-id'] = $image->{$image->id_field};
        }

        return $attrs;
    }

    function get_css_class()
    {
        return 'fa ngg-trigger nextgen_pro_lightbox fa-shopping-cart';
    }

    function render()
    {
        $retval = '';
        $context = $this->view->get_context('object');

        // For Galleria & slideshow displays: show the gallery trigger if a single
        // image is available for sale
        if ($context && get_class($context) == 'C_MVC_View' && !empty($context->_params['images']))
        {
            $mapper = C_Pricelist_Mapper::get_instance();
            foreach ($context->_params['images'] as $image) {
                if ($mapper->find_for_image($image))
                {
                    $retval = parent::render();
                    break;
                }
            }
        }
        else {
            // Display the trigger if the image is for sale
            $mapper = C_Pricelist_Mapper::get_instance();
            if ($mapper->find_for_image($context)) {
                $retval = parent::render();
            }
        }

        return $retval;
    }
}