<?php
class Sns_Megamenu_Helper_Default extends Mage_Core_Helper_Abstract {
	public function __construct(){
		$this->defaults = array(
			'isenabled'		=> '1',
			'title' 		=> 'MegaMenu',
			'theme' 			=> '1',
			'group_id'			=> '1',
			'effect'				=> '1',
			'start_level'			=> '1',
			'end_level'				=> '5'
		);
	}

	function get($attributes=array()){
		$data 						= $this->defaults;
		$general 					= Mage::getStoreConfig("megamenu_cfg/general");
		$module_setting				= Mage::getStoreConfig("megamenu_cfg/module_setting");
		$advanced 					= Mage::getStoreConfig("megamenu_cfg/advanced");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))					$data = array_merge($data, $general);
		if (is_array($module_setting)) 			$data = array_merge($data, $module_setting);
		if (is_array($advanced)) 				$data = array_merge($data, $advanced);
		return array_merge($data, $attributes);;
	}
}
?>