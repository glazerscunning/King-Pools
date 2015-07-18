<?php

class A_Ecommerce_Instructions_Form extends Mixin
{
    function get_title()
    {
        return $this->get_page_heading();
    }

    function get_page_heading()
    {
        return __('Getting Started', 'nggallery');
    }

    function _get_field_names()
    {
        return array('ecommerce_instructions');
    }

    function _render_ecommerce_instructions_field()
    {
        return $this->render_partial('photocrati-nextgen_pro_ecommerce#instructions', array(
            'i18n'  =>  $this->get_i18n_strings()
        ), TRUE);
    }

    function get_i18n_strings()
    {
        $retval = new stdClass();

        $retval->ecomm_header = __("How to create a gallery with ecommerce", 'nggallery');

        $retval->ecomm_step_1 = sprintf(
            __('Configure your %s.', 'nggallery', 'nggallery'),
            sprintf('<a href="%s" id="ecommerce_step_1">%s</a>', admin_url('admin.php?page=ngg_ecommerce_options'), __('ecommerce settings', 'nggallery'))
        );

        $retval->ecomm_step_2 = sprintf(
            __("Create one or more %s.", 'nggallery'),
            sprintf('<a href="%s">%s</a>', admin_url('edit.php?post_type=ngg_pricelist'), __('pricelists', 'nggallery'))
        );

        $retval->ecomm_step_3 = sprintf(
            __("Using the %s page, associate a pricelist with any gallery or image you would like to sell.", 'nggallery'),
            sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=nggallery-manage-gallery'), __('Manage Galleries', 'nggallery'))
        );

        $retval->ecomm_step_4 = __("When adding or editing a gallery via the NextGEN Insert Gallery Window, be sure to enable ecommerce.", 'nggallery');

        $retval->ecomm_step_5 =
            __("Be sure to select ", 'nggallery').sprintf("<a href='%s'>%s</a>", admin_url('admin.php?page=ngg_other_options#lightbox_effects'),__('NextGEN Pro Lightbox', 'nggallery')).__(" as your desired lightbox effect.", 'nggallery');

        $retval->additional_documentation = sprintf(
            __("Additional Documentation on %s", 'nggallery'),
            sprintf("<a target='_blank' href='%s'>%s</a>", 'http://www.nextgen-gallery.com', __('nextgen-gallery.com', 'nggallery'))
        );

        $retval->documentation_links = array(
            'http://www.nextgen-gallery.com/ecommerce-overview' =>  __('Ecommerce Overview', 'nggallery'),
            'http://www.nextgen-gallery.com/ecommerce-settings' =>  __('How to Configure Ecommerce Options', 'nggallery'),
            'http://www.nextgen-gallery.com/create-pricelist'   =>  __('How to Create and Assign a Pricelist', 'nggallery'),
            'http://www.nextgen-gallery.com/add-ecommerce/'     =>  __('How to Add Ecommerce to a Gallery', 'nggallery')
        );

        $retval->proofing_header = __('How to create a proofing gallery', 'nggallery');

        $retval->proofing_step_1 = sprintf(
            __("Configure your %s.", 'nggallery'),
            sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=ngg_ecommerce_options'), __('proofing settings', 'nggallery'))
        );

        $retval->proofing_step_2 = sprintf(
            __("Select %s as your desired lightbox effect.", 'nggallery'),
            sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=ngg_other_options'), __('NextGen Pro Lightbox', 'nggallery'))
        );

        $retval->proofing_step_3 = __("When adding a gallery via the NextGen Insert Gallery Window, click the option to enable proofing.", 'nggallery');

        return $retval;
    }
}