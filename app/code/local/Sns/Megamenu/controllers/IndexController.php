<?php

class Sns_Megamenu_IndexController extends Mage_Core_Controller_Front_Action{
    public function indexAction(){
		$this->loadLayout();
		$this->renderLayout();
    }
	public function getitemsAction(){
		if ($params = Mage::app()->getRequest()->getParams()) {
			if($params['group']){
				Mage::dispatchEvent('megamenu_menuitems_getItemsByGroupId',array('params'=>$params));
			}
		}
	}
	public function getchilditemsAction(){
		if ($params = Mage::app()->getRequest()->getParams()) {
			if($params['group']){
				Mage::dispatchEvent('megamenu_menuitems_getChildItemsByParentId',array('params'=>$params));
			}
		}
	}
}