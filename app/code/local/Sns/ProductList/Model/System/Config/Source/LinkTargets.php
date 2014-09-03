<?php

class Sns_ProductList_Model_System_Config_Source_LinkTargets
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'_self',	'label'=>Mage::helper('productlist')->__('Same Window')),
        	array('value'=>'_blank','label'=>Mage::helper('productlist')->__('New Window')),
			array('value'=>'_popup','label'=>Mage::helper('productlist')->__('Popup Window'))
		);
	}
}
