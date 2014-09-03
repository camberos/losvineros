<?php

class Sns_ProductList_Model_System_Config_Source_OrderBy
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'position',	'label' => Mage::helper('productlist')->__('Position')),
			array('value' => 'created_at', 	'label' => Mage::helper('productlist')->__('Date Created')),
			array('value' => 'name', 		'label' => Mage::helper('productlist')->__('Name')),
			array('value' => 'price', 		'label' => Mage::helper('productlist')->__('Price')),
			array('value' => 'random', 		'label' => Mage::helper('productlist')->__('Random')),
			array('value' => 'top_rating', 	'label' => Mage::helper('productlist')->__('Top Rating')),
			array('value' => 'most_reviewed',	'label' => Mage::helper('productlist')->__('Most Reviews')),
			array('value' => 'most_viewed',	'label' => Mage::helper('productlist')->__('Most Viewed')),
			array('value' => 'best_sales',	'label' => Mage::helper('productlist')->__('Most Selling')),
		);
	}
}
