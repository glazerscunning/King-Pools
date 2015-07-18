<?php

class C_NextGen_Pro_Proofing_Mapper extends C_CustomPost_DataMapper_Driver
{
    public static $_instances = array();

    public static function get_instance($context=FALSE)
    {
        if (!isset(self::$_instances[$context])) {
            self::$_instances[$context] = new C_NextGen_Pro_Proofing_Mapper();
        }
        return self::$_instances[$context];
    }

    function define($context=FALSE)
    {
        $object_name = 'nextgen_proof';
        if (!is_array($context))
            $context = array($context);
        array_push($context, $object_name);
        parent::define($object_name, $context);
        $this->add_mixin('Mixin_Proofing_Mapper');
        $this->set_model_factory_method($object_name);

        $this->define_column('ID',              'BIGINT', 0);
        $this->define_column('title',           'VARCHAR(255)');
        $this->define_column('email',           'VARCHAR(255)');
        $this->define_column('customer_name',   'VARCHAR(255)');
        $this->define_column('proofed_gallery', 'VARCHAR(255)');
        $this->define_column('referer',         'VARCHAR(255)');
        $this->define_column('hash',            'VARCHAR(255)');
    }

    function initialize($context=FALSE)
    {
        parent::initialize('nextgen_proof');
    }

    function find_by_hash($hash, $model=FALSE)
    {
        return array_pop(
            $this->select()
                 ->where(array("hash = %s", $hash))
                 ->run_query(NULL, $model)
        );
    }
}

class Mixin_Proofing_Mapper extends Mixin
{
    function _save_entity($entity)
    {
        if (empty($entity->hash))
            $entity->hash = md5(time() . $entity->email . $entity->customer_name);
        return $this->call_parent('_save_entity', $entity);
    }

    function set_defaults($entity)
    {
        $entity->post_status = 'publish';
        $entity->post_title = $this->get_post_title($entity);
    }

    function get_post_title($entity)
    {
        return $entity->title;
    }
}