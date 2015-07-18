<?php

class C_NextGen_Pro_Order extends C_DataMapper_Model
{
	var $_mapper_interface = 'I_Order_Mapper';
    var $_cart             = NULL;

	function define($properties=array(), $mapper=FALSE, $context=FALSE)
	{
		parent::define($mapper, $properties, $context);
		$this->implement('I_Order');
	}

	function initialize($properties=array(), $mapper=FALSE, $context=FALSE)
	{
		// If no mapper was specified, then get the mapper
		if (!$mapper) $mapper = $this->get_registry()->get_utility($this->_mapper_interface);

		// Construct the model
		parent::initialize($mapper, $properties);

        if (is_object($properties) && isset($properties->cart)) {
            $this->_cart = new C_NextGen_Pro_Cart($properties->cart);
        }
        elseif (is_array($properties) && isset($properties['cart'])) {
            $this->_cart = new C_NextGen_Pro_Cart($properties['cart']);
        }
	}

    function get_cart()
    {
        return $this->_cart;
    }
}