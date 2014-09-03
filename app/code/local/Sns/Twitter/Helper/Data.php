<?php
class Sns_Twitter_Helper_Data extends Mage_Core_Helper_Abstract {
	public function __construct(){
		$this->defaults = array(
			'isenabled'		=> '1',
			'title' 		=> 'SNS Twitter',
			'limit' 		=> '2',
			'avartar'		=> '0',
			'follow_link'	=> '0',
			'account_name'	=>'snstheme',
			'date'			=> '0',
			'interact_link'	=> '0',
			'pretext'			=> '',
			'posttext'			=> ''
		);
	}

	function get($attributes=array()){
		$data = $this->defaults;
		$general 					= Mage::getStoreConfig("twitter_cfg/general");
		$module_setting				= Mage::getStoreConfig("twitter_cfg/module_setting");
		$advanced 					= Mage::getStoreConfig("twitter_cfg/advanced");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))					$data = array_merge($data, $general);
		if (is_array($module_setting)) 			$data = array_merge($data, $module_setting);
		if (is_array($advanced)) 				$data = array_merge($data, $advanced);
		return array_merge($data, $attributes);;
	}
	public function getJQquery(){
		if (Mage::getStoreConfigFlag('slider_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquery')){
				Mage::register('sns.jquery', 1);
				return 'sns/twitter/js/jquery-1.7.2.min.js';
			}
		}
		return;
	}
	public function getJQqueryNoconflict(){
		if (Mage::getStoreConfigFlag('slider_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquerynoconflict')){
				Mage::register('sns.jquerynoconflict', 1);
				return 'sns/twitter/js/sns.noconflict.js';
			}
		}
		return;
	}
	public function getJSTwitterFetcher(){
		if (null == Mage::registry('sns.twitterfetcher')){
			Mage::register('sns.twitterfetcher', 1);
			return 'sns/twitter/js/twitterfetcher-min.js';
		}
		return;
	}
}
?>