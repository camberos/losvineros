<?php
class Sns_Megamenu_Block_Adminhtml_Menugroup extends Mage_Adminhtml_Block_Widget_Grid_Container{
	public function __construct(){
		$this->_controller = 'adminhtml_menugroup';
		$this->_blockGroup = 'megamenu';
		$this->_headerText = Mage::helper('megamenu')->__('Menu Manager');
		$this->_addButtonLabel = Mage::helper('megamenu')->__('Add Group');
		parent::__construct();
	}
}