<?php

class A_PayPal_Standard_Button extends Mixin
{
    function get_checkout_buttons()
    {
        $buttons = parent::call_parent('get_checkout_buttons');

        if ($this->is_paypal_standard_enabled())
            $buttons[] = 'paypal_standard';

        return $buttons;
    }

    function get_paypal_url()
    {
        return C_NextGen_Settings::get_instance()->get('ecommerce_paypal_std_sandbox', TRUE) ?
            'https://www.sandbox.paypal.com/cgi-bin/webscr' :'https://www.paypal.com/cgi-bin/webscr';
    }

    function is_paypal_standard_enabled()
    {
        return C_NextGen_Settings::get_instance()->get('ecommerce_paypal_std_enable',FALSE) ? TRUE : FALSE;
    }

    function _get_paypal_currency_code()
    {
        $settings = C_NextGen_Settings::get_instance();
        return C_NextGen_Pro_Currencies::$currencies[$settings['ecommerce_currency']]['code'];
    }

    function _render_paypal_standard_button()
    {
        return $this->render_partial('photocrati-paypal_standard#button', array(
            'value'                 => __('Pay with PayPal', 'nggallery'),
            'currency'              =>  $this->_get_paypal_currency_code(),
            'email'                 =>  C_NextGen_Settings::get_instance()->ecommerce_paypal_std_email,
            'continue_shopping_url' =>  $this->get_continue_shopping_url(),
            'return_url'            =>  site_url('/?ngg_pstd_rtn=1'),
            'notify_url'            =>  site_url('/?ngg_pstd_nfy=1'),
            'cancel_url'            =>  site_url('/?ngg_pstd_cnl=1'),
            'paypal_url'            =>  $this->get_paypal_url(),
	        'processing_msg'   =>  __('Processing...', 'nggallery')
        ), TRUE);
    }

    function is_pdt_enabled()
    {
        return strlen(trim(C_NextGen_Settings::get_instance()->get('ecommerce_paypal_std_pdt_token', ''))) > 1;
    }

    function create_paypal_standard_order()
    {
        $order_mapper = C_Order_Mapper::get_instance();
        if (($order = $order_mapper->find_by_hash($this->param('order')))) {
            $order->paypal_data = $_REQUEST;

            // If PDT is available, use it to verify the order
            if ($this->is_pdt_enabled()) {
                // TODO: Use PDT to verify order
                $order->status = 'verified';
            }

            // Save the order
            $order_mapper->save($order);

            // Redirect the user
            if ($order->status == 'verified') $this->redirect_to_thank_you_page($order->hash);
            else $this->redirect_to_order_verification_page($order->hash);

        }
    }

    function validate_order($order_hash, $total, $customer_name, $email, $shipping_street_address, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $phone)
    {
        $retval = FALSE;

        $order_mapper = C_Order_Mapper::get_instance();

        if (($order = $order_mapper->find_by_hash($order_hash))) {

            // Has fraud been detected?
            $cart = new C_NextGen_Pro_Cart($order->cart);
            if ($cart->get_total($order->use_home_country) == $total) {
                $order->customer_name           = $customer_name;
                $order->email                   = $email;
                $order->shipping_street_address = $shipping_street_address;
                $order->shipping_city           = $shipping_city;
                $order->shipping_state          = $shipping_state;
                $order->shipping_zip            = $shipping_zip;
                $order->shipping_country        = $shipping_country;
                $retval = $order;
            }

            // Fraud detected
            $order->status = 'fraud';
        }


        return $retval;
    }

    function paypal_ipn_listener()
    {
        // STEP 1: read POST data
        // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
        // Instead, read raw POST data from the input stream.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        // STEP 2: Validate the IPN
        if (isset($_REQUEST['custom'])) {
            $response = wp_remote_post($this->get_paypal_url(), array('body'=>$req));

            if (($order = $this->validate_order(
                $_REQUEST['custom'],
                isset($_REQUEST['mc_gross']) ? $_REQUEST['mc_gross']: 0.0,
                isset($_REQUEST['first_name']) && isset($_REQUEST['last_name']) ? $_REQUEST['first_name'].' '.$_REQUEST['last_name'] : '',
                isset($_REQUEST['payer_email']) ? $_REQUEST['payer_email'] : '',
                isset($_REQUEST['address_street']) ? $_REQUEST['address_street'] : '',
                isset($_REQUEST['address_city']) ? $_REQUEST['address_city'] : '',
                isset($_REQUEST['address_state']) ? $_REQUEST['address_state'] : '',
                isset($_REQUEST['address_zip']) ? $_REQUEST['address_zip'] : '',
                isset($_REQUEST['address_country']) ? $_REQUEST['address_country']: '',
                isset($_REQUEST['contact_phone']) ? $_REQUEST['contact_phone'] : ''
            ))) {
                $order_mapper = C_Order_Mapper::get_instance();

                // Fraud detected?
                if (stripos($response['body'], 'VERIFIED') === FALSE) {
                    $order->status = 'fraud';
                    $order_mapper->save($order);
                }

                // Valid
                else {
                    $order->status = 'verified';
                    $order->sent_emails = TRUE;
                    $order_mapper->save($order);

                    $this->send_email_notification($order->hash);
                    $this->send_email_receipt($order->hash);
                }
            }
        }

        throw new E_Clean_Exit;
    }
}
