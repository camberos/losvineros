<?php

class Sns_ProductList_Model_System_Config_Source_ProductSources
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'catalog',	'label'=>Mage::helper('productlist')->__('Catalog')),
        	array('value'=>'product',	'label'=>Mage::helper('productlist')->__('Product'))
		);
	}
}
