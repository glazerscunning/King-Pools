<?php

class A_NextGen_Pro_Lightbox_Mail_Form extends Mixin
{
    function get_title()
    {
        return __('E-mail', 'nggallery');
    }

    function get_page_heading()
    {
        return __('E-mail Settings', 'nggallery');
    }

    function _get_field_names()
    {
        return array(
            'ngg_pro_ecommerce_email_notification_subject',
            'ngg_pro_ecommerce_email_notification_recipient',
            'ngg_pro_ecommerce_email_notification_body',
            'ngg_pro_ecommerce_enable_email_receipt',
            'ngg_pro_ecommerce_email_receipt_subject',
            'ngg_pro_ecommerce_email_receipt_body'
        );
    }

    function get_proxy_model()
    {
        $model = new stdClass;
        $model->name = 'ecommerce';
        return $model;
    }

    function get_model()
    {
        return $settings = C_Settings_Model::get_instance();
    }

    function _render_ngg_pro_ecommerce_email_notification_subject_field()
    {
        $model = $this->get_model();

        return $this->_render_text_field(
          $this->get_proxy_model(),
            'email_notification_subject',
            __('Order notification e-mail subject:', 'nggallery'),
            $this->get_model()->ecommerce_email_notification_subject,
            NULL,
            NULL,
            __('Subject', 'nggallery')
        );
    }

    function _render_ngg_pro_ecommerce_email_notification_recipient_field()
    {
        $model = $this->get_model();

        return $this->_render_text_field(
            $this->get_proxy_model(),
            'email_notification_recipient',
            __('Order notification e-mail recipient:', 'nggallery'),
            $this->get_model()->ecommerce_email_notification_recipient,
            NULL,
            NULL,
            'john@example.com'
        );
    }

    function _render_ngg_pro_ecommerce_email_notification_body_field()
    {
        $model = $this->get_model();

        return $this->_render_textarea_field(
            $this->get_proxy_model(),
            'email_notification_body',
            __('Order notification e-mail content:', 'nggallery'),
            $this->get_model()->ecommerce_email_notification_body,
            __("Wrap placeholders in %%param%%. Accepted placeholders: customer_name, email, total_amount, item_count, shipping_street_address, shipping_city, shipping_state, shipping_zip, shipping_country, order_id, hash, order_details_page, admin_email, blog_name, blog_description, blog_url, site_url, home_url, and file_list", 'nggallery'),
            NULL
        );
    }

    function _render_ngg_pro_ecommerce_enable_email_receipt_field()
    {
        $model = $this->get_model();

        return $this->_render_radio_field(
            $this->get_proxy_model(),
            'enable_email_receipt',
            __('Send e-mail receipt to customer?', 'nggallery'),
            $model->ecommerce_enable_email_receipt,
            __('If enabled a receipt will be sent to the customer after successful checkout', 'nggallery')
        );
    }

    function _render_ngg_pro_ecommerce_email_receipt_subject_field()
    {
        $model = $this->get_model();

        return $this->_render_text_field(
            $this->get_proxy_model(),
            'email_receipt_subject',
            __('E-mail subject:', 'nggallery'),
            $this->get_model()->ecommerce_email_receipt_subject,
            NULL,
            $model->ecommerce_enable_email_receipt? FALSE : TRUE,
            __('Subject', 'nggallery')
        );
    }

    function _render_ngg_pro_ecommerce_email_receipt_body_field()
    {
        $model = $this->get_model();

        return $this->_render_textarea_field(
            $this->get_proxy_model(),
            'email_receipt_body',
            __('E-mail content:', 'nggallery'),
            $this->get_model()->ecommerce_email_receipt_body,
            __("Wrap placeholders in %%param%%. Accepted placeholders: customer_name, email, total_amount, item_count, shipping_street_address, shipping_city, shipping_state, shipping_zip, shipping_country, order_id, hash, order_details_page, admin_email, blog_name, blog_description, blog_url, site_url, and home_url", 'nggallery'),
            $model->ecommerce_enable_email_receipt? FALSE : TRUE
        );
    }
}