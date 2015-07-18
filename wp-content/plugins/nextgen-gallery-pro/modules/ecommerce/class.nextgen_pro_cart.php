<?php

class C_NextGen_Pro_Cart
{
    var $_state = array();

    function __construct($json=NULL)
    {
        if ($json) $this->_parse_state($json);
    }

    /**
     * Simplified state to represent the cart:
     * array(
     *  'images'                        =>  array(
     *          1 (image_id)            =>  array(
     *              'items'             =>  array(
     *                  1 (item_id)     =>  array(
     *                      'quantity'  =>  2
     *                  )
     *              ),
     *              'item_ids'          =>  array(
     *                  1 (item_id)
     *              )
     *          )
     *  ),
     *  'image_ids'                     =>  array(
     *          1 (image_id)
     *  )
     * )
     * @var array
     */
    function _parse_state($client_state)
    {
        if (isset($client_state['images']) AND is_array($client_state['images'])) {
            foreach ($client_state['images'] as $image_id => $image_props) {
                $this->add_image($image_id, $image_props);
            }
        }
    }

    function has_items()
    {
        return count($this->_state) ? TRUE : FALSE;
    }

    function add_image($image_id, $image_props)
    {
        // Get the items associated with the image
        $items = array();
        if (is_array($image_props)) {
            unset($image_props['item_ids']);
            if (isset($image_props['items'])) $items = $image_props['items'];
        }
        else {
            unset($image_props->item_ids);
            if (isset($image_props->items)) $items = $image_props->items;
        }

        // Does the image exist?
        if (($image = C_Image_Mapper::get_instance()->find($image_id))) {
            $storage = C_Gallery_Storage::get_instance();

            $image->thumbnail_url = $storage->get_thumbnail_url($image);
            $image->dimensions	  = $storage->get_thumbnail_dimensions($image);
            $image->width         = $image->dimensions['width'];
            $image->height        = $image->dimensions['height'];
            $this->_state[$image_id] = $image;

            foreach ($items as $item_id => $item_props) {
                if (is_numeric($item_id)) $this->add_item($image_id, $item_id, $item_props);
            }
        }
    }

    function add_item($image_id, $item_id, $item_props=array())
    {
        // Treat an object as if it were an array
        if (is_object($item_props)) $item_props = get_object_vars($item_props);

        // Find the item
        $item = C_Pricelist_Item_Mapper::get_instance()->find($item_id);

        // Find the image
        if (($image = C_Image_Mapper::get_instance()->find($image_id)) AND $item) {

            // Ensure that the image has been added
            if (!isset($this->_state[$image_id])) {
                $image->items = array();
                $this->_state[$image_id] = $image;
            }
            else {
                $image = $this->_state[$image_id];
            }

            // Ensure that the image has an items array
            if (!(isset($image->items))) {
                $image->items = array();
            }

            // Ensure that the items source key exists as an array
            if (!(isset($image->items[$item->source]))) {
                $image->items[$item->source] = array();
            }

            // Ensure that the item's pricelist id exists as a key in the array
            if (!isset($image->items[$item->source][$item->pricelist_id])) {
                $image->items[$item->source][$item->pricelist_id] = array();
            }

            // Has the item already been added? If so, increment it's quantity
            if (isset($image->items[$item->source][$item->pricelist_id][$item_id])) {
                $previous_quantity = intval($image->items[$item->source][$item->pricelist_id][$item_id]->quantity);
                $image->items[$item->source][$item->pricelist_id][$item_id]->quantity =
                    $previous_quantity + intval($item_props['quantity']);
            }

            // This is a new item
            else {
                $item->quantity = isset($item_props['quantity']) ? intval($item_props['quantity']) : 1;
                $image->items[$item->source][$item->pricelist_id][$item_id] = $item;
            }
        }
        else unset($this->_state[$image_id]);

    }

    function has_international_shipping_rate()
    {
        $retval     = FALSE;

        $mapper     = C_Pricelist_Mapper::get_instance();
        $sources    = C_Pricelist_Source_Manager::get_instance();

        foreach ($this->_state as $image_id => $image) {
            foreach ($image->items as $source => $items_array) {
                foreach ($items_array as $pricelist_id => $inner_items_array) {
                    foreach ($inner_items_array as $item_id => $item) {
                        $pricelist = $mapper->find($pricelist_id);
                        $field = $sources->get($item->source, 'settings_field');
                        $settings = $pricelist->$field;
                        if (isset($settings['allow_global_shipments']) && $settings['allow_global_shipments']) {
                            $retval = TRUE;
                            break;
                        }
                    }
                    if ($retval) break;
                }
                if ($retval) break;
            }
            if ($retval) break;
        }

        return $retval;
    }

    function to_array($use_home_country=TRUE)
    {
        $subtotal   = $this->get_subtotal();
        $shipping   = $this->get_shipping($use_home_country);
        $total      = $this->get_total($use_home_country);

        $retval = array(
            'images'    =>  array(),
            'image_ids' =>  array(),
            'subtotal'  =>  $subtotal,
            'shipping'  =>  $shipping,
            'total'     =>  $total,
            'allow_international_shipping'  =>  $this->has_international_shipping_rate()
        );

        foreach ($this->_state as $image_id => $image) {
            $items = $image->items;
            $image->item_ids = array();
            $image->items = array();
            foreach ($items as $source => $items_array) {
                foreach ($items_array as $pricelist_id => $inner_items_array) {
                    foreach ($inner_items_array as $item_id => $item) {
                        $image->item_ids[] = $item_id;
                        $image->items[$item_id] = $item;
                    }
                }
            }
            $retval['images'][$image_id] = $image;
            $retval['image_ids'][]       = $image_id;
        }

        return $retval;
    }

