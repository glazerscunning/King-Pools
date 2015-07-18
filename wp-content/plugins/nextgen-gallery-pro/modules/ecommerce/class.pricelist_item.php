<?php

class C_Pricelist_Item extends C_DataMapper_Model
{
	var $_mapper_interface = 'I_Pricelist_Item_Mapper';

	function define($properties=array(), $mapper=FALSE, $context=FALSE)
	{
		parent::define($mapper, $properties, $context);
		$this->implement('I_Pricelist_Item');
	}

	/**
	 * Initializes a display type with properties
	 * @param FALSE|C_Display_Type_Mapper $mapper
	 * @param array|stdClass|C_Display_Type $properties
	 * @param FALSE|string|array $context
	 */
	function initialize($properties=array(), $mapper=FALSE, $context=FALSE)
	{
		// If no mapper was specified, then get the mapper
		if (!$mapper) $mapper = $this->get_registry()->get_utility($this->_mapper_interface);

		// Construct the model
		parent::initialize($mapper, $properties);
	}

	function validation()
	{
		$this->validates_presence_of('title');
		$this->validates_presence_of('price');
		$this->validates_presence_of('source');
		$this->validates_presence_of('pricelist_id');
        $this->validates_numericality_of('price', 0.0, '>');
	}
}