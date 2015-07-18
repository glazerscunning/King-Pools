<?php

class C_Cheque_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $settings->set_default_value('ecommerce_cheque_enable', '0');
        $settings->set_default_value('ecommerce_cheque_instructions', "<p>Thanks very much for your purchase! We'll be in touch shortly via email to confirm your order and to provide details on payment.</p>");
    }
}
