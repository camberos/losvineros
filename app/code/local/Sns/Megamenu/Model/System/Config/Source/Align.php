<?php

class Sns_Megamenu_Model_System_Config_Source_Align extends Varien_Object{
    const LEFT	= 1;
    const RIGHT	= 2;

    static public function getOptionArray()
    {
        return array(
            self::LEFT    => Mage::helper('megamenu')->__('Left'),
            self::RIGHT   => Mage::helper('megamenu')->__('Right')
        );
    }
    static public function toOptionArray(){
        return array(
			array(
			  'value'     => self::LEFT,
			  'label'     => Mage::helper('megamenu')->__('Left'),
			),
			array(
			  'value'     => self::RIGHT,
			  'label'     => Mage::helper('megamenu')->__('Right'),
			),
		);
    }
}