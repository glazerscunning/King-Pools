<?php

class C_NextGen_Pro_Blog_Installer extends C_Gallery_Display_Installer
{
	function install()
	{
        $this->install_display_types();
    }

    function install_display_types()
    {
		$this->install_display_type(
			NGG_PRO_BLOG_GALLERY, array(
				'title'							=>	__('NextGEN Pro Blog Style', 'nggallery'),
				'entity_types'					=>	array('image'),
				'default_source'				=>	'galleries',
				'preview_image_relpath'			=>	'photocrati-nextgen_pro_blog_gallery#preview.jpg',
                'hidden_from_ui'                =>  FALSE,
				'view_order' => NGG_DISPLAY_PRIORITY_BASE + (NGG_DISPLAY_PRIORITY_STEP * 10) + 40
			)
		);
	}

	function uninstall($hard)
	{
        $mapper = C_Display_Type_Mapper::get_instance();
        if (($entity = $mapper->find_by_name(NGG_PRO_BLOG_GALLERY))) {
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
