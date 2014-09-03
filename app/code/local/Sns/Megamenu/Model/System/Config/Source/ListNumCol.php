<?php

class Sns_Megamenu_Model_System_Config_Source_ListNumCol extends Varien_Object{
    const MEGACOL1			= 1;
    const MEGACOL2			= 2;
    const MEGACOL3			= 3;
    const MEGACOL4			= 4;
    const MEGACOL5			= 5;
    const MEGACOL6			= 6;
    const MEGACOL7			= 7;
    const MEGACOL8			= 8;
    const MEGACOL9			= 9;
    const MEGACOL10			= 10;
    const MEGACOL11			= 11;
    const MEGACOL12			= 12;
    static public function getOptionArray(){
        return array(
			self::MEGACOL1 		=> Mage::helper('megamenu')->__('1 column'),
			self::MEGACOL2		=> Mage::helper('megamenu')->__('2 columns'),
			self::MEGACOL3		=> Mage::helper('megamenu')->__('3 columns'),
			self::MEGACOL4		=> Mage::helper('megamenu')->__('4 columns'),
			self::MEGACOL5		=> Mage::helper('megamenu')->__('5 columns'),
			self::MEGACOL6		=> Mage::helper('megamenu')->__('6 columns'),
			self::MEGACOL7		=> Mage::helper('megamenu')->__('7 columns'),
			self::MEGACOL8		=> Mage::helper('megamenu')->__('8 columns'),
			self::MEGACOL9		=> Mage::helper('megamenu')->__('9 columns'),
			self::MEGACOL10		=> Mage::helper('megamenu')->__('10 columns'),
			self::MEGACOL11		=> Mage::helper('megamenu')->__('11 columns'),
			self::MEGACOL12		=> Mage::helper('megamenu')->__('12 columns'),
        );
    }
    static public function toOptionArray(){
        return array(
			array(
			  'value'     => self::MEGACOL1,
			  'label'     => Mage::helper('megamenu')->__('1 column'),
			),

			array(
			  'value'     => self::MEGACOL2,
			  'label'     => Mage::helper('megamenu')->__('2 columns'),
			),

			array(
			  'value'     => self::MEGACOL3,
			  'label'     => Mage::helper('megamenu')->__('3 columns'),
			),
			array(
			  'value'     => self::MEGACOL4,
			  'label'     => Mage::helper('megamenu')->__('4 columns'),
			),
			array(
			  'value'     => self::MEGACOL5,
			  'label'     => Mage::helper('megamenu')->__('5 columns'),
			),
			array(
			  'value'     => self::MEGACOL6,
			  'label'     => Mage::helper('megamenu')->__('6 columns'),
			),
			array(
			  'value'     => self::MEGACOL7,
			  'label'     => Mage::helper('megamenu')->__('7 columns'),
			),
			array(
			  'value'     => self::MEGACOL8,
			  'label'     => Mage::helper('megamenu')->__('8 columns'),
			),
			array(
			  'value'     => self::MEGACOL9,
			  'label'     => Mage::helper('megamenu')->__('9 columns'),
			),
			array(
			  'value'     => self::MEGACOL10,
			  'label'     => Mage::helper('megamenu')->__('10 columns'),
			),
			array(
			  'value'     => self::MEGACOL11,
			  'label'     => Mage::helper('megamenu')->__('11 columns'),
			),
			array(
			  'value'     => self::MEGACOL12,
			  'label'     => Mage::helper('megamenu')->__('12 columns'),
			)
        );
    }
}