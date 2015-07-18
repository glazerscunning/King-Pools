<?php

class C_Pricelist_Item_Mapper extends C_CustomPost_DataMapper_Driver
{
	public static $_instances = array();

	public static function get_instance($context=FALSE)
	{
		if (!isset(self::$_instances[$context])) {
			self::$_instances[$context] = new C_Pricelist_Item_Mapper();
		}
		return self::$_instances[$context];
	}

	function define($context=FALSE)
	{
		$object_name = 'ngg_pricelist_item';

		// Add the object name to the context of the object as well
		// This allows us to adapt the driver itself, if required
		if (!is_array($context)) $context = array($context);
		array_push($context, $object_name);
		parent::define($object_name, $context);

		$this->set_model_factory_method($object_name);

		// Define columns
		$this->define_column('ID', 'BIGINT', 0);
		$this->define_column('pricelist_id', 'BIGINT', 0);
		$this->define_column('price', 'DECIMAL', 0.00);
		$this->define_column('source', 'VARCHAR(255)');
		$this->define_column('resolution', 'DECIMAL');
        $this->define_column('is_shippable', 'BOOLEAN', FALSE);
	}

	function initialize($context=FALSE)
	{
		parent::initialize('ngg_pricelist_item');
	}

	/**
	 * Uses the title attribute as the post title
	 * @param stdClass $entity
	 * @return string
	 */
	function get_post_title($entity)
	{
		return $entity->title;
	}
}