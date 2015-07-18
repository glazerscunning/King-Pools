<?php

class A_Ecommerce_Image extends Mixin
{
	function define_columns()
	{
		$this->object->define_column('pricelist_id', 'BIGINT', 0, TRUE);
	}
}