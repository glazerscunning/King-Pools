<?php

class A_NextGen_Pro_Lightbox_Transient_Creator
{
	function cache_action($displayed_gallery)
	{
		$this->call_parent();
		$displayed_gallery->to_transient();
	}
}