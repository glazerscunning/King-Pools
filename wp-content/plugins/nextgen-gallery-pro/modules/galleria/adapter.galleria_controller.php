<?php

class A_Galleria_Controller extends Mixin
{
	function enqueue_frontend_resources($displayed_gallery)
	{
        // Add some properties to the displayed gallery
        $this->object->_compute_aspect_ratio($displayed_gallery);

        // 2.0.67 gives get_url() more content types than home & site
        if (version_compare(NGG_PLUGIN_VERSION, '2.0.67') >= 0)
        {
            $displayed_gallery->iframe_url = $this->object->get_router()->get_url(
                '/nextgen-galleria-gallery/' . $displayed_gallery->id(),
                TRUE, 'root'
            );

            // If the site was accessed as http://foobar.com, but the site url configured in
            // WordPress is http://www.foobar.com, the browser will emit cross origin framing permission
            // errors. If NGG_ALLOW_CROSS_ORIGIN_FRAMING, we'll detect that and allow the request
            if (NGG_ALLOW_CROSS_ORIGIN_FRAMING) {
                if (strpos($displayed_gallery->iframe_url, '?') === FALSE) {
                    $displayed_gallery->iframe_url .= '?';
                }
                $displayed_gallery->iframe_url .= 'ngg_fix_cross_origins=1';
            }
        }
        else {
            $displayed_gallery->iframe_url = $this->object->get_router()->get_url(
                '/nextgen-galleria-gallery/' . $displayed_gallery->id()
            );
        }

        // Include ngg_common.js
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);

        // Galleria uses an iframe, which is controlled/loaded by the parent
        wp_enqueue_script('galleria_parent', $this->get_static_url('photocrati-galleria#galleria_parent.js'), array('jquery'));

		$this->enqueue_ngg_styles();
	}
	
	function _compute_aspect_ratio($displayed_gallery, $type = null)
	{
		$storage = C_Gallery_Storage::get_instance();
		$list = $displayed_gallery->get_included_entities();
		
		if ($type == null)
		{
			$type = !empty($displayed_gallery->display_settings['aspect_ratio']) ? $displayed_gallery->display_settings['aspect_ratio'] : 'image_average';
		}
		
		switch ($type)
		{
			case 'first_image':
			{
				if ($list != null)
				{
					$image = $list[0];
					$dims = $storage->get_image_dimensions($image);
					$ratio = round($dims['width'] / $dims['height'], 2);
			
					$displayed_gallery->display_settings['aspect_ratio_computed'] = $ratio;
				}
				
				break;
			}
			case 'image_average':
			{
				if ($list != null)
				{
					$computed_ratio = 0;
				
					foreach ($list as $image)
					{
						$dims = $storage->get_image_dimensions($image);
						$ratio = round($dims['width'] / $dims['height'], 2);
						
						if ($computed_ratio == 0)
						{
							$computed_ratio = $ratio;
						}
						else
						{
							if (abs($computed_ratio - $ratio) > 0.001)
							{
								$computed_ratio = ($computed_ratio + $ratio) / 2;
							}
						}
					}
					
					if ($computed_ratio > 0)
					{
						$displayed_gallery->display_settings['aspect_ratio_computed'] = $computed_ratio;
					}
				}
				
				break;
			}
		}
	}

	function index_action($displayed_gallery, $return=FALSE)
	{
		$storage = C_Gallery_Storage::get_instance();
		$list = $displayed_gallery->get_included_entities();

		$params = array(
			'images'				=> $list,
			'displayed_gallery_id'	=>	$displayed_gallery->id(),
			'storage'				=>	$storage,
			'custom_css_rules'		=>	$this->object->get_custom_css_rules($displayed_gallery),
            'thumbnail_size_name'   =>  'thumbnail'
		);

        $params = $this->object->prepare_display_parameters($displayed_gallery, $params);
		return $this->object->render_view('photocrati-galleria#galleria', $params, $return);

	}

	function get_custom_css_rules($displayed_gallery)
	{
		return '';
	}
}
