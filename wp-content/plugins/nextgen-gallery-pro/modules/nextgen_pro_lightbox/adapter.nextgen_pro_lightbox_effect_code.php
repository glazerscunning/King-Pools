<?php

class A_NextGen_Pro_Lightbox_Effect_Code extends Mixin
{
    function get_effect_code($displayed_gallery)
    {
        // Swap the gallery placeholder
        $retval = $this->call_parent('get_effect_code', $displayed_gallery);
        $retval = str_replace('%PRO_LIGHTBOX_GALLERY_ID%', $displayed_gallery->id(), $retval);

        $lightbox = C_Lightbox_Library_Manager::get_instance()->get(NGG_PRO_LIGHTBOX);
        if ($lightbox && $lightbox->values['nplModalSettings']['enable_comments']
        &&  $lightbox->values['nplModalSettings']['display_comments'])
            $retval .= ' data-nplmodal-show-comments="1"';

        return $retval;
    }
}