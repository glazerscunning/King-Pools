<?php

class A_Manual_Pricelist_Form extends Mixin
{
    function get_title()
    {
        return __('Manual Pricelist', 'nggallery');
    }

    function _get_field_names()
    {
        return array(
            'manual_pricelist_items'
        );
    }

    function get_i18n_strings()
    {
        $i18n = new stdClass;

        $i18n->name_header				= __('Name', 'nggallery');
        $i18n->price_header				= __('Price', 'nggallery');
        $i18n->item_title_placeholder	= __('Enter title of the item', 'nggallery');
        $i18n->delete					= __('Delete', 'nggallery');
        $i18n->add_another_item			= __('Add another item', 'nggallery');
        $i18n->no_items					= __('No items available for this source.', 'nggallery');
        $i18n->domestic_shipping		= __('Domestic shipping rate:', 'nggallery');
        $i18n->global_shipping			= __('International shipping rate:', 'nggallery');
        $i18n->allow_global_shipping	= __('Enable international shipping rate?', 'nggallery');

        return $i18n;
    }

    function save_action()
    {
        return $this->get_model()->is_valid();
    }

    function _render_manual_pricelist_items_field()
    {
        $items = $this->get_model()->get_manual_items();
        if (!$items) {
            $item = new stdClass;
            $item->ID = uniqid('new-');
            $item->title = "";
            $item->price = "";
            $item->source = NGG_PRO_MANUAL_PRICELIST_SOURCE;
            $item->post_status = 'publish';
            $items[] = $item;
        }

        return $this->object->render_partial('photocrati-nextgen_pro_ecommerce#manual_pricelist', array(
            'items'				=>	$items,
            'manual_settings'	=>	$this->get_model()->manual_settings,
            'i18n'				=>	$this->get_i18n_strings(),
            'shipping_methods'	=>	$this->object->get_shipping_methods(),
            'item_source'		=>	NGG_PRO_MANUAL_PRICELIST_SOURCE
        ), TRUE);
    }

    function get_shipping_methods()
    {
        return array(
            'flat'			=>	__('Flat Rate', 'nggallery'),
            'percentage'	=>	__('Percentage', 'nggallery')
        );
    }

    function enqueue_static_resources()
    {
        wp_enqueue_style( 'ngg-pro-lightbox-admin', $this->object->get_static_url('photocrati-nextgen_pro_ecommerce#admin.css'));
        wp_enqueue_script('ngg-pro-lightbox-admin', $this->object->get_static_url('photocrati-nextgen_pro_ecommerce#admin.js'));
    }
}