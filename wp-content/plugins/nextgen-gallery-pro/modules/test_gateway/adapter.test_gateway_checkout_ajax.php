<?php

class A_Test_Gateway_Checkout_Ajax extends Mixin
{
    function test_gateway_checkout_action()
    {
        $retval = array();

        if (($items = $this->param('items')))
        {
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
            if ($cart->has_items())
            {
                // the below address is the USPS headquarters; it's only an example.
                $order = $checkout->create_order(
                    $cart->to_array(),
                    __('Test Customer', 'nggallery'),
                    'Unknown',
                    $order_total,
                    'test_gateway',
                    "475 L'Enfant Plaza SW",
                    'Washington D.C.',
                    'MD',
                    '20260-0004'
                );
                $order->status = 'verified';
                $order->use_home_country = $use_home_country;
	            $order->gateway_admin_note = __('Payment was successfully made via the Test Gateway, with no further payment action required.');
                C_Order_Mapper::get_instance()->save($order);
                $checkout->send_email_notification($order->hash);
                $retval['order'] = $order->hash;
                $retval['redirect'] = $checkout->get_thank_you_page_url($order->hash, TRUE);
            }
            else {
                $retval['error'] = __('Your cart is empty', 'nggallery');
            }
        }

        return $retval;
    }
}
