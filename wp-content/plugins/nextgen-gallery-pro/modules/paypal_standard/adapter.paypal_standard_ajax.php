<?php

class A_PayPal_Standard_Ajax extends Mixin
{
    function paypal_standard_order_action()
    {
        $retval = array();

        if (($items = $this->param('items'))) {
            $checkout   = new C_NextGen_Pro_Checkout();
            $cart       = new C_NextGen_Pro_Cart();
            $settings   = C_NextGen_Settings::get_instance();
            $currency   = C_NextGen_Pro_Currencies::$currencies[$settings->ecommerce_currency];

            foreach ($items as $image_id => $image_items) {
                if (($image = C_Image_Mapper::get_instance()->find($image_id))) {
                    $cart->add_image($image_id, $image);
                    foreach ($image_items as $item_id => $quantity) {
                        if (($item = C_Pricelist_Item_Mapper::get_instance()->find($item_id))) {
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
            if ($cart->has_items()) {
                $order = $checkout->create_order(
                    $cart->to_array(),
                    __('PayPal Customer', 'nggallery'),
                    'Unknown',
                    $order_total,
                    'paypal_standard'
                );
                $order->status = 'unverified';
                $order->use_home_country = $use_home_country;
	            $order->gateway_admin_note = __('Payment was successfully made via PayPal Standard, with no further payment action required.');
                C_Order_Mapper::get_instance()->save($order);
                $retval['order'] = $order->hash;
            }
            else $retval['error'] = __('Your cart is empty', 'nggallery');
        }

        return $retval;
    }
}
