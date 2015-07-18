<?php

class C_NextGen_Pro_Checkout extends C_MVC_Controller
{
	static $_instance = NULL;

	static function get_instance()
	{
		if (!self::$_instance) {
			$klass = get_class();
			self::$_instance = new $klass;
		}
		return self::$_instance;
	}

	function define()
	{
		parent::define();
		$this->implement('I_NextGen_Pro_Checkout');
		$this->add_mixin('Mixin_NextGen_Pro_Checkout');
	}
}

class Mixin_NextGen_Pro_Checkout extends Mixin
{
	/**
	 * Adapters are expected to override to provide more payment gateway buttons
	 * @return array
	 */
	function get_checkout_buttons()
	{
		return array();
	}

	function get_i18n_strings()
	{
		$i18n = new stdClass;

		$i18n->image_header 	= __('Image', 		'nggallery');
		$i18n->quantity_header	= __('Quantity',	'nggallery');
		$i18n->item_header		= __('Description',	'nggallery');
		$i18n->price_header		= __('Price',		'nggallery');
		$i18n->total_header		= __('Totals',		'nggallery');
		$i18n->subtotal			= __('Subtotal:',	'nggallery');
		$i18n->shipping			= __('Shipping:', 	'nggallery');
		$i18n->total			= __('Total:',		'nggallery');
		$i18n->no_items			= __('There have been no items added to your cart.', 'nggallery');
        $i18n->continue_shopping= __('Continue shopping', 'nggallery');
        $i18n->empty_cart       = __('Empty cart',  'nggallery');
        $i18n->ship_to          = __('Ship to:', 'nggallery');
        $i18n->ship_elsewhere   = __('International', 'nggallery');

		return $i18n;
	}

	function enqueue_static_resources()
	{
        M_NextGen_Pro_Ecommerce::enqueue_cart_resources();

        // Enqueue fontawesome
        if (method_exists('M_Gallery_Display', 'enqueue_fontawesome')) {
            M_Gallery_Display::enqueue_fontawesome();
        }
        else {
            C_Display_Type_Controller::get_instance()->enqueue_displayed_gallery_trigger_buttons_resources();
        }
        wp_enqueue_style('fontawesome');



		wp_enqueue_style('ngg-pro-checkout', $this->get_static_url('photocrati-nextgen_pro_ecommerce#checkout.css'));
		foreach ($this->object->get_checkout_buttons() as $btn) {
			$method = "enqueue_{$btn}_resources";
			if ($this->object->has_method($method)) {
				$this->object->$method();
			}
		}
	}

    function get_continue_shopping_url()
    {
        return isset($_GET['referrer']) ? $_GET['referrer'] : '';
    }

	function checkout_form()
	{
		$this->enqueue_static_resources();

		if ($this->is_post_request()) $this->processor();

        // Get checkout buttons
		$buttons = array();
		foreach ($this->object->get_checkout_buttons() as $btn) {
			$method = "_render_{$btn}_button";
			$buttons[] = $this->object->$method();
		}


        // Get country
        $country    = C_NextGen_Pro_Currencies::$countries[840];
        $country_id = C_NextGen_Settings::get_instance()->ecommerce_home_country;
        if (isset(C_NextGen_Pro_Currencies::$countries[$country_id])) {
            $country = C_NextGen_Pro_Currencies::$countries[$country_id];
            $country = $country['name'];
        }

		return $this->render_partial('photocrati-nextgen_pro_ecommerce#checkout_form', array(
			'buttons'		=>	$buttons,
            'referrer_url'  =>  $this->get_continue_shopping_url(),
			'i18n'			=>	$this->get_i18n_strings(),
            'country'       =>  $country
		), TRUE);
	}

	function processor()
	{
		if (($gateway = $this->param('ngg_pro_checkout'))) {
			$method = "process_{$gateway}_request";
			if ($this->object->has_method($method)) {
				$this->object->$method();
			}
		}
	}

    function create_order($cart, $customer_name, $email, $total_amount, $payment_gateway, $shipping_street_address=NULL, $shipping_city=NULL, $shipping_state=NULL, $shipping_zip=NULL, $shipping_country=NULL, $use_home_country=TRUE, $status='verified')
    {
        $order_mapper = C_Order_Mapper::get_instance();
        $properties = array(
            'customer_name'             =>  $customer_name,
            'email'                     =>  $email,
            'payment_gateway'           =>  $payment_gateway,
            'total_amount'              =>  $total_amount,
            'cart'                      =>  $cart,
            'shipping_street_address'   =>  $shipping_street_address,
            'shipping_city'             =>  $shipping_city,
            'shipping_state'            =>  $shipping_state,
            'shipping_zip'              =>  $shipping_zip,
            'shipping_country'          =>  $shipping_country,
            'status'                    =>  $status,
	        'use_home_country'          =>  $use_home_country,
            'post_status'               =>  'publish'
        );
        $order = $order_mapper->create($properties);

        return $order;
    }

