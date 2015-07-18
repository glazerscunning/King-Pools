<?php
/**
{
	Module: photocrati-stripe
}
**/
class M_Photocrati_Stripe extends C_Base_Module
{
	function define()
	{
		parent::define(
			'photocrati-stripe',
			'Stripe',
			'Provides integration with Stripe payment gateway',
			'0.4',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com'
		);

        include_once('class.stripe_installer.php');
        C_Photocrati_Installer::add_handler($this->module_id, 'C_Stripe_Installer');
	}

	function _register_adapters()
	{
        if (!is_admin()) {
            $this->get_registry()->add_adapter('I_NextGen_Pro_Checkout', 'A_Stripe_Checkout_Button');
            $this->get_registry()->add_adapter('I_Ajax_Controller',      'A_Stripe_Checkout_Ajax');
        }
	}

    function _register_hooks()
    {
        add_action('init', array(&$this, 'route'));
    }

    function route()
    {
        if (isset($_REQUEST['ngg_stripe_rtn']) && isset($_REQUEST['order'])) {
            $checkout = C_NextGen_Pro_Checkout::get_instance();
            $checkout->redirect_to_thank_you_page($_REQUEST['order']);
        }
    }

	function get_type_list()
	{
		return array(
			'A_Stripe_Checkout_Button'		=>	'adapter.stripe_checkout_button.php',
            'A_Stripe_Checkout_Ajax'        =>  'adapter.stripe_checkout_ajax.php'
		);
	}
}

new M_Photocrati_Stripe;