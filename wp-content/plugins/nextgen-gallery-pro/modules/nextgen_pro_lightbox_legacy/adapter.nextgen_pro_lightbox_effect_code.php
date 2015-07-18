<?php

class A_NextGen_Pro_Lightbox_Effect_Code extends Mixin
{
    function get_effect_code($displayed_gallery)
    {
        $retval = $this->call_parent('get_effect_code', $displayed_gallery);

        if (C_NextGen_Settings::get_instance()->thumbEffect == NGG_PRO_LIGHTBOX)
        {
            $retval = str_replace('%PRO_LIGHTBOX_GALLERY_ID%', $displayed_gallery->id(), $retval);

            $mapper = C_Lightbox_Library_Mapper::get_instance();
            $lightbox = $mapper->find_by_name(NGG_PRO_LIGHTBOX);
            if ($lightbox->display_settings['display_comments'])
                $retval .= ' data-nplmodal-show-comments="1"';
        }

        return $retval;

    }
}