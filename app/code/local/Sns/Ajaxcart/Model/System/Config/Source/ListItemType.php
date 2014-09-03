<?php

class Sns_Ajaxcart_Model_System_Config_Source_ListItemType
{
	public function toOptionArray()
	{
		return array(
				array('value'=>'all', 'label'=>Mage::helper('ajaxcart')->__('All Products')),
				array('value'=>'recent', 'label'=>Mage::helper('ajaxcart')->__('Recent Products'))
		);
	}
}
