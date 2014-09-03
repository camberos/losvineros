<?php

class Sns_Megamenu_Block_List extends Mage_Core_Block_Template{
	private static $parent_ignored = array();
	protected $_config = null;
	protected $_storeId = null;
	protected $_productCollection = null;
	protected $_allLeafId = null;
	protected $_allItemsFirstColumnId = null;
	protected $_allActivedItems = null;
	protected $_allActivedId = null;
	protected $_typeCurrentUrl = null;
	protected $_itemCurrentUrl = null;
    public function getMegamenu(){
        if (!$this->hasData('megamenu')) {
            $this->setData('megamenu', Mage::registry('megamenu'));
        }
        return $this->getData('megamenu');
    }
	public function __construct($attributes = array()){
		parent::__construct();
		$this->_config = Mage::helper('megamenu/default')->get($attributes);
		if(!$this->_config['isenabled']) return;
		if($this->filterRouter()){
			if($this->_typeCurrentUrl == Sns_Megamenu_Model_System_Config_Source_Type::CMSPAGE ){
				$item_id = $this->_itemCurrentUrl;
			}
			if($this->_typeCurrentUrl == Sns_Megamenu_Model_System_Config_Source_Type::PRODUCT ){
				$item_id = 'product/'.$this->_itemCurrentUrl->getId();
			}
			if($this->_typeCurrentUrl == Sns_Megamenu_Model_System_Config_Source_Type::CATEGORY ){
				$item_id = 'category/'.$this->_itemCurrentUrl->getId();
			}
			$this->_allActivedItems = Mage::helper('megamenu')->getAllActivedItems($this->_typeCurrentUrl, $item_id, $this->_config['group_id']);
			$this->_allActivedId = $this->_allActivedItems->getALLIds();
		};
		$itemsLeaf = Mage::helper('megamenu')->getAllLeafByGroupId($this->_config['group_id']);
		$this->_allLeafId = ($itemsLeaf)?$itemsLeaf->getALLIds():'';
		if(!$this->_allItemsFirstColumnId){
			$itemsFirstColumn = Mage::helper('megamenu')->getAllItemsFirstByGroupId($this->_config['group_id']);
			$this->_allItemsFirstColumnId = ($itemsFirstColumn)?$itemsFirstColumn->getALLIds():'';
		}
	}

