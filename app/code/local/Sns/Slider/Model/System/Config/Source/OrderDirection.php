<?php


class Sns_Slider_Model_System_Config_Source_OrderDirection
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'asc',			'label' => Mage::helper('slider')->__('Asc')),
			array('value' => 'desc', 		'label' => Mage::helper('slider')->__('Desc'))
		);
	}
}
