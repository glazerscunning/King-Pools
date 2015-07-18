<?php

class C_NextGen_Pro_Add_To_Cart
{
    function enqueue_static_resources()
    {
        $router = C_Router::get_instance();
        wp_enqueue_script('jquery-ui-accordion');

	    // For some reason ajax.js isn't registered yet in 2.0.67.14 and above, so we have
	    // to do it manually.
	    if (method_exists('M_Ajax', 'register_scripts')) M_Ajax::register_scripts();

        if (version_compare(NGG_PLUGIN_VERSION, '2.0.67') >= 0)
            wp_enqueue_script('ngg-pro-lightbox-ecommerce-overrides', $router->get_static_url('photocrati-nextgen_pro_ecommerce#lightbox_overrides.js'));
        else
            wp_enqueue_script('ngg-pro-lightbox-ecommerce-overrides', $router->get_static_url('photocrati-nextgen_pro_ecommerce#lightbox_overrides.js'), array('ngg-store-js'));
        wp_enqueue_style('ngg-pro-add-to-cart', $router->get_static_url('photocrati-nextgen_pro_ecommerce#add_to_cart.css'));
        M_NextGen_Pro_Ecommerce::enqueue_cart_resources();

        // Determine which lightbox style is in use
        $lightbox_style = '';
        if (class_exists('C_Lightbox_Library_Manager')) {
            $lightbox = C_Lightbox_Library_Manager::get_instance()->get(NGG_PRO_LIGHTBOX);
            $lightbox_style = $lightbox->values['nplModalSettings']['style'];
        }
        else {
            $mapper = C_Lightbox_Library_Mapper::get_instance();
            $library = $mapper->find_by_name(NGG_PRO_LIGHTBOX, TRUE);
            if (isset($library->display_settings['style'])) {
                $lightbox_style = $library->display_settings['style'];
            }
        }

        if ($lightbox_style) wp_enqueue_style('nextgen_pro_lightbox_cart_user_style', $router->get_static_url('photocrati-nextgen_pro_ecommerce#lightbox_styles/' . $lightbox_style));
    }

    function get_sources()
    {
        return array(
            NGG_PRO_MANUAL_PRICELIST_SOURCE     =>  $this->_render_manual_pricelist_template(),
            NGG_PRO_DIGITAL_DOWNLOADS_SOURCE    =>  $this->_render_digital_download_template(),
        );
    }

    function get_i18n_strings()
    {
        $i18n = new stdClass();
        $i18n->add_to_cart  = __('Add To Cart', 'nggallery');
        $i18n->qty_add_desc = __('Change quantities to update your cart.');
        $i18n->checkout     = __('View Cart / Checkout', 'nggallery');
        $i18n->not_for_sale = __('This image is not for sale', 'nggallery');
        $i18n->quantity     = __('Quantity', 'nggallery');
        $i18n->description  = __('Description', 'nggallery');
        $i18n->price        = __('Price', 'nggallery');
        $i18n->total        = __('Total', 'nggallery');
        $i18n->update_cart  = __('Update Cart', 'nggallery');
        return $i18n;
    }

    function _render_manual_pricelist_template()
    {
        $heading    = __('Prints & Products', 'nggallery');
        $id         = NGG_PRO_MANUAL_PRICELIST_SOURCE;

        return "<h3>{$heading}</h3><div class='source_contents' id='{$id}'></div>";
    }

    function _render_digital_download_template()
    {
        $heading        = __('Digital Downloads', 'nggallery');
        $license_terms  = __('View license terms', 'nggallery');
        $id             = NGG_PRO_DIGITAL_DOWNLOADS_SOURCE;

        return "<h3><span id='ngg_digital_downloads_header'>{$heading}</span></h3><div class='source_contents' id='{$id}'></div>";
    }

    function render()
    {
        $template = new C_MVC_View('photocrati-nextgen_pro_ecommerce#add_to_cart', array(
            'not_for_sale_msg'  =>  C_NextGen_Settings::get_instance()->ecommerce_not_for_sale_msg,
            'sources'           =>  $this->get_sources(),
            'i18n'              =>  $this->get_i18n_strings()
        ));
        return $template->render(TRUE);
    }
}