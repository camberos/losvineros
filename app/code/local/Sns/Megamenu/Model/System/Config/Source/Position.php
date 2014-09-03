<?php

class Sns_Megamenu_Model_System_Config_Source_Position extends Varien_Object{
    const BEFORE	= 1;
    const AFTER		= 2;
	const FIRST		= 3;

    static public function getOptionArray(){
        return array(
            self::BEFORE    => Mage::helper('megamenu')->__('Before'),
            self::AFTER   	=> Mage::helper('megamenu')->__('After'),
			self::FIRST   	=> Mage::helper('megamenu')->__('First')
        );
    }
    static public function toOptionArray(){
        return array(
			array(
			  'value'     => self::BEFORE,
			  'label'     => Mage::helper('megamenu')->__('Before'),
			),
			array(
			  'value'     => self::AFTER,
			  'label'     => Mage::helper('megamenu')->__('After'),
			),
			array(
			  'value'     => self::FIRST,
			  'label'     => Mage::helper('megamenu')->__('First'),
			),
		);
    }
}