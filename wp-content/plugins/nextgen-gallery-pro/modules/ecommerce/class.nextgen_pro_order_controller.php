<?php

class C_NextGen_Pro_Order_Controller extends C_MVC_Controller
{
    static $_instance = NULL;
    static function get_instance()
    {
        if (is_null(self::$_instance)) {
            $klass = get_class();
            self::$_instance = new $klass;
        }
        return self::$_instance;
    }

    function get_i18n_strings()
    {
        $i18n = new stdClass();

        $i18n->image        = __('Image', 'nggallery');
        $i18n->quantity     = __('Quantity', 'nggallery');
        $i18n->description  = __('Description', 'nggallery');
        $i18n->price        = __('Price', 'nggallery');
        $i18n->total        = __('Total', 'nggallery');

        return $i18n;
    }

    function enqueue_static_resources()
    {
        wp_enqueue_style('ngg-pro-order-info', $this->get_static_url('photocrati-nextgen_pro_ecommerce#order_info.css'));
    }


    function render($cart)
    {
        $this->enqueue_static_resources();

        $cart = $cart->to_array();

        return $this->object->render_partial(
            'photocrati-nextgen_pro_ecommerce#order',
            array(
                'images'    =>  $cart['images'],
                'i18n'      =>  $this->get_i18n_strings()
            ),
            TRUE
        );
    }
}