	public function getConfig($name=null, $value=null){
		if (is_null($this->_config)){
			$this->_config = Mage::helper('megamenu/default')->get(null);
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
		$template_file = 'sns/megamenu/megamenu.phtml';
		$this->setTemplate($template_file);
		return parent::_toHtml();
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

	public function getItems(){
		$helper = Mage::helper('megamenu');
		$group_item = Mage::getModel('megamenu/menugroup')->load($this->_config['group_id']);
		if($group_item->getStatus() == Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED){
			$collection_items = $helper ->getItemsByLv($this->_config['start_level'],$this->_config['group_id']);
			return $collection_items;
		}
		else{
			return array();
		}
	}
	public function getItemHtml($item, $isMegacontent = false){
		$prefix = Sns_Megamenu_Model_System_Config_Source_Html::PREFIX;
		$divClassName = 'megamenu-col megamenu-'.$item->getColsNb().'col';
		$divClassName .= ($item->custom_class!="")?' '.$item->custom_class:'';
		$divClassName .=($isMegacontent)?' mega-content-wrap':'';

		$divClassName .= ($item->getAlign() == Sns_Megamenu_Model_System_Config_Source_Align::RIGHT)?' align-right':'';
		$divClassName .= (!$this->isLeaf($item) || ($this->hasConntentType($item)))?' have-spetitle':'';
		$divClassName .= ' level'.($item->getDepth()-1);

		$activedClassName = ($this->isActived($item))?'active':'';

		$link = ($this->hasLinkType($item))?$this->getLinkOfType($item):'#';
		$title = ($item->getShowTitle()==Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED)?'<span>'.$item->getTitle().'</span>':'&nbsp';
		if($this->isDrop($item) OR $this->hasLinkType($item)){
			$headTitle = '<a class="'.$activedClassName.'" href="'.$link.'" '.Mage::helper('megamenu/utils')->getTargetAttr($item->getTarget()).' >'.$title.'</a>';
		}else{
			$headTitle = $title;
		}

		$html = '';
		$html .= '<div class="'.$divClassName.' '.$activedClassName.'">';
		if($item->getDepth() != $this->_config['start_level']){
			if($item->getShowTitle()==Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED){
				$addClass['title'] = 'mega-title';
				$html.= '<div class="'.implode(' ',$addClass).'">'.$headTitle.'</div>';
				$addClass=array();
			}
		}
		if(!$this->isLeaf($item)){
			if($item->getDepth()+1 <= $this->_config['end_level']){
				$childItems = Mage::helper('megamenu')->getChildsDirectlyByItem($item);
				if(!count($childItems->getItems())){
					if(!$this->hasLinkType($item)){
						$html.= '<div class="mega-content">'.$this->getContentType($item).'</div>';
					}
					$html.= '</div>';
					return $html;
				}
				$cols_total = $item->getColsNb();
				$cols_sub = intval($cols_total);
				foreach($childItems as $childItem){
					$cols_sub = $cols_sub - intval($childItem->getColsNb());
					$isFirst = '';
					if($cols_sub < 0){
						$isFirst = 'isFirstColumn';
						$cols_sub = $cols_total - intval($childItem->getColsNb());
					}
					$html .= $this->getItemHtml($childItem);
				}
				$html .= '</div>';
			}else{
				if(!$this->hasLinkType($item)){
					$html.= '<div class="'.$prefix.'content">'.$this->getContentType($item).'</div>';
				}
				$html .= '</div>';
			}
		}else{
			if(!$this->hasLinkType($item)){
				$html.= '<div class="'.$prefix.'content">'.$this->getContentType($item).'</div>';
			}
			$html.= '</div>';
		}
		return $html;
	}

	public function isLeaf($item){
		return (in_array($item->getId(),$this->_allLeafId))?true:false;
	}
	public function isFirstCol($item){
		return (in_array($item->getId(),$this->_allItemsFirstColumnId))?true:false;
	}
	public function isDrop($item){
		return ($item->getShowAsGroup()==Sns_Megamenu_Model_System_Config_Source_Status::STATUS_DISABLED)?true:false;
	}
	public function isAlignRight($item){
		return ($item->getAlign()==Sns_Megamenu_Model_System_Config_Source_Align::RIGHT)?true:false;
	}

	public function isActived($item){ //Zend_Debug::dump($this->_allActivedId); die();
		return (in_array($item->getId(),$this->_allActivedId))?true:false;
	}
	public function hasActivedType($item){
		$activedType = array(
			Sns_Megamenu_Model_System_Config_Source_Type::PRODUCT ,
			Sns_Megamenu_Model_System_Config_Source_Type::CATEGORY ,
			Sns_Megamenu_Model_System_Config_Source_Type::CMSPAGE ,
		);
		return (in_array($item->getType(),$activedType))?true:false;
	}
	public function hasLinkType($item){
		$linkType = array(
			Sns_Megamenu_Model_System_Config_Source_Type::EXTERNALLINK ,
			Sns_Megamenu_Model_System_Config_Source_Type::PRODUCT ,
			Sns_Megamenu_Model_System_Config_Source_Type::CATEGORY ,
			Sns_Megamenu_Model_System_Config_Source_Type::CMSPAGE ,
		);
		return (in_array($item->getType(),$linkType))?true:false;
	}
	public function hasConntentType($item){
		$contentType = array(
			Sns_Megamenu_Model_System_Config_Source_Type::STATICBLOCK,
			Sns_Megamenu_Model_System_Config_Source_Type::CONTENT,
		);
		return (in_array($item->getType(),$contentType))?true:false;
	}
	public function getLinkOfType($item){
		if($item->getType() == Sns_Megamenu_Model_System_Config_Source_Type::EXTERNALLINK){
			return $this->filterUrl($item);
		}
		elseif($item->getType() == Sns_Megamenu_Model_System_Config_Source_Type::PRODUCT){
			return $this->getProductLink($item);
		}
		elseif($item->getType() == Sns_Megamenu_Model_System_Config_Source_Type::CATEGORY){
			return $this->getCategoryLink($item);
		}
		elseif($item->getType() == Sns_Megamenu_Model_System_Config_Source_Type::CMSPAGE){
			return $this->getCMSPageLink($item);
		}
		else
			return '#';
	}
	public function getContentType($item){
		if($item->getType() == Sns_Megamenu_Model_System_Config_Source_Type::STATICBLOCK){
			return $this->getBlockPageHtml($item);
		}
		elseif($item->getType() == Sns_Megamenu_Model_System_Config_Source_Type::CONTENT){
			return $this->getContentHtml($item);
		}
		else{
			return false;
		}
	}

	public function filterUrl($item){
		$link = Mage::helper('catalog/product_url')->format(trim($item->getDataType()));
		$link = strtolower($link);
		$haveHttp =  strpos($link, "http://");
		if(!$haveHttp && ($haveHttp!==0)){
			return "http://" . $link;
		}else {
			return $link;
		}
	}
	public function getProductLink($item){
		$filter = explode('/',$item->getDataType());
		$productId = $filter[1];
		$product = Mage::getModel('catalog/product')->load($productId);
		return $product->getProductUrl();
	}
	public function getCategoryLink($item){
		$filter = explode('/',$item->getDataType());
		$categoryId = $filter[1];
		$category = Mage::getModel('catalog/category')->load($categoryId);
		return $category->getUrl();
	}
	public function getCMSPageLink($item){
		$cmspageId = $item->getDataType();
		return Mage::Helper('cms/page')->getPageUrl($cmspageId);
	}
	public function getBlockPageHtml($item){
		$blockId = $item->getDataType();
		$block = Mage::getSingleton('core/layout')->createBlock('cms/block')->setBlockId($blockId);
		return $block->toHtml();
	}
	public function getContentHtml($item){
		return $this->filterContent($item->getContent());
	}
	public function filterContent($content){
		$helper = Mage::helper('cms');
        $processor = $helper->getPageTemplateProcessor();
        $html = $processor->filter($content);
		return $html;
	}
	public function filterRouter(){
		$current_page = '';
		if(Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms'){
			$this->_typeCurrentUrl = Sns_Megamenu_Model_System_Config_Source_Type::CMSPAGE ;
			$this->_itemCurrentUrl = Mage::getSingleton('cms/page')->getId() ;
			return true;
		}
		if(empty($current_page)){
			$current_page = Mage::app()->getFrontController()->getRequest()->getRouteName();
		}
		if($current_page == 'catalog'){
			if($this->getRequest()->getControllerName()=='product') {
				$this->_typeCurrentUrl = Sns_Megamenu_Model_System_Config_Source_Type::PRODUCT ;
				$this->_itemCurrentUrl = Mage::registry('current_product') ;
				return true;
			}
			if($this->getRequest()->getControllerName()=='category'){
				$this->_typeCurrentUrl = Sns_Megamenu_Model_System_Config_Source_Type::CATEGORY ;
				$this->_itemCurrentUrl = Mage::registry('current_category') ;
				return true;
			}
		}
		return false;
	}
}


