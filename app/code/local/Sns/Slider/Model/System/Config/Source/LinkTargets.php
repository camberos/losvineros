<?php

class Sns_Slider_Model_System_Config_Source_LinkTargets
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'_self',	'label'=>Mage::helper('slider')->__('Same Window')),
        	array('value'=>'_blank','label'=>Mage::helper('slider')->__('New Window')),
			array('value'=>'_popup','label'=>Mage::helper('slider')->__('Popup Window'))
		);
	}
}
