<?php

class C_Stripe_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $settings->set_default_value('ecommerce_stripe_enable', '0');
        $settings->set_default_value('ecommerce_stripe_key_public', '');
        $settings->set_default_value('ecommerce_stripe_key_private', '');
    }
}