<?php

class C_NextGen_Pro_Proofing_Installer
{
    function install()
    {
        $settings = C_NextGen_Settings::get_instance();
        $settings->set_default_value('proofing_page_confirmation', '');
        $settings->set_default_value('proofing_lightbox_active_color', '#ffff00');
        $settings->set_default_value('proofing_trigger_text', __('Submit proofs', 'nggallery'));
        $settings->set_default_value('proofing_email_template', 'Hi %%admin%% Administrator,

%%user_name%% has submitted images from a proofing gallery.

You can find the proofed images at %%proof_link%%

Here is a comma separated list of the image file names. You can copy and
paste this in your favorite image management software to quickly search for
and find all selected images.

Files: %%file_list%%');

        $settings->set_default_value('proofing_enable_user_email', 0);
        $settings->set_default_value('proofing_user_email_subject', __('Confirmation of image proof', 'nggallery'));
        $settings->set_default_value('proofing_user_email_template', 'Hello %%user_name%%,

This is confirmation that you have selected and submitted the following
images from one of our proofing galleries: %%proof_link%%

Thanks very much!');

        $settings->set_default_value(
            'proofing_user_confirmation_template',
            '<p>%%user_name%% has submitted the following images for proofing. <a href="%%proof_link%%">Go back</a></p>

 %%proof_details%%'
        );
        $settings->set_default_value(
            'proofing_user_confirmation_not_found',
            __('Oops! This page usually displays details for image proofs, but you have not proofed any images yet. Please feel free to continue browsing. Thanks for visiting.', 'nggallery')
        );
    }
}