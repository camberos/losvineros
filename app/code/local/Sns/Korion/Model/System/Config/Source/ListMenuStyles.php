<?php

class Sns_Korion_Model_System_Config_Source_ListMenuStyles
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'base', 'label'=>Mage::helper('korion')->__('Base')),
			array('value'=>'mega', 'label'=>Mage::helper('korion')->__('Mega'))
		);
	}
}
