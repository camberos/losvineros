<?php

class Sns_Twitter_Block_List extends Mage_Catalog_Block_Product_Abstract{
	protected $_config = null;
	protected $defaultTemplate = 'sns/twitter/default.phtml';

	public function __construct($attributes = array()){
		parent::__construct();
		$this->_config = Mage::helper('twitter/data')->get($attributes);
	}

	public function getConfig($name=null, $value=null){
		if (is_null($this->_config)){
			$this->_config = Mage::helper('twitter/data')->get(null);
		}
		if (!is_null($name) && !empty($name)){
			$valueRet = isset($this->_config[$name]) ? $this->_config[$name] : $value;
			return $valueRet;
		}
		return $this->_config;
	}

	public function setConfig($name, $value=null){
		if (is_null($this->_config)) $this->getConfig();
		if (is_array($name)){
			$this->_config = array_merge($this->_config, $name);
			return;
		}
		if (!empty($name)){
			$this->_config[$name] = $value;
		}
		return true;
	}

	protected function _toHtml(){
		if(!$this->_config['isenabled']) return;
		if ( !($template = $this->getTemplate()) ){
			$this->setTemplate($this->defaultTemplate);
		}
		return parent::_toHtml();
	}

	public function getConfigObject(){
		return (object)$this->getConfig();
	}

}
