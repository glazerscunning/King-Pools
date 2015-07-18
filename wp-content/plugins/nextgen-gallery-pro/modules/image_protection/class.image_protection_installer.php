<?php

class C_Image_Protection_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $this->install_image_protection_settings($settings);
    }

    function install_image_protection_settings($settings)
    {
        $settings->set_default_value('protect_images', 0);
        $settings->set_default_value('protect_images_globally', 0);
    }
}
