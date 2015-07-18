<?php

class A_Ecommerce_Ajax extends Mixin
{
    /**
     * Read an image file into memory and display it
     *
     * This is necessary for htaccess or server-side protection that blocks access to filenames ending with "_backup"
     * At the moment it only supports the backup or full size image.
     */
    function get_image_file_action()
    {
        $order_id = $this->param('order_id', FALSE);
        $image_id = $this->param('image_id', FALSE);

        $bail = FALSE;
        if (!$order_id || !$image_id)
            $bail = TRUE;

        $order = C_Order_Mapper::get_instance()->find_by_hash($order_id);

        if (!in_array($image_id, $order->cart['image_ids']))
            $bail = TRUE;
        if ($order->status != 'verified')
            $bail = TRUE;
        if ($bail)
        {
            header('HTTP/1.1 404 Not found');
            exit;
        }

        $storage = C_Gallery_Storage::get_instance();
        if (version_compare(NGG_PLUGIN_VERSION, '2.0.66.99') <= 0)
        {
            // Pre 2.0.67 didn't fallback to the original path if the backup file didn't exist
            $imagemapper = C_Image_Mapper::get_instance();
            $fs = C_Fs::get_instance();
            $image = $imagemapper->find($image_id);
            $gallery_path = $storage->get_gallery_abspath($image->galleryid);
            $abspath = $fs->join_paths($gallery_path, $image->filename . '_backup');
            if (!@file_exists($abspath))
                $abspath = $storage->get_image_abspath($image_id, 'full');
        }
        else {
            $abspath = $storage->get_image_abspath($image_id, 'backup');
        }

        $mimetype = 'application/octet';
        if (function_exists('finfo_buffer'))
        {
            $finfo = new finfo(FILEINFO_MIME);
            $mimetype = @$finfo->file($abspath);
        }
        elseif (function_exists('mime_content_type')) {
            $mimetype = @mime_content_type($abspath);
        }

        header('Content-Description: File Transfer');
	    header('Content-Disposition: attachment; filename='.basename($storage->get_image_abspath($image_id, 'full')));
        header("Content-type: " . $mimetype);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . @filesize($abspath));

        readfile($abspath);
        exit;
    }

    function get_digital_download_settings_action()
    {
        $retval = array();
        if (($pricelist = C_Pricelist_Mapper::get_instance()->find_for_image($this->param('image_id')))) {
            $retval = $pricelist->digital_download_settings;
            $retval['header'] = esc_html(__('Digital Downloads', 'nggallery'));
            if (intval($retval['show_licensing_link']) > 0) {
                $retval['licensing_link'] = get_page_link($retval['licensing_page_id']);
                $view_licensing_terms = __('View license terms', 'nggallery');
                $retval['header'] .= " <a href='{$retval['licensing_link']}'>($view_licensing_terms)</a>";
            }
        }
        return $retval;
    }

	function get_cart_items_action()
	{
        $cart = new C_NextGen_Pro_Cart($this->param('cart'));
        return $cart->to_array();
	}

    function get_shipping_amount_action()
    {
        $cart = new C_NextGen_Pro_Cart($this->param('cart'));
        return array('shipping' => $cart->get_shipping($this->param('use_home_country')));
    }

    function get_image_items_action()
    {
        $retval = array();
        if (($image_id = $this->param('image_id'))) {
            $cart = $this->param('cart');
            if (($pricelist = C_Pricelist_Mapper::get_instance()->find_for_image($image_id, TRUE))) {
                $retval = $pricelist->get_items($image_id);

                // Determine if the item is in the cart. If so, set the item's quantity
                if (isset($cart['images'][$image_id])) {
                    foreach ($retval as &$item) {
                        foreach ($cart['images'][$image_id]['items'] as $item_id => $item_props) {
                            if ($item->{$item->id_field} == $item_id) {
                                $item->quantity = $item_props['quantity'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $retval;
    }

    function is_order_verified_action()
    {
        $retval = array('verified' => FALSE);

        if (($order = C_Order_Mapper::get_instance()->find_by_hash($this->param('order')))) {
            if ($order->status == 'verified') {
                $retval['verified'] = TRUE;
                $checkout = C_NextGen_Pro_Checkout::get_instance();
                $retval['thank_you_page_url'] = $checkout->get_thank_you_page_url($order->hash, TRUE);
            }
        }
        else $retval['error'] = __("We're sorry, but we couldn't find your order.", 'nggallery');

        return $retval;
    }
}