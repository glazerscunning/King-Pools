<?php

class C_Order_Mapper extends C_CustomPost_DataMapper_Driver
{
	public static $_instances = array();
	public static function get_instance($context=FALSE)
	{
		if (!isset(self::$_instances[$context])) {
			$klass = get_class();
			self::$_instances[$context] = new $klass($context);
		}
		return self::$_instances[$context];
	}

	function define($context=FALSE)
	{
		$object_name = 'ngg_order';

		// Add the object name to the context of the object as well
		// This allows us to adapt the driver itself, if required
		if (!is_array($context)) $context = array($context);
		array_push($context, $object_name);
		parent::define($object_name, $context);
        $this->add_mixin('Mixin_Order_Mapper');

		$this->set_model_factory_method($object_name);

		// Define columns/properties
		$this->define_column('ID', 'BIGINT', 0);
		$this->define_column('email', 'VARCHAR(255)');
		$this->define_column('customer_name', 'VARCHAR(255');
		$this->define_column('phone', 'VARCHAR(255)');
		$this->define_column('total_amount', 'DECIMAL', 0.0);
		$this->define_column('payment_gateway', 'VARCHAR(255)');
		$this->define_column('shipping_street_address', 'VARCHAR(255)');
		$this->define_column('shipping_address_line', 'VARCHAR(255)');
		$this->define_column('shipping_city', 'VARCHAR(255)');
		$this->define_column('shipping_state', 'VARCHAR(255)');
		$this->define_column('shipping_zip', 'VARCHAR(255)');
		$this->define_column('shipping_country', 'VARCHAR(255)');
		$this->define_column('shipping_phone', 'VARCHAR(255)');
		$this->define_column('cart', 'TEXT');
        $this->define_column('hash', 'VARCHAR(255)');
		$this->define_column('gateway_admin_note', 'VARCHAR(255)');

		$this->add_serialized_column('cart');
	}

	function initialize($context=FALSE)
	{
		parent::initialize('ngg_order');
	}

    function find_by_hash($hash, $model=FALSE)
    {
        $results = $this->select()->where(array("hash = %s", $hash))->run_query(NULL, $model);
        return array_pop($results);
    }
}

class Mixin_Order_Mapper extends Mixin
{
    function _save_entity($entity)
    {
        // Create a unique hash
        if (!property_exists($entity, 'hash') OR !$entity->hash)
            $entity->hash = md5(time() . $entity->email . (is_string($this->cart) ? $this->cart : json_encode($this->cart)));

        return $this->call_parent('_save_entity', $entity);
    }

    /**
     * Uses the title attribute as the post title
     * @param stdClass $entity
     * @return string
     */
    function get_post_title($entity)
    {
        return $entity->customer_name;
    }

    function set_defaults($entity)
    {
        // Pricelists should be published by default
        $entity->post_status = 'publish';

        // TODO: This should be part of the datamapper actually
        $entity->post_title = $this->get_post_title($entity);
    }
}