<?php

class Sns_Ajaxcart_Model_System_Config_Source_ListEffectToggle
{
	public function toOptionArray()
	{
		return array(
				array('value'=>'slide', 'label'=>Mage::helper('ajaxcart')->__('Slide')),
				array('value'=>'blind', 'label'=>Mage::helper('ajaxcart')->__('Blind')),
				array('value'=>'appear', 'label'=>Mage::helper('ajaxcart')->__('Appear'))
		);
	}
}
