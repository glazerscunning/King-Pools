<?php

class Mixin_NextGen_Pro_Album_Controller extends Mixin
{
    function set_param_for($url, $key, $value, $id=NULL, $use_prefix=FALSE)
    {
        $retval = $this->call_parent('set_param_for', $url, $key, $value, $id, $use_prefix);

        // Adjust the return value
	    while (preg_match("#album--([^/]+)#", $retval, $matches)) {
		    $retval = str_replace($matches[0], $matches[1], $retval);
	    }

	    while (preg_match("#gallery--([^/]+)#", $retval, $matches)) {
		    $retval = str_replace($matches[0], $matches[1], $retval);
	    }

        return $retval;
    }

    function _render_gallery($display_type, $original_display_type, $original_settings, $return=FALSE)
    {
		// Try finding the gallery by slug first. If nothing is found, we assume that
		// the user passed in a gallery id instead
		$gallery = $gallery_slug = $this->object->param('gallery');
		$mapper = C_Gallery_Mapper::get_instance();
		$result = reset($mapper->select()->where(array('slug = %s', $gallery))->limit(1)->run_query());
		if ($result) {
			$gallery = $result->{$result->id_field};
		}

        $renderer = C_Displayed_Gallery_Renderer::get_instance();
        return $renderer->display_images(
            array(
                'source'				            => 'galleries',
                'container_ids'			            => array($gallery),
                'display_type'			            => $display_type,
                'original_display_type'	            => $original_display_type,
                'original_settings'                 => $original_settings
            ),
            $return
        );
    }

    function _render_album($displayed_gallery, $return)
    {
        // The HTML id of the gallery
        $id = 'displayed_gallery_'.$displayed_gallery->id();

        // Generate the named thumbnail size
        $thumbnail_size_name = 'thumb';
        if (isset($displayed_gallery->display_settings['override_thumbnail_settings']) &&
            $displayed_gallery->display_settings['override_thumbnail_settings']) {

            $dynthumbs = C_Dynamic_Thumbnails_Manager::get_instance();
            $dyn_params = array(
                'width' => $displayed_gallery->display_settings['thumbnail_width'],
                'height' => $displayed_gallery->display_settings['thumbnail_height'],
            );

            if ($displayed_gallery->display_settings['thumbnail_quality'])
                $dyn_params['quality'] = $displayed_gallery->display_settings['thumbnail_quality'];

            if ($displayed_gallery->display_settings['thumbnail_crop'])
                $dyn_params['crop'] = true;

            if ($displayed_gallery->display_settings['thumbnail_watermark'])
                $dyn_params['watermark'] = true;

            $thumbnail_size_name = $dynthumbs->get_size_name($dyn_params);
        }

        // Get entities
        $entities                       = $this->object->_prepare_entities($displayed_gallery,$thumbnail_size_name);

        // Render view/template
	    $params = array(
		    'entities'				=>	$entities,
		    'effect_code'			=>	$this->object->get_effect_code($displayed_gallery),
		    'id'					=>	$id,
		    'thumbnail_size_name'	=>	$thumbnail_size_name,
		    'css_class'             =>  $this->object->_get_css_class()
	    );
	    $params = $this->object->prepare_display_parameters($displayed_gallery, $params);
        return $this->render_view('photocrati-nextgen_pro_albums#index', $params, $return);
    }


    function _prepare_entities($displayed_gallery, $thumbnail_size_name)
    {
        $current_url= $this->object->get_routed_url(TRUE);
        $storage    = C_Gallery_Storage::get_instance();
        $mapper     = C_Image_Mapper::get_instance();
        $entities   = $displayed_gallery->get_included_entities();


        foreach ($entities as &$entity) {
            $entity_type	= intval($entity->is_gallery) ? 'gallery' : 'album';

            // Is the gallery actually a link to a page? Stupid feature...
            if (isset($entity->pageid) && $entity->pageid > 0) {
                $entity->link = get_page_link($entity->pageid);
            }

            // If not, we'll link to the actual gallery/sub-album
            else {
                $page_url = $current_url;
                if (intval($entity->is_gallery) && !$this->param('album')) {
                    $page_url = $this->object->set_param_for($page_url, 'album', 'galleries');
                }
                $entity->link = $this->object->set_param_for($page_url, $entity_type, $entity->slug);
            }

            $preview_img        = $mapper->find($entity->previewpic);
            $entity->thumb_size = $storage->get_image_dimensions($preview_img, $thumbnail_size_name);
            $entity->url        = $storage->get_image_url($preview_img, $thumbnail_size_name, TRUE);
        }

        return $entities;
    }


    function index_action($displayed_gallery, $return=FALSE)
    {
        $retval = '';

        // Determine what to render:

        // 1) A gallery
        if ($this->param('gallery')) {
            $retval = $this->object->_render_gallery(
                $displayed_gallery->display_settings['gallery_display_type'],
                $displayed_gallery->display_type,
                $displayed_gallery->display_settings,
                TRUE
            );
        }

        // 2) A sub-album
        else if (($album_id = $this->param('album'))) {
            if (!is_numeric($album_id)) {
                $mapper = C_Album_Mapper::get_instance();
                $result = array_pop($mapper->select()->where(array("slug = %s", $album_id))->limit(1)->run_query());
                $album_id = $result->{$result->id_field};
            }
            $displayed_gallery->container_ids = array($album_id);
            $retval = $this->object->_render_album($displayed_gallery, $return);
        }

        // 3) The current album
        else {
            $retval = $this->object->_render_album($displayed_gallery, $return);
        }

        return $retval;
    }
}