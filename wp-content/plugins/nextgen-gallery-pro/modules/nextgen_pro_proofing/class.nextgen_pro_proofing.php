<?php

class C_NextGen_Pro_Proofing extends C_DataMapper_Model
{
    var $_mapper_interface = 'I_NextGen_Pro_Proofing_Mapper';

    function define($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        parent::define($mapper, $properties, $context);
    }

    function initialize($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        if (!$mapper)
            $mapper = $this->get_registry()->get_utility($this->_mapper_interface);
        parent::initialize($mapper, $properties);

    }
}