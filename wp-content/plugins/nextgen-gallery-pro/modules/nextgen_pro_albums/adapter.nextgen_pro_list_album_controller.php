<?php

class A_NextGen_Pro_List_Album_Controller extends Mixin_NextGen_Pro_Album_Controller
{
    function _get_css_class()
    {
        return 'nextgen_pro_list_album';
    }

	function enqueue_frontend_resources($displayed_gallery)
	{
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
		wp_enqueue_style('nextgen_pro_list_album', $this->get_static_url('photocrati-nextgen_pro_albums#nextgen_pro_list_album.css'));
        wp_enqueue_script('nextgen_pro_albums', $this->get_static_url('photocrati-nextgen_pro_albums#nextgen_pro_album_init.js'));

		// Enqueue the dynamic stylesheet
		$dyn_styles = C_Dynamic_Stylesheet_Controller::get_instance('all');
		$dyn_styles->enqueue($this->object->_get_css_class(), $this->array_merge_assoc(
			$displayed_gallery->display_settings,
			array('id' => 'displayed_gallery_'.$displayed_gallery->id())
		));

		$this->enqueue_ngg_styles();
	}
}