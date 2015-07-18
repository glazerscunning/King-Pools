<?php

class C_NextGen_Pro_Slideshow_Installer extends C_Gallery_Display_Installer
{
	function install($reset=FALSE)
	{
        $this->install_display_types();
    }

    function install_display_types()
    {
		$this->install_display_type(
			NGG_PRO_SLIDESHOW, array(
				'title'							=>	__('NextGEN Pro Slideshow', 'nggallery'),
				'entity_types'					=>	array('image'),
				'default_source'				=>	'galleries',
				'preview_image_relpath'			=>	'photocrati-nextgen_pro_slideshow#preview.jpg',
                'hidden_from_ui'                =>  FALSE,
				'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 10
			)
		);
	}

	function uninstall($hard=FALSE)
	{
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_SLIDESHOW))) {
            if ($hard)
            {
                $mapper->destroy($entity);
            }
            else {
                $entity->hidden_from_ui = TRUE;
                $mapper->save($entity);
            }
        }
	}
}
