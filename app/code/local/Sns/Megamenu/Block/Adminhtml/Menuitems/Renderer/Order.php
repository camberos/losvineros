<?php

class Sns_Megamenu_Block_Adminhtml_Menuitems_Renderer_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
	public function render(Varien_Object $row){
		$value =  $row->getData('order');
		return $value;
	}

}