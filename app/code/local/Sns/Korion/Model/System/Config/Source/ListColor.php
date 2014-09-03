<?php

class Sns_Korion_Model_System_Config_Source_ListColor
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'dark-pink', 'label'=>Mage::helper('korion')->__('Dark Pink')),
			array('value'=>'light-blue', 'label'=>Mage::helper('korion')->__('Light Blue')),
			array('value'=>'magenta', 'label'=>Mage::helper('korion')->__('Magenta')),
			array('value'=>'light-green', 'label'=>Mage::helper('korion')->__('Light Green')),
			array('value'=>'dark-blue', 'label'=>Mage::helper('korion')->__('Dark Blue')),
			array('value'=>'black', 'label'=>Mage::helper('korion')->__('Black')),
			array('value'=>'orange', 'label'=>Mage::helper('korion')->__('Orange')),
			array('value'=>'light-red', 'label'=>Mage::helper('korion')->__('Light Red')),
			array('value'=>'purple', 'label'=>Mage::helper('korion')->__('Purple')),
		);
	}
}
