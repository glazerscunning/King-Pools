<?php

class C_NextGen_Pro_Ecommerce_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $this->install_ecommerce_settings($settings);
    }

    function install_ecommerce_settings($settings)
    {
        $settings->set_default_value('ecommerce_currency', 840); // 'USD'
        $settings->set_default_value('ecommerce_home_country', 840); // 'United States'
        $settings->set_default_value('ecommerce_page_checkout', '');
        $settings->set_default_value('ecommerce_page_thanks', '');
        $settings->set_default_value('ecommerce_page_cancel', '');
        $settings->set_default_value('ecommerce_page_digital_downloads', '');
        $settings->set_default_value('ecommerce_enable_email_notification', TRUE);
        $settings->set_default_value('ecommerce_email_notification_subject', 'New Purchase!');
        $settings->set_default_value('ecommerce_email_notification_recipient', get_bloginfo('admin_email'));
        $settings->set_default_value('ecommerce_enable_email_receipt', TRUE);
        $settings->set_default_value('ecommerce_email_receipt_subject', "Thank you for your purchase!");
        $settings->set_default_value('ecommerce_email_receipt_body', "Thank you for your order, %%customer_name%%.\n\nYou ordered %%item_count%% items, and have been billed a total of %%total_amount%%.\n\nTo review your order, please go to %%order_details_page%%.\n\nThanks for shopping at %%site_url%%!");
        $settings->set_default_value('ecommerce_email_notification_body', "You received a payment of %%total_amount%% from %%customer_name%%. For more details, visit: %%order_details_page%%\n\n%%gateway_admin_note%%\n\nHere is a comma separated list of the image file names. You can copy and\npaste this in your favorite image management software to quickly search for\nand find all selected images.\n\nFiles: %%file_list%%");
        $settings->set_default_value('ecommerce_not_for_sale_msg', "Sorry, this image is not currently for sale.");

	    $ngg_pro_lightbox = $settings->get('ngg_pro_lightbox');
	    if (empty($ngg_pro_lightbox['display_cart']))
	    {
		    $ngg_pro_lightbox['display_cart'] = 0;
		    $settings->set('ngg_pro_lightbox', $ngg_pro_lightbox);
	    }
    }
}