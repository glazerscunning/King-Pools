<?php

class C_Test_Gateway_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $settings->set_default_value('ecommerce_test_gateway_enable', '0');
    }
}