<?php

class Sns_Ajaxcart_Model_System_Config_Source_ListEffectSelector
{
	public function toOptionArray()
	{
		return array(
				array('value'=>'click', 'label'=>Mage::helper('ajaxcart')->__('Click')),
				array('value'=>'hover', 'label'=>Mage::helper('ajaxcart')->__('Hover'))
		);
	}
}
