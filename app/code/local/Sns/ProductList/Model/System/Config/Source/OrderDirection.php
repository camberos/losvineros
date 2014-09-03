<?php


class Sns_ProductList_Model_System_Config_Source_OrderDirection
{
	public function toOptionArray()
	{
		return array(
			array('value' => 'asc',			'label' => Mage::helper('productlist')->__('Asc')),
			array('value' => 'desc', 		'label' => Mage::helper('productlist')->__('Desc'))
		);
	}
}
