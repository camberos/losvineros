<?php

class Sns_Megamenu_Model_System_Config_Source_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('megamenu')->__('Yes'),
            self::STATUS_DISABLED   => Mage::helper('megamenu')->__('No')
        );
    }
    static public function toOptionArray()
    {
        return array(
			array(
			  'value'     => self::STATUS_ENABLED,
			  'label'     => Mage::helper('megamenu')->__('Yes'),
			),
			array(
			  'value'     => self::STATUS_DISABLED,
			  'label'     => Mage::helper('megamenu')->__('No'),
			),
		);
    }
}