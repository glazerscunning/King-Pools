<?php

class A_Ecommerce_Factory extends Mixin
{
    function ngg_pricelist($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        return new C_Pricelist($properties, $mapper, $context);
    }

    function pricelist($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        return $this->ngg_pricelist($properties, $mapper, $context);
    }

    function ngg_pricelist_item($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        return new C_Pricelist_Item($properties, $mapper, $context);
    }

    function pricelist_item($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        return $this->pricelist_item($properties, $mapper, $context);
    }

    function ngg_order($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        return new C_NextGen_Pro_Order($properties, $mapper, $context);
    }

    function order($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        return $this->ngg_order($properties, $mapper, $context);
    }
}