<?php


class C_PayPal_Standard_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $settings->set_default_value('ecommerce_paypal_std_enable', 0);
        $settings->set_default_value('ecommerce_paypal_std_sandbox', 1);
        $settings->set_default_value('ecommerce_paypal_std_email', '');
    }
}