    /**
     * Determines if the cart has digital downloads
     * @return bool
     */
    function has_digital_downloads()
    {
        $retval = FALSE;
        $sources    = C_Pricelist_Source_Manager::get_instance();

        foreach ($this->_state as $image_id => $image_props) {
            if (!isset($image_props->items)) $image_props->items = array();
            foreach (array_keys($image_props->items) as $source) {
                if (!$sources->get($source, 'shipping_method')) {
                    $retval = TRUE;
                    break;
                }
            }
        }

        return $retval;
    }

    /**
     * Gets the subtotal of all items in the cart
     * @return float|string
     */
    function get_subtotal()
    {
        $retval = 0;
        $settings = C_NextGen_Settings::get_instance();
        $currency = C_NextGen_Pro_Currencies::$currencies[$settings->ecommerce_currency];

        foreach ($this->_state as $image_id => $image) {
            foreach ($image->items as $source => $pricelists) {
                foreach ($pricelists as $pricelist_id => $items) {
                    foreach ($items as $item_id => $item) {
                        $retval = bcadd($retval, bcmul($item->price, $item->quantity, $currency['exponent']), $currency['exponent']);
                    }
                }
            }
        }

        return $retval;
    }

    function get_shipping($use_home_country=TRUE)
    {
        $retval     =   0;
        $sources    =   C_Pricelist_Source_Manager::get_instance();
        $mapper     =   C_Pricelist_Mapper::get_instance();
        $settings   =   C_NextGen_Settings::get_instance();
        $currency   =   C_NextGen_Pro_Currencies::$currencies[$settings->ecommerce_currency];

        // Consolidate items via pricelist
        $consolidated = array();
        foreach ($this->_state as $image_id => $image) {
            foreach ($image->items as $source => $pricelists) {
                foreach ($pricelists as $pricelist_id => $items) {
                    if (!isset($consolidated[$pricelist_id])) {
                        $consolidated[$pricelist_id] = array();
                    }

                    if (!isset($consolidated[$pricelist_id][$source])) {
                        $consolidated[$pricelist_id][$source] = array();
                    }

                    foreach ($items as $item) $consolidated[$pricelist_id][$source][] = $item;
                }
            }
        }

        // Foreach pricelist, calculate the items shipping
        foreach ($consolidated as $pricelist_id => $source_array) {
            foreach ($source_array as $source => $items) {

                // Normally, we would instantiate the class responsible for calculating
                // the shipping method. But for now, for simplicity sake, we'll just assume that there is
                // only one calculation method
                if (($shipping_klass = $sources->get($source, 'shipping_method'))) {
                    $pricelist      = $mapper->find($pricelist_id);
                    $settings       = $pricelist->manual_settings;

                    // Calculate the item subtotal
                    $subtotal = 0;
                    foreach ($items as $item_id => $item) {
                        $subtotal = bcadd($subtotal, bcmul($item->price, $item->quantity, $currency['exponent']), $currency['exponent']);
                    }

                    // Calculate the shipping cost for local orders
                    $local_rate = 0;
                    if ($settings['domestic_shipping_method'] == 'flat') {
                        $local_rate = bcadd($local_rate, $settings['domestic_shipping_rate'], $currency['exponent']);
                    }
                    else {
                        $local_rate = bcadd($local_rate, bcmul($settings['domestic_shipping_rate'], ($subtotal/100), $currency['exponent']), $currency['exponent']);
                    }

                    // Calculate the shipping cost for international orders
                    $global_rate = 0;
                    if ($settings['global_shipping_method'] == 'flat') {
                        $global_rate = bcadd($global_rate, $settings['global_shipping_rate'], $currency['exponent']);
                    }
                    else {
                        $global_rate = bcadd($global_rate, bcmul($settings['global_shipping_rate'], ($subtotal/100), $currency['exponent']), $currency['exponent']);
                    }

                    // Determine what rate to use. The local rate is used as a minimum rate as well
                    if ($use_home_country) $retval = bcadd($retval, $local_rate, $currency['exponent']);
                    else if ($local_rate > $global_rate) $retval = bcadd($retval, $local_rate, $currency['exponent']);
                    else $retval = bcadd($retval, $global_rate, $currency['exponent']);
                }
            }
        }

        return $retval;
    }

    function get_total($use_home_country=TRUE)
    {
        $settings = C_NextGen_Settings::get_instance();
        $currency = C_NextGen_Pro_Currencies::$currencies[$settings->ecommerce_currency];
        return bcadd($this->get_shipping($use_home_country), $this->get_subtotal(), $currency['exponent']);
    }

    /**
     * Determines if the cart has shippable items
     * @return bool
     */
    function has_shippable_items()
    {
        $retval     = FALSE;
        $sources    = C_Pricelist_Source_Manager::get_instance();

        foreach ($this->_state as $image_id => $image_props) {
            if (!isset($image_props->items)) $image_props->items = array();
            foreach (array_keys($image_props->items) as $source) {
                if ($sources->get($source, 'shipping_method')) {
                    $retval = TRUE;
                    break;
                }
            }
        }

        return $retval;
    }
}