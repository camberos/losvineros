<?php

class Sns_ProductList_Model_System_Config_Source_DescriptionSrc
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'description',	'label'=>Mage::helper('productlist')->__('Description')),
        	array('value'=>'short_description',	'label'=>Mage::helper('productlist')->__('Short Description'))
		);
	}
}
