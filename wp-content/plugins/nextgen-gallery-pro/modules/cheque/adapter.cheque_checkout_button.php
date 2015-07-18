<?php

class A_Cheque_Checkout_Button extends Mixin
{
    function get_checkout_buttons()
    {
        $buttons = parent::call_parent('get_checkout_buttons');

        if (C_NextGen_Settings::get_instance()->get('ecommerce_cheque_enable', FALSE))
            $buttons[] = 'cheque_checkout';

        return $buttons;
    }

    function enqueue_cheque_checkout_resources()
    {
        wp_enqueue_script(
            'jquery-placeholder',
            $this->object->get_static_url('photocrati-nextgen_admin#jquery.placeholder.min.js'),
            'jquery',
            FALSE,
            FALSE
        );
        wp_enqueue_script('cheque-checkout', $this->object->get_static_url('photocrati-cheque#button.js'), array('jquery-placeholder'));
        wp_enqueue_style('cheque-checkout', $this->object->get_static_url('photocrati-cheque#button.css'));
    }

    function get_i18n_strings()
    {
        $i18n = new stdClass;
        $i18n->headline           = __('Shipping information', 'nggallery');
        $i18n->button_text        = __('Pay by check', 'nggallery');
        $i18n->button_text_submit = __('Place order',  'nggallery');
        $i18n->button_text_cancel = __('Cancel',       'nggallery');
	    $i18n->processing_msg     = __('Processing...', 'nggallery');

        $i18n->field_name    = __('Name',    'nggallery');
        $i18n->field_email   = __('Email',   'nggallery');
        $i18n->field_address = __('Address', 'nggallery');
        $i18n->field_city    = __('City',    'nggallery');
        $i18n->field_state   = __('State',   'nggallery');
        $i18n->field_postal  = __('Zip',     'nggallery');
        $i18n->field_country = __('Country', 'nggallery');
        return $i18n;
    }

    function _render_cheque_checkout_button()
    {
        return $this->render_partial(
            'photocrati-cheque#button',
            array(
                'countries' => C_NextGen_Pro_Currencies::$countries,
                'i18n' => $this->get_i18n_strings()
            ),
            TRUE
        );
    }

}
