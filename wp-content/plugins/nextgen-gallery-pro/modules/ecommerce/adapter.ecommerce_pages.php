<?php

class A_Ecommerce_Pages extends Mixin
{
	function setup()
	{
        $this->object->add(NGG_PRO_ECOMMERCE_OPTIONS_PAGE, array(
            'adapter'   => 'A_Ecommerce_Options_Controller',
            'parent'    => 'ngg_ecommerce_options',
            'add_menu'  =>  TRUE
        ));

        $this->object->add('ngg_manage_pricelists', array(
			'url'        => '/edit.php?post_type=ngg_pricelist',
			'menu_title' => __('Manage Pricelists', 'nggallery'),
			'permission' => 'NextGEN Change options',
			'parent'     => 'ngg_ecommerce_options'
		));

        $this->object->add('ngg_manage_orders', array(
            'url'           =>  '/edit.php?post_type=ngg_order',
            'menu_title'    =>  __('View Orders', 'nggallery'),
            'permission'    =>  'NextGEN Change options',
            'parent'        =>  'ngg_ecommerce_options'
        ));

        $this->object->add('ngg_manage_proofs', array(
            'url'        => '/edit.php?post_type=nextgen_proof',
            'menu_title' => __('View Proofs', 'nggallery'),
            'permission' => 'NextGEN Change options',
            'parent'     => 'ngg_ecommerce_options'
        ));

        $this->object->add(NGG_PRO_ECOMMERCE_INSTRUCTIONS_PAGE, array(
            'adapter'   =>  'A_Ecommerce_Instructions_Controller',
            'parent'    =>  'ngg_ecommerce_options'
        ));

        return $this->call_parent('setup');
	}
}