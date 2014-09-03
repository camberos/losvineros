<?php

class Sns_FractionSliderBanner_Helper_Data extends Mage_Core_Helper_Abstract {
	public function __construct(){
		$this->defaults = array(
			/* General options */
			'title' 		=> 'SNS Fraction Slider Banner',
			'autoplay'		=> '1',
			'timeout'		=> '8000',
			'controls'		=> '1',
			'pager'			=> '0',
			'fullWidth'		=> '1',
			'hDimensions'	=> '500',
			'wDimensions'  	=> '1170',
			'html_slides'	=>'',
			'include_jquery'	=> '1',
			'pretext'			=> '',
			'posttext'			=> ''

			/**config_fields**/
		);
	}

	function get($attributes=array())
	{
		$data = $this->defaults;
		$general 					= Mage::getStoreConfig("fractionsliderbanner_cfg/general");
		$slideshow_effect				= Mage::getStoreConfig("fractionsliderbanner_cfg/slideshow_effect");
		$banner_selection 			= Mage::getStoreConfig("fractionsliderbanner_cfg/banner_selection");
		$advanced 					= Mage::getStoreConfig("fractionsliderbanner_cfg/advanced");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))					$data = array_merge($data, $general);
		if (is_array($slideshow_effect)) 			$data = array_merge($data, $slideshow_effect);
		if (is_array($banner_selection)) 		$data = array_merge($data, $banner_selection);
		if (is_array($advanced)) 				$data = array_merge($data, $advanced);

		return array_merge($data, $attributes);;
	}
	public function getJQquery(){
		if (Mage::getStoreConfigFlag('fractionsliderbanner_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquery')){
				Mage::register('sns.jquery', 1);
				return 'sns/fractionsliderbanner/js/jquery-1.7.2.min.js';
			}
		}
		return;
	}
	public function getJQqueryNoconflict(){
		if (Mage::getStoreConfigFlag('fractionsliderbanner_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquerynoconflict')){
				Mage::register('sns.jquerynoconflict', 1);
				return 'sns/fractionsliderbanner/js/sns.noconflict.js';
			}
		}
		return;
	}
	public function getJSFractionSlider(){
		if (null == Mage::registry('sns.fractionslider')){
			Mage::register('sns.fractionslider', 1);
			return 'sns/fractionsliderbanner/js/jquery.fractionslider.js';
		}
		return;
	}
}
?>