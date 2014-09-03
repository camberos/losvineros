<?php

class Sns_FractionSliderBanner_Block_List extends Mage_Core_Block_Template
{
	protected $_config = null;
	protected $_storeId = null;
	
	public function __construct($attributes = array()){
		parent::__construct();
		$this->_config = Mage::helper('fractionsliderbanner/data')->get($attributes);
	}

	public function getConfig($name=null, $value=null){
		if (is_null($this->_config)){
			$this->_config = Mage::helper('fractionsliderbanner/data')->get(null);
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
		$template_file = 'sns/fractionsliderbanner/default.phtml';
		$this->setTemplate($template_file);
		return parent::_toHtml();
	}

	protected function _getSlide(){
		//$scope 		= (Mage::app()->getStore()->getData('group_id') == 1 || $this->getStoreId()==Mage::app()->getWebsite()->getDefaultGroup()->getDefaultStoreId())?'default':'stores';
		//$scopeId 	= (Mage::app()->getStore()->getData('group_id') == 1 || $this->getStoreId()==Mage::app()->getWebsite()->getDefaultGroup()->getDefaultStoreId())?0:$this->getStoreId();
		$scope  = 'stores';
		$scopeId = $this->getStoreId();
		$path 		= 'fractionsliderbanner_cfg/banner_selection/html_slides';
        $table 	= (string) Mage::getConfig()->getTablePrefix().'core_config_data';

        $sql = "SELECT value FROM `$table` WHERE scope='$scope' AND scope_id=$scopeId AND path='$path'";
        //Zend_Debug::dump($sql); die();
		$data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
		if(empty($data)){
			$scope  = 'default';
			$scopeId = 0;
			$sql = "SELECT value FROM `$table` WHERE scope='$scope' AND scope_id=$scopeId AND path='$path'";
			$data = Mage::getSingleton('core/resource') ->getConnection('core_read')->fetchAll($sql);
		}
        //Zend_Debug::dump($data); die();

		//$items = Mage::getStoreConfig('fractionsliderbanner_cfg/banner_selection/html_slides');Zend_Debug::dump($items); die();
		//$items = $this->getConfig('html_slides'); Zend_Debug::dump($items); die();

		$items = $data[0]["value"]; //Zend_Debug::dump($items); die();
		if($items){
			$array_items=unserialize($items);
			$id = 1;
			$collect_items = array();
			foreach($array_items as $key=>$item){
				$item['id'] = $id;
				$item['img'] = $item['slide'];
				$collect_items[] = $item;
				$id++;
			}
			return $collect_items;
		}
		return array();
	}

	public function getStoreId(){
		if (is_null($this->_storeId)){
			$this->_storeId = Mage::app()->getStore()->getId();
		}
		return $this->_storeId;
	}
	public function setStoreId($storeId=null){
		$this->_storeId = $storeId;
	}

	public function getConfigObject(){
		return $this->_config;
	}
}
