<?php

class A_Ecommerce_Options_Form extends Mixin
{
    function get_title()
    {
        return $this->get_page_heading();
    }

    function get_page_heading()
    {
        return __('General Options', 'nggallery');
    }

    function _get_field_names()
    {
        return array(
            'nextgen_pro_ecommerce_home_country',
            'nextgen_pro_ecommerce_currency',
            'nextgen_pro_ecommerce_page_checkout',
            'nextgen_pro_ecommerce_page_thanks',
            'nextgen_pro_ecommerce_page_cancel',
            'nextgen_pro_ecommerce_page_digital_downloads',
            'nextgen_pro_ecommerce_not_for_sale_msg',
        );
    }

    function save_action()
    {
        $ecommerce = $this->param('ecommerce');
        if (empty($ecommerce))
            return;

        $settings = C_NextGen_Settings::get_instance();
        $settings->ecommerce_home_country  = $ecommerce['home_country'];
        $settings->ecommerce_currency      = $ecommerce['currency'];

        if ($ecommerce['page_checkout'] == '')
            $settings->ecommerce_page_checkout = $this->create_new_page('Shopping Cart', '[ngg_pro_checkout]');
        else {
            $this->add_shortcode_to_post(
                $settings->ecommerce_page_checkout = $ecommerce['page_checkout'],
                '[ngg_pro_checkout]'
            );
        }

        if ($ecommerce['page_thanks'] == '')
            $settings->ecommerce_page_thanks = $this->create_new_page('Thanks', '[ngg_pro_order_details]');
        else {
            $this->add_shortcode_to_post(
                $settings->ecommerce_page_thanks = $ecommerce['page_thanks'],
                '[ngg_pro_order_details]'
            );
        }

        if ($ecommerce['page_cancel'] == '')
            $settings->ecommerce_page_cancel = $this->create_new_page('Order Cancelled', __('You order was cancelled.', 'nggallery'));
        else {
            $this->add_shortcode_to_post(
                $settings->ecommerce_page_cancel = $ecommerce['page_cancel'],
                __('Your order was cancelled', 'nggallery'),
                TRUE
            );
        }

        if ($ecommerce['page_digital_downloads'] == '')
            $settings->ecommerce_page_digital_downloads = $this->create_new_page('Digital Downloads', __('[ngg_pro_digital_downloads]'));
        else {
            $this->add_shortcode_to_post(
                $settings->ecommerce_page_digital_downloads = $ecommerce['page_digital_downloads'],
                '[ngg_pro_digital_downloads]'
            );
        }

        $settings->save();
    }

    function add_shortcode_to_post($post_id, $shortcode, $only_if_empty=FALSE)
    {
        if (($post = get_post($post_id))) {
            if ($only_if_empty) {
                if (strlen($post->post_content) == 0) {
                    $post->post_content .= "\n".$shortcode;
                    wp_update_post($post);
                }
            }
            elseif (strpos($post->post_content, $shortcode) === FALSE) {
                $post->post_content .= "\n".$shortcode;
                wp_update_post($post);
            }
        }
    }

    function enqueue_static_resources()
    {
        wp_enqueue_style(
          'photocrati-nextgen_pro_ecommerce_options',
           $this->get_static_url('photocrati-nextgen_pro_ecommerce#ecommerce_options.css')
        );

        wp_enqueue_script(
            'photocrati-nextgen_pro_ecommerce_options-settings-js',
            $this->get_static_url('photocrati-nextgen_pro_ecommerce#ecommerce_options_form_settings.js'),
            array('jquery', 'jquery-ui-tooltip')
        );

        wp_localize_script(
            'photocrati-nextgen_pro_ecommerce_options-settings-js',
            'NGG_Pro_EComm_Settings',
            array(
                'iso_4217_countries' => C_NextGen_Pro_Currencies::$countries
            )
        );
    }

    function create_new_page($title, $content)
    {
        global $user_ID;

        $page = array(
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'post_content'   => $content,
            'post_author'    => $user_ID,
            'post_title'     => $title,
            'comment_status' => 'closed'
        );

        return wp_insert_post($page);
    }

