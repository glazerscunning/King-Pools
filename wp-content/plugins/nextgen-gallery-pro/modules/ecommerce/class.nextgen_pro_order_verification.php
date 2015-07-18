<?php

class C_NextGen_Pro_Order_Verification extends C_MVC_Controller
{
    static $_instance = NULL;
    static function get_instance()
    {
        if (!isset(self::$_instance)) {
            $klass = get_class();
            self::$_instance = new $klass;
        }
        return self::$_instance;
    }

    function get_i18n_strings()
    {
        $i18n = new stdClass();
        $i18n->please_wait_msg = __("Please wait - we appreciate your patience.", 'nggallery');
        $i18n->verifying_order_msg = __("We're verifying your order. This might take a few minutes.", 'nggallery');
        $i18n->redirect_msg    = __('This page will redirect automatically.', 'nggallery');
        return $i18n;
    }


    function render($order_hash)
    {
	    wp_enqueue_script('photocrati_ajax');

        return $this->render_partial('photocrati-nextgen_pro_ecommerce#order_verification', array(
            'order_hash'    =>  $order_hash,
            'i18n'          =>  $this->get_i18n_strings()
        ), TRUE);
    }
}