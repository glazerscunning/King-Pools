<?php

class A_Test_Gateway_Checkout_Button extends Mixin
{
    function get_checkout_buttons()
    {
        $buttons = parent::call_parent('get_checkout_buttons');
        if (C_NextGen_Settings::get_instance()->ecommerce_test_gateway_enable)
            $buttons[] = 'test_gateway_checkout';
        return $buttons;
    }

    function get_i18n_strings()
    {
        $i18n = new stdClass;
        $i18n->button_text = __('Place order', 'nggallery');
	    $i18n->processing_msg = __('Processing...', 'nggallery');
        return $i18n;
    }

    function _render_test_gateway_checkout_button()
    {
        return $this->render_partial(
            'photocrati-test_gateway#button',
            array('i18n' => $this->get_i18n_strings()),
            TRUE
        );
    }

}
