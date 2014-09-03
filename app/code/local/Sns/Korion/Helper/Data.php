<?php
class Sns_Korion_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function __construct(){
		$this->defaults = array(
			/* general options */
			'showCpanel'				=> '1',
			'themeColor'				=> 'cyan',
			'fontFamily'				=> 'arial',
			'fontSize'					=> '12px',
			'bodyBgColor'				=> '#FFFFFF',
			'menuStyles'				=> '1',
			'resMenu'					=> 'sidebar',
			'googleFont'				=> '',
			'googleFontTargets'			=> ''
		);
	}

	function get($attributes=array())
	{
		$data = $this->defaults;
		$general 					= Mage::getStoreConfig("korion_cfg/general");
		$productlisting				= Mage::getStoreConfig("korion_cfg/productlisting");
		if (!is_array($attributes)) {
			$attributes = array($attributes);
		}
		if (is_array($general))					$data = array_merge($data, $general);
		if (is_array($productlisting)) 			$data = array_merge($data, $productlisting);
		return array_merge($data, $attributes);
	}
}