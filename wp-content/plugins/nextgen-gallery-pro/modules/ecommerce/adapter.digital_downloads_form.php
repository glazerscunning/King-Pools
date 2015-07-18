<?php

class A_Digital_Downloads_Form extends Mixin
{
	function get_title()
	{
		return __('Digital Downloads', 'nggallery');
	}

	function _get_field_names()
	{
		return array(
			'digital_downloads'
		);
	}

    function save_action()
    {
        return $this->get_model()->is_valid();
    }

    function enqueue_static_resources()
    {
        wp_enqueue_script(
            'nextgen_pro_lightbox_digital_downloads_form_settings',
            $this->get_static_url('photocrati-nextgen_pro_ecommerce#ecommerce_downloads_settings.js'),
            array('jquery-ui-tooltip')
        );
    }

	function get_i18n_strings()
	{
		$i18n = new stdClass;

		$i18n->show_licensing_link 		= __('Display link to license terms?', 'nggallery');
		$i18n->licensing_page		 	= __('Licensing page:', 'nggallery');
		$i18n->name_header			 	= __('Name:', 'nggallery');
		$i18n->price_header			 	= __('Price:', 'nggallery');
		$i18n->resolution_header	 	= __('Longest Image Dimension:', 'nggallery');
        $i18n->resolution_tooltip       = __('A setting of 0px will deliver full-resolution images');
		$i18n->resolution_placeholder	= __('Enter 0 for maximum', 'nggallery');
		$i18n->item_title_placeholder	= __('Enter title of the item', 'nggallery');
		$i18n->delete					= __('Delete', 'nggallery');
		$i18n->add_another_item			= __('Add another item', 'nggallery');
		$i18n->no_items					= __('No items available for this source.', 'nggallery');

		return $i18n;
	}

	function get_image_resolutions()
	{
		$retval = array('100'	=>	'Full');
		for($i=90; $i>0; $i-=10) {
			$retval[$i] = "{$i}%";
		}
		return $retval;
	}

	function get_pages()
	{
		return get_pages(array('number'=>100));
	}

	function _render_digital_downloads_field()
	{
		$items = $this->get_model()->get_digital_downloads();
		if (!$items) {
			$item = new stdClass;
			$item->ID = uniqid('new-');
			$item->title = "";
			$item->price = "";
			$item->resolution = 0;
			$item->source = NGG_PRO_DIGITAL_DOWNLOADS_SOURCE;
			$item->post_status = 'publish';
			$items[] = $item;
		}

		return $this->object->render_partial('photocrati-nextgen_pro_ecommerce#digital_downloads', array(
			'items'					=>	$items,
			'settings'				=>	$this->get_model()->digital_download_settings,
			'i18n'					=>	$this->object->get_i18n_strings(),
			'image_resolutions'		=>	$this->object->get_image_resolutions(),
			'pages'					=>	$this->object->get_pages(),
			'item_source'				=>	NGG_PRO_DIGITAL_DOWNLOADS_SOURCE
		), TRUE);
	}
}