<?php
class Sns_Megamenu_Block_Adminhtml_Menuitems extends Mage_Adminhtml_Block_Widget_Grid_Container{
	public function __construct(){
		$this->_controller = 'adminhtml_menuitems';
		$this->_blockGroup = 'megamenu';
		$this->_headerText = Mage::helper('megamenu')->__('Menu Items Manager');
		$this->_addButtonLabel = Mage::helper('megamenu')->__('Add Item');
		parent::__construct();
	}
}