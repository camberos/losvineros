<?php

class Sns_Slider_Helper_Data extends Mage_Core_Helper_Abstract {
	public function getJQquery(){
		if (Mage::getStoreConfigFlag('slider_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquery')){
				Mage::register('sns.jquery', 1);
				return 'sns/slider/js/jquery-1.7.2.min.js';
			}
		}
		return;
	}
	public function getJQqueryNoconflict(){
		if (Mage::getStoreConfigFlag('slider_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquerynoconflict')){
				Mage::register('sns.jquerynoconflict', 1);
				return 'sns/slider/js/sns.noconflict.js';
			}
		}
		return;
	}
	public function getJSCaroufredsel(){
		if (null == Mage::registry('sns.caroufredsel')){
			Mage::register('sns.caroufredsel', 1);
			return 'sns/slider/js/jquery.carouFredSel-6.2.1-packed.js';
		}
		return;
	}
	public function getJSMousewheel(){
		if (null == Mage::registry('sns.mousewheel')){
			Mage::register('sns.mousewheel', 1);
			return 'sns/slider/js/jquery.mousewheel.min.js';
		}
		return;
	}
	public function getJSTouchswipe(){
		if (null == Mage::registry('sns.touchswipe')){
			Mage::register('sns.touchswipe', 1);
			return 'sns/slider/js/jquery.touchSwipe.min.js';
		}
		return;
	}
	public function getJSTransit(){
		if (null == Mage::registry('sns.transit')){
			Mage::register('sns.transit', 1);
			return 'sns/slider/js/jquery.transit.min.js';
		}
		return;
	}
}
?>