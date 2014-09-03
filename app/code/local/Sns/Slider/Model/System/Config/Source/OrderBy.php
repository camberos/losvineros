<?php

class Sns_Slider_Model_System_Config_Source_OrderBy
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'position',	'label' => Mage::helper('slider')->__('Position')),
			array('value' => 'created_at', 	'label' => Mage::helper('slider')->__('Date Created')),
			array('value' => 'name', 		'label' => Mage::helper('slider')->__('Name')),
			array('value' => 'price', 		'label' => Mage::helper('slider')->__('Price')),
			array('value' => 'random', 		'label' => Mage::helper('slider')->__('Random')),
			array('value' => 'top_rating', 	'label' => Mage::helper('slider')->__('Top Rating')),
			array('value' => 'most_reviewed',	'label' => Mage::helper('slider')->__('Most Reviews')),
			array('value' => 'most_viewed',	'label' => Mage::helper('slider')->__('Most Viewed')),
			array('value' => 'best_sales',	'label' => Mage::helper('slider')->__('Most Selling')),
		);
	}
}
