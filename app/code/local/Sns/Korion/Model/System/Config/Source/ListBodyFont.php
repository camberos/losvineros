<?php
class Sns_Korion_Model_System_Config_Source_ListBodyFont
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'arial', 'label'=>Mage::helper('korion')->__('Arial')),
			array('value'=>'arial-black', 'label'=>Mage::helper('korion')->__('Arial black')),
			array('value'=>'courier new', 'label'=>Mage::helper('korion')->__('courier New')),
			array('value'=>'georgia', 'label'=>Mage::helper('korion')->__('Georgia')),
			array('value'=>'impact', 'label'=>Mage::helper('korion')->__('Impact')),
			array('value'=>'lucida-console', 'label'=>Mage::helper('korion')->__('Lucida Console')),
			array('value'=>'lucida-grande', 'label'=>Mage::helper('korion')->__('Lucida-grande')),
			array('value'=>'palatino', 'label'=>Mage::helper('korion')->__('Palatino')),
			array('value'=>'tahoma', 'label'=>Mage::helper('korion')->__('Tahoma')),
			array('value'=>'times new roman', 'label'=>Mage::helper('korion')->__('Times')),
			array('value'=>'Trebuchet', 'label'=>Mage::helper('korion')->__('Trebuchet')),
			array('value'=>'Verdana', 'label'=>Mage::helper('korion')->__('Verdana')),
			array('value'=>'segoe ui', 'label'=>Mage::helper('korion')->__('Segoe UI'))
		);
	}
}
