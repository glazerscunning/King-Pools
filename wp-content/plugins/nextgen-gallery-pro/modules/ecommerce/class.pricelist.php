<?php

class C_Pricelist extends C_DataMapper_Model
{
	var $_mapper_interface = 'I_Pricelist_Mapper';

	function define($properties=array(), $mapper=FALSE, $context=FALSE)
	{
		parent::define($mapper, $properties, $context);
		$this->implement('I_Pricelist');
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

    /**
     * Gets all items from all sources for the pricelist, optionally filtered by an image
     * @param null $image
     * @return array
     */
    function get_items($image=NULL)
	{
        $retval = array();

        foreach (C_Pricelist_Source_Manager::get_instance()->get_ids() as $id) {
            $method = "get_{$id}_items";
            if ($this->has_method($method)) {
                $items = $this->$method($image);
                $retval = array_merge($retval, $items);
            }
        }

        return $retval;
	}

	function delete_items($ids=array())
	{
		$this->get_mapper()->destroy_items($this->id(), $ids);
	}

	function destroy_items($ids=array())
	{
		return $this->delete_items($ids);
	}

    function get_ngg_manual_pricelist_items($image)
    {
        return $this->get_manual_items($image);
    }

    function get_ngg_digital_downloads_items($image)
    {
        return $this->get_digital_downloads($image);
    }

    /**
     * Gets all manual items of the pricelist
     * @param null $image
     * @return mixed
     */
    function get_manual_items($image=NULL)
	{
        $mapper = C_Pricelist_Item_Mapper::get_instance();
        $conditions = array(
            array("pricelist_id = %d", $this->object->id()),
            array("source IN %s", array(NGG_PRO_MANUAL_PRICELIST_SOURCE))
        );

        return $mapper->select()->where($conditions)->order_by('ID', 'ASC')->run_query();
	}

    /**
     * Gets all digital downloads for the pricelist
     * @param null $image_id
     * @return mixed
     */
    function get_digital_downloads($image_id=NULL)
	{
        // Find digital download items
        $mapper = C_Pricelist_Item_Mapper::get_instance();
        $conditions = array(
            array("pricelist_id = %d", $this->object->id()),
            array("source IN %s", array(NGG_PRO_DIGITAL_DOWNLOADS_SOURCE))
        );
        $items = $mapper->select()->where($conditions)->order_by('ID', 'ASC')->run_query();

        // Filter by image resolutions
        if ($image_id) {
            $image = is_object($image_id) ? $image_id : C_Image_Mapper::get_instance()->find($image_id);
            if ($image) {
                $retval = array();
                $storage = C_Gallery_Storage::get_instance();
                foreach ($items as $item) {
                    $source_width = $image->meta_data['width'];
                    $source_height = $image->meta_data['height'];

                    // the downloads themselves come from the backup as source so if possible only filter images
                    // whose backup file doesn't have sufficient dimensions
                    $backup_abspath = $storage->get_backup_abspath($image);
                    if (@file_exists($backup_abspath))
                    {
                        $dimensions = @getimagesize($backup_abspath);
                        $source_width = $dimensions[0];
                        $source_height = $dimensions[1];
                    }

                    if (isset($item->resolution) && $item->resolution >= 0 && ($source_height >= $item->resolution OR $source_width >= $item->resolution)) {
                        $retval[] = $item;
                    }
                }
                $items = $retval;
            }
        }

        return $items;
	}

	function validate()
	{
		$this->validates_presence_of('title');
	}
}