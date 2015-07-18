<?php

class C_Pricelist_Source_Manager
{
    static $_instance   = NULL;
    var $_registered    = array();

    /**
     * @return C_Pricelist_Source_Manager
     */
    static function get_instance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new C_Pricelist_Source_Manager();
        }
        return self::$_instance;
    }

    /**
     * Registers a pricelist source with the system
     * @param $id
     * @param array $properties
     */
    function register($id, $properties=array())
    {
        $this->_registered[$id] = $properties;
    }

    /**
     * Deregisters a pricelist source with the system
     * @param $id
     */
    function deregister($id)
    {
        unset($this->_registered[$id]);
    }


    /**
     * Updates a source properties
     * @param $id
     * @param array $properties
     */
    function update($id, $properties=array())
    {
        $retval = FALSE;

        if (isset($this->_registered[$id])) {
            foreach ($properties as $k=>$v) $this->_registered[$id][$k] = $v;
            $retval = TRUE;
        }

        return $retval;
    }

    /**
     * Gets all or a specific property of a pricelist source
     * @param $id
     * @param bool $property
     * @return null
     */
    function get($id, $property=FALSE)
    {
        $retval = NULL;

        if (isset($this->_registered[$id])) {
            if ($property && isset($this->_registered[$id][$property]))
                $retval = $this->_registered[$id][$property];
            else if (!$property) {
                $retval = $this->_registered[$id];
            }
        }

        return $retval;
    }

    /**
     * Gets ids of all registered sources
     * @return array
     */
    function get_ids()
    {
        return array_keys($this->_registered);
    }
}