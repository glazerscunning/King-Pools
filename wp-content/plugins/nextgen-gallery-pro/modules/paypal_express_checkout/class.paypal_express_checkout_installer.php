<?php
class C_Paypal_Express_Checkout_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $settings->set_default_value('ecommerce_paypal_enable', '0');
        $settings->set_default_value('ecommerce_paypal_sandbox', 1);
        $settings->set_default_value('ecommerce_paypal_email', '');
        $settings->set_default_value('ecommerce_paypal_username', '');
        $settings->set_default_value('ecommerce_paypal_password', '');
        $settings->set_default_value('ecommerce_paypal_signature', '');
    }
}