<?php
class Sns_Megamenu_Helper_Filter extends Mage_Core_Helper_Abstract {
	public function  getFilterData($data,$typeFilter){
		$new = '';
		if($typeFilter =='text'){
			$new = $this->_getDataText($data);
		}
		return $new;
	}
	protected function _getDataText($data){
		return strip_tags(trim($data));
	}
}
