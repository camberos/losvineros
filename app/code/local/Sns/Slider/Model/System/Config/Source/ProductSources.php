<?php

class Sns_Slider_Model_System_Config_Source_ProductSources
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'catalog',	'label'=>Mage::helper('slider')->__('Catalog')),
        	array('value'=>'product',	'label'=>Mage::helper('slider')->__('Product'))
		);
	}
}
