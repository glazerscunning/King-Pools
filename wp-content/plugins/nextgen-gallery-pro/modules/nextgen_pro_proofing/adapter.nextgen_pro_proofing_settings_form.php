<?php

class A_NextGen_Pro_Proofing_Settings_Form extends Mixin
{
    function get_title()
    {
        return $this->get_page_heading();
    }

    function get_page_heading()
    {
        return __('Proofing', 'nggallery');
    }

    function _get_field_names()
    {
        $fields = array(
            'proofing_page_confirmation',
            'proofing_trigger_text',
            'proofing_user_confirmation_not_found',
            'proofing_user_confirmation_template',
            'proofing_enable_user_email',
            'proofing_user_email_subject',
            'proofing_user_email_template',
            'proofing_email_template',
            'proofing_lightbox_active_color'
        );
        return $fields;
    }

    function enqueue_static_resources()
    {
        wp_enqueue_style(
            'photocrati-nextgen_pro_proofing_options_style',
            $this->get_static_url('photocrati-nextgen_pro_proofing#options.css')
        );
        wp_enqueue_script(
            'photocrati-nextgen_pro_proofing_options_script',
            $this->get_static_url('photocrati-nextgen_pro_proofing#options.js'),
            array('jquery.nextgen_radio_toggle')
        );
    }

    function save_action()
    {
        if ($changes = $this->param('proofing'))
        {
            $settings = C_NextGen_Settings::get_instance();
            foreach ($changes as $key => $value) {
                $key = "proofing_{$key}";
                $settings->$key = $value;
            }

            if ($changes['page_confirmation'] == '')
                $settings->proofing_page_confirmation = $this->create_new_page(
                    __('Proofed Images', 'nggallery'),
                    '[ngg_pro_proofing]'
                );
            else
                $this->add_shortcode_to_post(
                    $settings->proofing_page_confirmation = $changes['page_confirmation'],
                    '[ngg_pro_proofing]'
                );

            $settings->save();
        }
    }

    function create_new_page($title, $content)
    {
        global $user_ID;
        return wp_insert_post(array(
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'post_content'   => $content,
            'post_author'    => $user_ID,
            'post_title'     => $title,
            'comment_status' => 'closed'
        ));
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

    function _retrieve_page_list()
    {
        $pages = get_pages();
        $options = array('' => 'Create new');
        foreach ($pages as $page) {
            $options[$page->ID] = $page->post_title;
        }
        return $options;
    }

    function _render_proofing_page_confirmation_field($model)
    {
        $model = new stdClass;
        $model->name = 'proofing';
        $pages = $this->_retrieve_page_list();
        return $this->_render_select_field(
            $model,
            'page_confirmation',
            __('Confirmation page', 'nggallery'),
            $pages,
            C_NextGen_Settings::get_instance()->proofing_page_confirmation,
            __("This page requires the [ngg_pro_proofing] shortcode, which will be automatically added if not already present. Selecting \"Create new\" will create a new page that will appear in your Primary Menu unless you've customized your menu settings: http://codex.wordpress.org/Appearance_Menus_SubPanel", 'nggallery')
        );
    }

    function _render_proofing_lightbox_active_color_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_color_field(
            $model,
            'lightbox_active_color',
            __('Pro Lightbox icon color', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_lightbox_active_color,
            __('When the NextGen Pro Lightbox is active an additional icon is added for image proofing, this controls the color of that icon for chosen images', 'nggallery')
        );
    }

    function _render_proofing_trigger_text_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_text_field(
            $model,
            'trigger_text',
            __('Trigger text', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_trigger_text
        );
    }

    function _render_proofing_email_template_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_textarea_field(
            $model,
            'email_template',
            __('Admin email message', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_email_template,
            __('Possible substitution fields: admin, file_list, proof_link, user_name, user_email', 'nggallery')
        );
    }

    function _render_proofing_enable_user_email_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_radio_field(
            $model,
            'enable_user_email',
            __('Send confirmation to users', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_enable_user_email
        );
    }

    function _render_proofing_user_email_subject_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_text_field(
            $model,
            'user_email_subject',
            __('Confirmation subject', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_user_email_subject,
            '',
            !C_NextGen_Settings::get_instance()->proofing_enable_user_email ? TRUE : FALSE
        );
    }

    function _render_proofing_user_email_template_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_textarea_field(
            $model,
            'user_email_template',
            __('Confirmation email', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_user_email_template,
            __('Possible substition fields: user_name, user_email, proof_link', 'nggallery'),
            !C_NextGen_Settings::get_instance()->proofing_enable_user_email ? TRUE : FALSE
        );
    }

    function _render_proofing_user_confirmation_not_found_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_text_field(
            $model,
            'user_confirmation_not_found',
            __('Not found message', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_user_confirmation_not_found,
            'This is displayed to users viewing the proofing page without a valid proofing to view'
        );
    }

    function _render_proofing_user_confirmation_template_field()
    {
        $model = new stdClass;
        $model->name = 'proofing';
        return $this->_render_textarea_field(
            $model,
            'user_confirmation_template',
            __('Confirmation template', 'nggallery'),
            C_NextGen_Settings::get_instance()->proofing_user_confirmation_template,
            __('Possible substition fields: user_name, user_email, proof_link, proof_details', 'nggallery')
        );
    }
}
