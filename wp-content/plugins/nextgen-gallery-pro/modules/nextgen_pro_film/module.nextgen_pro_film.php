<?php
/*
{
	Module: photocrati-nextgen_pro_film
}
 */
define('NGG_PRO_FILM', 'photocrati-nextgen_pro_film');
class M_NextGen_Pro_Film extends C_Base_Module
{
	function define($context=FALSE)
	{
		parent::define(
			'photocrati-nextgen_pro_film',
			'NextGEN Pro Film',
			'Provides a film-like gallery for NextGEN Gallery',
            '0.11',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com',
			$context
		);

		include_once('class.nextgen_pro_film_installer.php');
		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Film_Installer');
	}

	function get_type_list()
	{
		return array(
			'A_Nextgen_Pro_Film_Controller' => 'adapter.nextgen_pro_film_controller.php',
			'A_Nextgen_Pro_Film_Dynamic_Styles' => 'adapter.nextgen_pro_film_dynamic_styles.php',
			'A_Nextgen_Pro_Film_Form' => 'adapter.nextgen_pro_film_form.php',
			'A_Nextgen_Pro_Film_Forms' => 'adapter.nextgen_pro_film_forms.php',
			'C_Nextgen_Pro_Film_Installer' => 'class.nextgen_pro_film_installer.php',
			'A_Nextgen_Pro_Film_Mapper' => 'adapter.nextgen_pro_film_mapper.php',
		);
	}

	function _register_adapters()
	{
		$this->get_registry()->add_adapter(
			'I_Display_Type_Mapper',
			'A_NextGen_Pro_Film_Mapper'
		);

        if (!is_admin()) {
            C_Dynamic_Stylesheet_Controller::get_instance('all')
                ->register('nextgen_pro_film', 'photocrati-nextgen_pro_film#nextgen_pro_film_dyncss');

            $this->get_registry()->add_adapter(
                'I_Display_Type_Controller',
                'A_NextGen_Pro_Film_Controller',
                $this->module_id
            );
        }

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            $this->get_registry()->add_adapter(
                'I_Form',
                'A_NextGen_Pro_Film_Form',
                $this->module_id
            );
            $this->get_registry()->add_adapter(
                'I_Form_Manager',
                'A_NextGen_Pro_Film_Forms'
            );
        }
	}
}

new M_NextGen_Pro_Film;
