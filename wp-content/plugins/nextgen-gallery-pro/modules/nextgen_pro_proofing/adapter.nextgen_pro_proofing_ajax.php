<?php

/**
 * Provides AJAX actions for the proofing actions
 */
class A_NextGen_Pro_Proofing_Ajax extends Mixin
{
    /**
     * Submits proofed image list
     */
    function submit_proofed_gallery_action()
    {
        $settings = C_NextGen_Settings::get_instance();
        $response = array();
        $proofed_gallery = $this->object->param('proofed_gallery');
        $email = $this->object->param('email');
        $customer_name = $this->object->param('customer_name');
        $referer = $_SERVER['HTTP_REFERER'];

        // Do we have fields to work with?
        if ($this->object->validate_ajax_request()) {
            $image_list = isset($proofed_gallery['image_list']) ? $proofed_gallery['image_list'] : null;
            $file_list = '';

            if (!empty($image_list))
            {
                $i = 0;
                foreach ($image_list as $image_id) {
                    $image = C_Image_Mapper::get_instance()->find($image_id);
                    $name = pathinfo($image->filename);
                    $name = $name['filename'];
                    if ($i == 0)
                        $file_list = $name;
                    else
                        $file_list .= ',' . $name;
                    $i++;
                }
            }

            if ($image_list != null && $email != null)
            {
                $post_title = sprintf(__('Proof request by %1$s (%2$d images)', 'nggallery'), $email, count($image_list));

                $proof_mapper = C_NextGen_Pro_Proofing_Mapper::get_instance();
                $proof = $proof_mapper->create(array(
                    'customer_name'   => $customer_name,
                    'email'           => $email,
                    'proofed_gallery' => $proofed_gallery,
                    'referer'         => $referer,
                    'title'           => $post_title
                ));
                $post_id = $proof_mapper->save($proof);

                if ($post_id)
                {
                    $response['message'] = __('Done', 'nggallery');

                    $confirmation_params = array('proof' => $proof->hash);
                    if (!empty($settings->proofing_page_confirmation))
                    {
                        $confirmation_url = M_NextGen_Pro_Proofing::get_page_url($settings->proofing_page_confirmation, $confirmation_params);
                    }
                    else {
                        $confirmation_url = M_NextGen_Pro_Proofing::add_to_querystring(site_url('/?ngg_pro_proofing_page=1'), $confirmation_params);
                    }

                    // send e-mail to the site admin (get_option(admin_email))
                    $mailman = $this->object->get_registry()->get_utility('I_Nextgen_Mail_Manager');
                    $content = $mailman->create_content();
                    $content->set_subject($post_title);
                    $content->set_property('admin', get_bloginfo('name'));
	                $content->set_property('site_name', get_bloginfo('name'));
                    $content->set_property('file_list', $file_list);
                    $content->set_property('proof_link', $confirmation_url);
                    $content->set_property('user', array(
                        'email' => $email,
                        'name' => $customer_name,
                    ));
                    $content->load_template($settings->proofing_email_template);
                    $mailman->send_mail($content, get_bloginfo('admin_email'));

                    // potentially send email to the submitting user
                    if ($settings->proofing_enable_user_email)
                    {
                        $content = $mailman->create_content();
                        $content->set_subject($settings->proofing_user_email_subject);
                        $content->set_property('proof_link', $confirmation_url);
                        $content->set_property('user', array(
                            'email' => $email,
                            'name' => $customer_name,
                        ));
                        $content->load_template($settings->proofing_user_email_template);
                        $mailman->send_mail($content, $email);
                    }

                    $response['redirect'] = $confirmation_url;
                }
                else {
                    $response['error'] = __('Proof post could not be created', 'nggallery');
                }
            }
            else {
                if (empty($email) || empty($customer_name))
                {
                    $response['error'] = __('Please provide a name and e-mail address', 'nggallery');
                }
                else if ($image_list == null) {
                    // Sanity check, submit button is disabled when 0 images are selected
                    $response['error'] = __('No images selected', 'nggallery');
                }
            }
        }
        else {
            $response['error'] = __('Invalid request', 'nggallery');
        }

        return $response;
    }

    function validate_ajax_request($check_token = false)
    {
        $valid_request = false;
        if (true)
            $valid_request = true;
        return $valid_request;
    }

}
