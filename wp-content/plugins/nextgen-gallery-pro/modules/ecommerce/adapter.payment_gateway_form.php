<?php

class A_Payment_Gateway_Form extends Mixin
{
	function get_title()
	{
		return $this->get_page_heading();
	}

	function get_page_heading()
	{
		return __('Payment Gateway','nggallery');
	}

    /**
     * These should be moved to their appropriate module
     * @return array
     */
    function _get_field_names()
    {
        return array(
            'nextgen_pro_ecommerce_test_gateway_enable',
            'nextgen_pro_ecommerce_cheque_enable',
            'nextgen_pro_ecommerce_cheque_instructions',
            'nextgen_pro_ecommerce_stripe_enable',
            // 'nextgen_pro_ecommerce_stripe_currencies_supported',
            'nextgen_pro_ecommerce_stripe_key_public',
            'nextgen_pro_ecommerce_stripe_key_private',
            'nextgen_pro_ecommerce_paypal_enable',
            // 'nextgen_pro_ecommerce_paypal_currencies_supported',
            'nextgen_pro_ecommerce_paypal_sandbox',
            'nextgen_pro_ecommerce_paypal_email',
            'nextgen_pro_ecommerce_paypal_username',
            'nextgen_pro_ecommerce_paypal_password',
            'nextgen_pro_ecommerce_paypal_signature'
        );
    }

    function save_action()
    {
        $ecommerce = $this->param('ecommerce');
        if (empty($ecommerce))
            return;
    }

    function enqueue_static_resources()
    {
        wp_enqueue_script(
            'photocrati-nextgen_pro_ecommerce_payment_gateway-settings-js',
            $this->get_static_url('photocrati-nextgen_pro_ecommerce#ecommerce_payment_gateway_form_settings.js'),
            array('jquery.nextgen_radio_toggle')
        );
    }

    function _render_nextgen_pro_ecommerce_stripe_enable_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'stripe_enable',
            __('Enable Stripe', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_stripe_enable,
            __('Not all currencies are supported by all payment gateways. Please be sure to confirm your desired currency is supported by Stripe', 'nggallery')
        );
    }

    /**
     * Displays a warning if the user has chosen a currency not available to Stripe.
     *
     * See: https://support.stripe.com/questions/which-currencies-does-stripe-support
     * @param $model
     * @return string
     */
    function _render_nextgen_pro_ecommerce_stripe_currencies_supported_field($model)
    {
        $settings = C_NextGen_Settings::get_instance();
        $currency = C_NextGen_Pro_Currencies::$currencies[$settings->ecommerce_currency];
        $supported = array(
            'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN',
            'BAM', 'BBD', 'BDT', 'BGN', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD',
            'BWP', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CVE',
            'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EEK', 'EGP', 'ETB', 'EUR', 'FJD',
            'FKP', 'GBP', 'GEL', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL',
            'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JMD', 'JPY', 'KES',
            'KGS', 'KHR', 'KMF', 'KRW', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD',
            'LSL', 'LTL', 'LVL', 'MAD', 'MDL', 'MGA', 'MKD', 'MNT', 'MOP', 'MRO',
            'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK',
            'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR',
            'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SEK', 'SGD', 'SHP',
            'SLL', 'SOS', 'SRD', 'STD', 'SVC', 'SZL', 'THB', 'TJS', 'TOP', 'TRY',
            'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VEF', 'VND',
            'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW'
        );
        if (!in_array($currency['code'], $supported))
        {
            $message = __('Stripe does not support your currently chosen currency', 'nggallery');
            return "<tr id='tr_ecommerce_stripe_currencies_supported'><td colspan='2'>{$message}</td></tr>";
        }
    }

    function _render_nextgen_pro_ecommerce_stripe_sandbox_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'stripe_sandbox',
            __('Use sandbox', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_stripe_sandbox,
            __('If enabled transactions will use testing servers on which no currency is actually moved', 'nggallery'),
            !C_NextGen_Settings::get_instance()->ecommerce_stripe_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_stripe_key_public_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_text_field(
            $model,
            'stripe_key_public',
            __('Public key', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_stripe_key_public,
            '',
            !C_NextGen_Settings::get_instance()->ecommerce_stripe_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_stripe_key_private_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_text_field(
            $model,
            'stripe_key_private',
            __('Private key', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_stripe_key_private,
            '',
            !C_NextGen_Settings::get_instance()->ecommerce_stripe_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_paypal_enable_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'paypal_enable',
            __('Enable PayPal Express Checkout', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_enable,
            __('Not all currencies are supported by all payment gateways. Please be sure to confirm your desired currency is supported by PayPal', 'nggallery')
        );
    }

    function _render_nextgen_pro_ecommerce_paypal_currencies_supported_field($model)
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
            return "<tr id='tr_ecommerce_paypal_currencies_supported'><td colspan='2'>{$message}</td></tr>";
        }
    }

    function _render_nextgen_pro_ecommerce_paypal_sandbox_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'paypal_sandbox',
            __('Use sandbox?', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_sandbox,
            __('If enabled transactions will use testing servers on which no currency is actually moved', 'nggallery'),
            !C_NextGen_Settings::get_instance()->ecommerce_paypal_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_paypal_email_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_text_field(
            $model,
            'paypal_email',
            __('Email', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_email,
            '',
            !C_NextGen_Settings::get_instance()->ecommerce_paypal_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_paypal_username_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_text_field(
            $model,
            'paypal_username',
            __('API Username', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_username,
            '',
            !C_NextGen_Settings::get_instance()->ecommerce_paypal_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_paypal_password_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_text_field(
            $model,
            'paypal_password',
            __('API Password', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_password,
            '',
            !C_NextGen_Settings::get_instance()->ecommerce_paypal_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_paypal_signature_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_text_field(
            $model,
            'paypal_signature',
            __('API Signature', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_paypal_signature,
            '', // Tooltip text
            !C_NextGen_Settings::get_instance()->ecommerce_paypal_enable ? TRUE : FALSE
        );
    }

    function _render_nextgen_pro_ecommerce_test_gateway_enable_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'test_gateway_enable',
            __('Enable Testing Gateway', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_test_gateway_enable,
            __('Enables a gateway that does not collect payments and sends users directly to their order confirmation', 'nggallery')
        );
    }

    function _render_nextgen_pro_ecommerce_cheque_enable_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_radio_field(
            $model,
            'cheque_enable',
            __('Enable Checks', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_cheque_enable
        );
    }

    function _render_nextgen_pro_ecommerce_cheque_instructions_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $this->_render_textarea_field(
            $model,
            'cheque_instructions',
            __('Instructions', 'nggallery'),
            C_NextGen_Settings::get_instance()->ecommerce_cheque_instructions,
            'Use this to inform users how to pay and where they should send their payment',
            !C_NextGen_Settings::get_instance()->ecommerce_cheque_enable ? TRUE : FALSE
        );
    }
}