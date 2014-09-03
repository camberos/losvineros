<?php

class Sns_Korion_Model_System_Config_Source_ListResMenu
{
	public function toOptionArray()
	{
		return array(
			array('value'=>'sidebar', 'label'=>Mage::helper('korion')->__('SideBar')),
			array('value'=>'collapse', 'label'=>Mage::helper('korion')->__('Collapse'))
		);
	}
}
