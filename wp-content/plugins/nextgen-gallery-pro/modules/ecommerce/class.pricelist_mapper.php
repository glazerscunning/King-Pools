<?php

class C_Pricelist_Mapper extends C_CustomPost_DataMapper_Driver
{
	public static $_instances = array();

	static function get_instance($context=FALSE)
	{
		if (!isset(self::$_instances[$context])) {
			$klass = get_class();
			self::$_instances[$context] = new $klass($context);
		}
		return self::$_instances[$context];
	}

	function define($context=FALSE, $not_used=FALSE)
	{
		$object_name = 'ngg_pricelist';

		// Add the object name to the context of the object as well
		// This allows us to adapt the driver itself, if required
		if (!is_array($context)) $context = array($context);
		array_push($context, $object_name);
		parent::define($object_name, $context);
		$this->add_mixin('Mixin_Pricelist_Mapper');

		$this->set_model_factory_method($object_name);

		// Define columns
		$this->define_column('ID', 'BIGINT');
		$this->define_column('post_author', 'BIGINT');
		$this->define_column('title', 'VARCHAR(255)');
		$this->define_column('manual_settings',	'TEXT');
		$this->define_column('digital_download_settings', 'TEXT');

		// Mark the columns which should be unserialized
		$this->add_serialized_column('manual_settings');
		$this->add_serialized_column('digital_download_settings');
	}

	function initialize($context=FALSE)
	{
		parent::initialize('ngg_pricelist');
	}
}

class Mixin_Pricelist_Mapper extends Mixin
{
	function destroy($entity)
	{
		if ($this->call_parent('destroy', $entity)) {
			return $this->destroy_items($entity);
		}
		else return FALSE;
	}

	function destroy_items($pricelist_id, $ids=array())
	{
		global $wpdb;

		// If no ids have been provided, then delete all items for the given pricelist
		if (!$ids) {

			// Ensure we have the pricelist id
			if (!is_int($pricelist_id)) {
				$pricelist_id = $pricelist_id->ID;
			}

			// Find all item ids
			$item_mapper = C_Pricelist_Item_Mapper::get_instance();
			$ids = array();
			$results = $item_mapper->select("ID, post_parent")->
				where(array('pricelist_id = %d', $pricelist_id))->run_query();
			foreach ($results as $row) {
				$ids[] = $row->ID;
				if ($row->post_parent) $ids[] = $row->post_parent;
			}
		}

		// Get unique ids
		$ids = array_unique($ids);

		// Delete all posts and post meta for the item ids
		$sql = array();
		$sql[] = "DELETE FROM {$wpdb->posts} WHERE ID IN (". implode(',', $ids). ')';
		$sql[] = "DELETE FROM {$wpdb->postmeta} WHERE post_id IN (" . implode(',', $ids). ')';
		foreach ($sql as $query) $wpdb->query($query);

		return TRUE;

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

	function find_for_gallery($id, $model=FALSE)
	{
		$retval = NULL;

		if (is_object($id)) {
			$id = $id->{$id->id_field};
		}

		$mapper = C_Gallery_Mapper::get_instance();
		if (($gallery = $mapper->find($id))) {
			if (isset($gallery->pricelist_id)) $retval = $this->object->find($gallery->pricelist_id, $model);
		}
		return $retval;
	}

	function find_for_image($id, $model=FALSE)
	{
		$retval = NULL;
        $image = NULL;

        // Find the image
        if (is_object($id)) {
            $image = $id;
        }
        else {
            $mapper = C_Image_Mapper::get_instance();
            $image = $mapper->find($id);
        }

        // If we've found the image, then find it's pricelist
        if ($image) {
            if ($image->pricelist_id) {
                $retval = $this->object->find($image->pricelist_id, $model);
            }
            else $retval = $this->find_for_gallery($image->galleryid, $model);
        }

		return $retval;
	}

	function set_defaults($entity)
	{
		// Set defaults for manual pricelist settings
		if (!isset($entity->manual_settings)) $entity->manual_settings = array();

		if (!isset($entity->manual_settings['domestic_shipping_method'])) {
			$entity->manual_settings['domestic_shipping_method'] = 'flat';
		}

		if (!array_key_exists('domestic_shipping_rate', $entity->manual_settings)) {
			$entity->manual_settings['domestic_shipping_rate'] = 5.00;
		}

		if (!isset($entity->manual_settings['allow_global_shipments'])) {
			$entity->manual_settings['allow_global_shipments'] = 0;
		}

		if (!isset($entity->manual_settings['global_shipping_method'])) {
			$entity->manual_settings['global_shipping_method'] = 'flat';
		}

		if (!array_key_exists('global_shipping_rate', $entity->manual_settings)) {
			$entity->manual_settings['global_shipping_rate'] = 5.00;
		}

		// Set defaults for digital download settings
		if (!isset($entity->digital_download_settings)) $entity->digital_download_settings = array();

		if (!isset($entity->digital_download_settings['show_licensing_link'])) {
			$entity->digital_download_settings['show_licensing_link'] = 0;
		}

		if (!isset($entity->digital_download_settings['licensing_page_id'])) {
			$entity->digital_download_settings['licensing_page_id'] = 0;
		}

        // Pricelists should be published by default
		$entity->post_status = 'publish';

        // TODO: This should be part of the datamapper actually
        $entity->post_title = $this->get_post_title($entity);
	}
}