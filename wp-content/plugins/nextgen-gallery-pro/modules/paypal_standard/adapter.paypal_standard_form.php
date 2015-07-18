<?php

class A_PayPal_Standard_Form extends Mixin
{
    function _get_field_names()
    {
        $fields = $this->call_parent('_get_field_names');
        $fields[] = 'paypal_std_enable';
        // $fields[] = 'paypal_std_currencies_supported';
        $fields[] = 'paypal_std_sandbox';
        $fields[] = 'paypal_std_email';
        return $fields;
    }

    function enqueue_static_resources()
    {
        $this->call_parent('enqueue_static_resources');
        wp_enqueue_script('ngg_pro_paypal_std_form', $this->get_static_url('photocrati-paypal_standard#form.js'));
    }

    function _render_paypal_std_enable_field()
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'paypal_std_enable',
            __('Enable PayPal Standard', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_std_enable,
            __('Not all currencies are supported by all payment gateways. Please be sure to confirm your desired currency is supported by PayPal', 'nggallery')
        );
    }

    function _render_paypal_std_sandbox_field()
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'paypal_std_sandbox',
            __('Use Sandbox?', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_std_sandbox,
            '',
            !C_NextGen_Settings::get_instance()->ecommerce_paypal_std_enable ? TRUE : FALSE
        );
    }

    function _render_paypal_std_email_field()
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_text_field(
            $model,
            'paypal_std_email',
            __('Email', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_std_email,
            '',
            !C_NextGen_Settings::get_instance()->ecommerce_paypal_std_enable ? TRUE : FALSE
        );
    }

    function _render_paypal_std_currencies_supported_field()
    {
        $settings = C_NextGen_Settings::get_instance();
        $currency = C_NextGen_Pro_Currencies::$currencies[$settings->ecommerce_currency];
        $supported = array(
            'CAD', 'EUR', 'GBP', 'USD', 'JPY', 'AUD', 'NZD', 'CHF', 'HKD', 'SGD',
            'SEK', 'DKK', 'PLN', 'NOK', 'HUF', 'CZK', 'ILS', 'MXN', 'BRL', 'MYR',
            'PHP', 'TWD', 'THB', 'TRY', 'RUB'
        );
        if (!in_array($currency['code'], $supported))
        {
            $message = __('PayPal does not support your currently chosen currency', 'nggallery');
            return "<tr id='tr_ecommerce_paypal_std_currencies_supported'><td colspan='2'>{$message}</td></tr>";
        }
    }
}