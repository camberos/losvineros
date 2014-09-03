<?php
class Sns_Ajaxcart_Helper_Sendhtml extends Varien_Object{
	public static $ACProductName=null;
	public $_ISWISHLIST=null;
	public function sendResponse($co_sb, $link){
		$options="0";
		$wishlist="";
		$wishtitle="";
		$addtype="0";
		$addtype=(Mage::helper('ajaxcart/Sendhtml')->getAddwhat()!='0')?Mage::helper('ajaxcart/Sendhtml')->getAddwhat():"0";
		if ($product = Mage::registry('current_product')){
			$options=$product->getHasOptions();
			if($product->getTypeId()=='grouped'){
				$options="1";
			}
	    }
		$pdname=($this->ACProductName)?$this->ACProductName:"";
		$this->ACProductName='';
		header('content-type: text/javascript');
		echo '{"addtype":"'.$addtype.'", "links":"'.$link.'","options":'.$options.', "co_sb":' . json_encode($co_sb) . ', "pdname":'.json_encode($pdname).'}';
		die();
	}
}