<?php

class Sns_Slider_Model_System_Config_Source_DescriptionSrc
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'description',	'label'=>Mage::helper('slider')->__('Description')),
        	array('value'=>'short_description',	'label'=>Mage::helper('slider')->__('Short Description'))
		);
	}
}
