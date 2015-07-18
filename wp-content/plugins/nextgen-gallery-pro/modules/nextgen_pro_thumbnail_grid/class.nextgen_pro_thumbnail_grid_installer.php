<?php

class C_NextGen_Pro_Thumbnail_Grid_Installer extends C_Gallery_Display_Installer
{
	function install($reset=FALSE)
	{
        $this->install_display_types();
    }

    function install_display_types()
    {
		$this->install_display_type(
			NGG_PRO_THUMBNAIL_GRID, array(
				'title'							=>	__('NextGEN Pro Thumbnail Grid', 'nggallery'),
				'entity_types'					=>	array('image'),
				'preview_image_relpath'			=>	'photocrati-nextgen_pro_thumbnail_grid#preview.jpg',
				'default_source'				=>	'galleries',
                'hidden_from_ui'                =>  FALSE,
				'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10)
			)
		);
	}

	function uninstall($hard=FALSE)
	{
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_THUMBNAIL_GRID))) {
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
