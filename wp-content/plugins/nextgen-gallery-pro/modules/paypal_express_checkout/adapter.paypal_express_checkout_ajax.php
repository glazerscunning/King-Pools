<?php

class A_PayPal_Express_Checkout_Ajax extends Mixin
{
	function paypal_express_checkout_action()
	{
		$retval = array();
		$checkout = C_NextGen_Pro_Checkout::get_instance();
		$response = $checkout->set_express_checkout();
		unset($response['token']); // for security reasons
		return $response;
	}
}