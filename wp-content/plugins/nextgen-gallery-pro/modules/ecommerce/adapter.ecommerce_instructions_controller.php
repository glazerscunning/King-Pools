<?php

class A_Ecommerce_Instructions_Controller extends C_NextGen_Admin_Page_Controller
{
    function get_page_title()
    {
        return __('Instructions');
    }

    function get_page_heading()
    {
        return __('Instructions', 'nggallery');
    }

    function get_required_permission()
    {
        return 'NextGEN Change options';
    }
}