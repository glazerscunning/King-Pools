<?php

class A_Cheque_Checkout_Ajax extends Mixin
{
    function cheque_checkout_action()
    {
	    $retval = array();

        $items = $this->param('items');

        if (!$items)
            return array('error' => __('Your cart is empty', 'nggallery'));

        $customer = array(
            'name'    => $this->param('customer_name'),
            'email'   => $this->param('customer_email'),
            'address' => $this->param('customer_address'),
            'city'    => $this->param('customer_city'),
            'state'   => $this->param('customer_state'),
            'postal'  => $this->param('customer_postal'),
            'country' => $this->param('customer_country')
        );

		$retval['customer'] = $customer;

        // Presently we only do basic field validation: ensure that each field is filled and that
        // the country selected exists in C_NextGen_Pro_Currencies::$countries
        foreach ($customer as $key => $val) {
            if (empty($val)) {
	            $retval['error'] = __('Please fill all fields and try again', 'nggallery');
	            break;
            }
        }

	    // No error yet?
	    if (!isset($retval['error'])) {
		    if (empty(C_NextGen_Pro_Currencies::$countries[$customer['country']]))
			    return array('error' => __('Invalid country selected, please try again.', 'nggallery'));
		    else
			    $customer['country'] = C_NextGen_Pro_Currencies::$countries[$customer['country']]['name'];

		    $checkout = new C_NextGen_Pro_Checkout();
		    $cart     = new C_NextGen_Pro_Cart();
		    $settings = C_NextGen_Settings::get_instance();
		    $currency = C_NextGen_Pro_Currencies::$currencies[$settings->ecommerce_currency];

		    foreach ($items as $image_id => $image_items) {
			    if (($image = C_Image_Mapper::get_instance()->find($image_id)))
			    {
				    $cart->add_image($image_id, $image);
				    foreach ($image_items as $item_id => $quantity) {
					    if (($item = C_Pricelist_Item_Mapper::get_instance()->find($item_id)))
					    {
						    $item->quantity = $quantity;
						    $cart->add_item($image_id, $item_id, $item);
					    }
				    }
			    }
		    }

		    // Calculate the total
		    $use_home_country = intval($this->param('use_home_country'));
		    $order_total = $cart->get_total($use_home_country);

		    // Create the order
		    if (!$cart->has_items())
			    return array('error' => __('Your cart is empty', 'nggallery'));

		    $order = $checkout->create_order(
			    $cart->to_array(),
			    $customer['name'],
			    $customer['email'],
			    $order_total,
			    'cheque',
			    $customer['address'],
			    $customer['city'],
			    $customer['state'],
			    $customer['postal'],
			    $customer['country'],
			    $use_home_country,
			    'unverified'
		    );
		    $order->status = 'unverified';
		    $order->gateway_admin_note = __('Payment was successfully made via Check. Once you have received payment, you can click “Verify” in the View Orders page and a confirmation email will be sent to the user.');

		    C_Order_Mapper::get_instance()->save($order);

		    $checkout->send_email_notification($order->hash);

		    $retval['order']    = $order->hash;
		    $retval['redirect'] = $checkout->get_thank_you_page_url($order->hash, TRUE);
	    }

        return $retval;
    }
}
