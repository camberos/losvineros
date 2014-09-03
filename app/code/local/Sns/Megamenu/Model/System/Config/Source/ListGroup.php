<?php

class Sns_Megamenu_Model_System_Config_Source_ListGroup extends Varien_Object{
	static public function getOptionArray(){
		foreach (Mage::getModel('megamenu/menugroup')->getCollection() as $group){
			$arr[$group ->getTitle()] = $group ->getTitle();
		}
		return $arr;
	}
	static public function toOptionArray(){
    	$arr[] = array(
			'value'			=>	'',
			'label'     	=>	Mage::helper('megamenu')->__('--Please Select--'),
		);
		foreach (Mage::getModel('megamenu/menugroup')->getCollection() as $group){
			$arr[] = array(
				'value'		=>	$group ->getId(),
				'label'     => 	$group ->getTitle(),
			);
		}
		return $arr;
	}
}