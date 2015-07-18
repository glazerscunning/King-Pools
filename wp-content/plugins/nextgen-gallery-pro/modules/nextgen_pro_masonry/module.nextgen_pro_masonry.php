<?php
/***
{
    Module: photocrati-nextgen_pro_masonry,
    Depends: { photocrati-nextgen_gallery_display }
}
***/

define('NGG_PRO_MASONRY', 'photocrati-nextgen_pro_masonry');

class M_NextGen_Pro_Masonry extends C_Base_Module
{
    function define()
    {
        parent::define(
            'photocrati-nextgen_pro_masonry',
            'NextGEN Pro Masonry',
            'Provides the NextGEN Pro Masonry Display Type',
            '0.10',
            'http://www.nextgen-gallery.com',
            'Photocrati Media',
            'http://www.photocrati.com'
        );

		include_once('class.nextgen_pro_masonry_installer.php');
		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Masonry_Installer');
    }

    /**
     * Register adapters
     */
    function _register_adapters()
    {
        // Add display type
        $this->get_registry()->add_adapter('I_Display_Type_Mapper', 'A_NextGen_Pro_Masonry_Mapper');

        if (!is_admin()) {
            // Add controller
            $this->get_registry()->add_adapter(
                'I_Display_Type_Controller',
                'A_NextGen_Pro_Masonry_Controller',
                $this->module_id
            );
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            // Add settings form
            $this->get_registry()->add_adapter(
                'I_Form',
                'A_NextGen_Pro_Masonry_Form',
                $this->module_id
            );
            $this->get_registry()->add_adapter(
                'I_Form_Manager',
                'A_NextGen_Pro_Masonry_Forms'
            );
        }
    }

    function get_type_list()
    {
        return array(
            'A_Nextgen_Pro_Masonry_Controller' => 'adapter.nextgen_pro_masonry_controller.php',
            'A_Nextgen_Pro_Masonry_Form' => 'adapter.nextgen_pro_masonry_form.php',
            'A_Nextgen_Pro_Masonry_Forms' => 'adapter.nextgen_pro_masonry_forms.php',
            'C_NextGen_Pro_Masonry_Installer' => 'class.nextgen_pro_masonry_installer.php',
            'A_Nextgen_Pro_Masonry_Mapper' => 'adapter.nextgen_pro_masonry_mapper.php'
        );
    }
}

new M_NextGen_Pro_Masonry();