    function _send_email($order_hash, $subject, $body, $to=NULL)
    {
        $retval = FALSE;

        // Get the destination url
        $order_details_page = $this->get_thank_you_page_url($order_hash, TRUE);

        // Ensure that we have a valid order
        if (($order = C_Order_Mapper::get_instance()->find_by_hash($order_hash))) {
            // Get needed components
            $mail = C_Nextgen_Mail_Manager::get_instance();

            // Set additional order variables
            $order->order_details_page  = $order_details_page;
            $order->total_amount        = M_NextGen_Pro_Ecommerce::get_formatted_price($order->total_amount, FALSE, FALSE);
            $order->admin_email         = bloginfo('admin_email');
            $order->blog_description    = bloginfo('description');
            $order->blog_name           = bloginfo('name');
            $order->blog_url            = site_url();
            $order->site_url            = site_url();
            $order->home_url            = home_url();
            $order->order_id            = $order->ID;

            // Determine item count
            $item_count = 0;
            $file_list  = '';
            foreach ($order->cart['images'] as $image) {
                $imageObj = C_Image_Mapper::get_instance()->find($image[$image['id_field']]);
                $name = pathinfo($imageObj->filename);
                $name = $name['filename'];
                if ($item_count == 0)
                    $file_list = $name;
                else
                    $file_list .= ',' . $name;

                foreach ($image['items'] as $tmpid => $item) {
                    // TODO: remove this if. I can't determine why these are being added as items
                    if (in_array($tmpid, array('ngg_digital_downloads', 'ngg_manual_pricelist')))
                        continue;
                    $item_count += intval($item['quantity']);
                }
            }
            $order->item_count = $item_count;
            $order->file_list = $file_list;

            // Send the e-mail
            $content = $mail->create_content();
            $content->set_subject($subject);
            $content->load_template($body);
            foreach (get_object_vars($order) as $key => $val) $content->set_property($key, $val);
            $mail->send_mail($content, $to ? $to : $order->email);
            $retval = TRUE;
        }
        return $retval;
    }

    function send_email_receipt($order_hash)
    {
        $retval = FALSE;

        $settings = C_NextGen_Settings::get_instance();

        // Send e-mail receipt to customer
        if ($settings->ecommerce_enable_email_receipt) {
            $retval = $this->_send_email(
                $order_hash,
                $settings->ecommerce_email_receipt_subject,
                $settings->ecommerce_email_receipt_body
            );
        }

        return $retval;
    }

    function send_email_notification($order_hash)
    {
        $retval = FALSE;

        $settings = C_NextGen_Settings::get_instance();

        // Send admin notification
        if ($settings->ecommerce_enable_email_notification) {
            $this->_send_email(
                $order_hash,
                $settings->ecommerce_email_notification_subject,
                $settings->ecommerce_email_notification_body,
                $settings->ecommerce_email_notification_recipient
            );
            $retval = TRUE;
        }

        return $retval;
    }


    function redirect_to_thank_you_page($order_hash)
    {
        // Expose hook for third-parties
        do_action('ngg_pro_purchase_complete');

        // Get the destination url
        $order_details_page = $this->get_thank_you_page_url($order_hash, TRUE);

        // Get the order
        if (($order = C_Order_Mapper::get_instance()->find_by_hash($order_hash))) {

            if (!isset($order->sent_emails) OR !$order->sent_emails) {

                // Send the admin notification only when the purchase has been verified
                if ($order->status == 'verified') {
                    $this->send_email_notification($order_hash);
                }

                // Send the e-mail receipt as soon as we can
                $this->send_email_receipt($order_hash);
            }
        }
        else die(__("We couldn't find your order. We apologize for the inconvenience", 'nggallery'));

        wp_redirect($order_details_page);
        throw new E_Clean_Exit;
    }

    function redirect_to_cancel_page()
    {
        wp_redirect($this->get_cancel_page_url());
        throw new E_Clean_Exit;
    }

    function redirect_to_order_verification_page($order_hash)
    {
        wp_redirect($this->get_order_verification_page_url($order_hash));
        throw new E_Clean_Exit;
    }

    function get_thank_you_page_url($order_id, $order_complete=FALSE)
    {
        $params     = array('order' => $order_id);
        if ($order_complete) $params['ngg_order_complete'] = 1;
        $settings   = C_NextGen_Settings::get_instance();
        if ($settings->ecommerce_page_thanks) {
            return $this->get_page_url(C_NextGen_Settings::get_instance()->ecommerce_page_thanks, $params);
        }
        else {
            return $this->_add_to_querystring(site_url('/?ngg_pro_return_page=1'),$params);
        }

    }

    function _add_to_querystring($url, $params=array())
    {
        if ($params) {
            $qs = array();
            foreach ($params as $key => $value) {
                $qs[] = urlencode($key) .'='. urlencode($value);
            }
            $url .= ((strpos($url, '?') === FALSE ? '?' : '&')) . implode('&', $qs);
        }

        return $url;
    }

    function get_order_verification_page_url($order_hash)
    {
        $settings = C_NextGen_Settings::get_instance();
        if ($settings->get('ecommerce_page_order_verification', FALSE))
            return $this->_add_to_querystring(
                $this->get_page_url($settings->get('ecommerce_page_order_verification')),
                array('order' => $order_hash)
            );
        else
            return site_url('/?ngg_pro_verify_page=1&order='.$order_hash);
    }


    function get_cancel_page_url()
    {
        $settings = C_NextGen_Settings::get_instance();

        if ($settings->ecommerce_page_cancel) {
            return $this->get_page_url($settings->ecommerce_page_cancel);
        }
        else {
            return $this->_add_to_querystring(site_url('/?ngg_pro_cancel_page=1'));
        }
    }

    function get_page_url($page_id, $params=array())
    {
        $link = get_page_link($page_id);
        if ($params) $link = $this->_add_to_querystring($link, $params);

        return $link;
    }

    function redirect_to_page($page_id, $params=array())
    {
        wp_redirect($this->get_page_url($page_id, $params));
    }
}