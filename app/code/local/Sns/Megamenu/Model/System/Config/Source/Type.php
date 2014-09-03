<?php

class Sns_Megamenu_Model_System_Config_Source_Type extends Varien_Object
{
    const NORMAL		= 1;
    const EXTERNALLINK	= 2;
    const PRODUCT		= 3;
    const CATEGORY		= 4;
    const CMSPAGE		= 5;
    const STATICBLOCK	= 6;
	const CONTENT		= 7;

    static public function getOptionArray()
    {
        return array(
			self::NORMAL 		=> Mage::helper('megamenu')->__('Default'),
			self::EXTERNALLINK	=> Mage::helper('megamenu')->__('External Link'),
			self::PRODUCT		=> Mage::helper('megamenu')->__('Product'),
			self::CATEGORY		=> Mage::helper('megamenu')->__('Category'),
			self::CMSPAGE		=> Mage::helper('megamenu')->__('CMS Page'),
			self::STATICBLOCK	=> Mage::helper('megamenu')->__('Static Block'),
			self::CONTENT		=> Mage::helper('megamenu')->__('Content'),
        );
    }
    static public function toOptionArray()
    {
        return array(
			array(
			  'value'     => self::NORMAL,
			  'label'     => Mage::helper('megamenu')->__('Default'),
			),

			array(
			  'value'     => self::EXTERNALLINK,
			  'label'     => Mage::helper('megamenu')->__('External Link'),
			),

			array(
			  'value'     => self::PRODUCT,
			  'label'     => Mage::helper('megamenu')->__('Product'),
			),
			array(
			  'value'     => self::CATEGORY,
			  'label'     => Mage::helper('megamenu')->__('Category'),
			),
			array(
			  'value'     => self::CMSPAGE,
			  'label'     => Mage::helper('megamenu')->__('CMS Page'),
			),
			array(
			  'value'     => self::STATICBLOCK,
			  'label'     => Mage::helper('megamenu')->__('Static Block'),
			),
			array(
			  'value'     => self::CONTENT,
			  'label'     => Mage::helper('megamenu')->__('Content'),
			),
        );
    }
}