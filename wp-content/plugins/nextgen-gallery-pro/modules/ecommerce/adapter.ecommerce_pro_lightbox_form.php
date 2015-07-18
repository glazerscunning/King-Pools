<?php

class A_Ecommerce_Pro_Lightbox_Form extends A_NextGen_Pro_Lightbox_Form
{
    function _get_field_names()
    {
        $fields = $this->call_parent('_get_field_names');
        $fields[] = 'ecommerce_pro_lightbox_display_cart';
        return $fields;
    }

    function enqueue_static_resources()
    {
        wp_enqueue_script(
            'ngg_pro_ecommerce_lightbox_form',
            $this->object->get_static_url('photocrati-nextgen_pro_ecommerce#ecommerce_pro_lightbox_form.js')
        );
        return $this->call_parent('enqueue_static_resources');
    }

    function _render_ecommerce_pro_lightbox_display_cart_field($lightbox)
    {
	    $value = NULL;

	    if (is_array($lightbox->values) && isset($lightbox->values['nplModalSettings'])) {
			if (isset($lightbox->values['nplModalSettings']['display_cart'])) {
				$value = $lightbox->values['nplModalSettings']['display_cart'];
			}
	    }
	    elseif (isset($lightbox->display_settings['display_cart'])) {
		    $value = $lightbox->display_settings['display_cart'];
	    }

        return $this->_render_radio_field(
            $lightbox,
            'display_cart',
            __('Display cart', 'nggallery'),
            $value,
            __('When on the cart sidebar will be opened at startup. If the "Display Comments" option is also on the comments panel will open instead.', 'nggallery')
        );
    }
}