    function _render_nextgen_pro_ecommerce_not_for_sale_msg_field()
    {
        $settings = C_NextGen_Settings::get_instance();

        // _render_select_field only needs $model->name
        $model = new stdClass;
        $model->name = 'ecommerce';

        return $this->_render_textarea_field(
            $model,
            'not_for_sale_msg',
            "\"Not for sale\" Message",
            $settings->ecommerce_not_for_sale_msg
        );
    }

    function _render_nextgen_pro_ecommerce_home_country_field($model)
    {
        $settings = C_NextGen_Settings::get_instance();

        // _render_select_field only needs $model->name
        $model = new stdClass;
        $model->name = 'ecommerce';

        $countries = array();
        foreach (C_NextGen_Pro_Currencies::$countries as $country) {
            $countries[$country['id']] = $country['name'];
        }

        return $this->_render_select_field(
            $model,
            'home_country',
            __('Home Country', 'nggallery'),
            $countries,
            $settings->ecommerce_home_country
        );
    }

    function _retrieve_page_list()
    {
        $pages = get_pages();

		$options = array('' => 'Create new');
		foreach ($pages as $page) {
            $options[$page->ID] = $page->post_title;
        }

        return $options;
    }

    function _render_nextgen_pro_ecommerce_currency_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';

        $currencies = array();
        foreach (C_NextGen_Pro_Currencies::$currencies as $id => $currency) {
            $currencies[$id] = $currency['name'];
        }

        return $this->_render_select_field(
            $model,
            'currency',
            __('Currency', 'nggallery'),
            $currencies,
            C_NextGen_Settings::get_instance()->ecommerce_currency
        );
    }

    function _render_nextgen_pro_ecommerce_page_checkout_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        $pages = $this->_retrieve_page_list();
        return $this->_render_select_field(
            $model,
            'page_checkout',
            __('Checkout page', 'nggallery'),
            $pages,
            C_NextGen_Settings::get_instance()->ecommerce_page_checkout,
            __("This page requires the [ngg_pro_checkout] shortcode, which will be automatically added if not already present. Selecting \"Create new\" will create a new page that will appear in your Primary Menu unless you've customized your menu settings: http://codex.wordpress.org/Appearance_Menus_SubPanel", 'nggallery')
        );
    }

    function _render_nextgen_pro_ecommerce_page_thanks_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        $pages = $this->_retrieve_page_list();
        return $this->_render_select_field(
            $model,
            'page_thanks',
            __('Thank-you page', 'nggallery'),
            $pages,
            C_NextGen_Settings::get_instance()->ecommerce_page_thanks,
            __("This page should have the [ngg_pro_order_details] shortcode, which will be automatically added if not already present. Selecting \"Create new\" will create a new page that will appear in your Primary Menu unless you've customized your menu settings: http://codex.wordpress.org/Appearance_Menus_SubPanel", 'nggallery')
        );
    }

    function _render_nextgen_pro_ecommerce_page_cancel_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        $pages = $this->_retrieve_page_list();
        return $this->_render_select_field(
            $model,
            'page_cancel',
            __('Cancel page', 'nggallery'),
            $pages,
            C_NextGen_Settings::get_instance()->ecommerce_page_cancel,
            __("Selecting \"Create new\" will create a new page that will appear in your Primary Menu unless you've customized your menu settings: http://codex.wordpress.org/Appearance_Menus_SubPanel", 'nggallery')
        );
    }

    function _render_nextgen_pro_ecommerce_page_digital_downloads_field($model)
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        $pages = $this->_retrieve_page_list();
        return $this->_render_select_field(
            $model,
            'page_digital_downloads',
            __('Digital downloads page', 'nggallery'),
            $pages,
            C_NextGen_Settings::get_instance()->ecommerce_page_digital_downloads,
            __("This page requires the [ngg_pro_digital_downloads] shortcode, which will be automatically added if not already present. Selecting \"Create new\" will create a new page that will appear in your Primary Menu unless you've customized your menu settings: http://codex.wordpress.org/Appearance_Menus_SubPanel", 'nggallery')
        );
    }
}
