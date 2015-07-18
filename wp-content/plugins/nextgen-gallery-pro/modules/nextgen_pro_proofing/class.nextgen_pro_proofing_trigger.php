<?php

class C_NextGen_Pro_Proofing_Trigger extends C_Displayed_Gallery_Trigger
{
    function get_css_class()
    {
        $classes = 'fa ngg-trigger ngg-trigger-proofing nextgen_pro_proofing fa-star';
        return $classes;
    }

    static function is_renderable($name, $displayed_gallery)
    {
        $retval = FALSE;

        if (isset($displayed_gallery->display_settings['ngg_proofing_display'])
        &&  $displayed_gallery->display_settings['ngg_proofing_display'])
        {
            $retval = TRUE;
        }

        return $retval;
    }

    function get_attributes()
    {
        $retval = array(
            'class' => $this->get_css_class(),
            'data-nplmodal-gallery-id' => $this->displayed_gallery->transient_id
        );

        // If we're adding the trigger to an image, then we need
        // to add an attribute for the Pro Lightbox to know which image to display
        if ($this->view->get_id() == 'nextgen_gallery.image')
        {
            $image = $this->view->get_object();
            $retval['data-image-id'] = $image->{$image->id_field};
        }

        return $retval;
    }
}
