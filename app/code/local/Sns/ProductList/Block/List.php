<?php

class Sns_ProductList_Block_List extends Mage_Catalog_Block_Product_List
{
	protected $defaultTemplate = 'sns/productlist/default.phtml';
	public function __construct($attributes = array()){
		parent::__construct($attributes);
		
		$selfData = $this->getData();
		
		// handler configuration for module config
		$configuration = $this->_getConfiguration();
		if ( count($configuration) ){
			foreach ($configuration as $field => $value) {
				//if (!array_key_exists($field, $selfData)){
				$selfData[$field] = $value;
				//}
			}
		}
		
		// handler attributes for {{block ...}} in cms page
		if ( count($attributes) ){
			foreach ($attributes as $field => $value) {
				//if (!array_key_exists($field, $selfData)){
				$selfData[$field] = $value;
				//}
			}
		}
		
		// re-save data
		$this->setData($selfData);
	}
	
	public function setConfig($key = null, $value = null){
		if ( is_array($key) ){
			foreach ($key as $k => $v){
				$this->setData($k, $v);
			}
		} else if ( !is_null($key) ) {
			$this->setData($key, $value);
		}
	}
	
	protected function _getConfiguration($path = 'productlist_cfg'){
		$configuration = Mage::getStoreConfig($path);
		$conf_merged = array();
		foreach( $configuration as $group ){
			foreach($group as $field => $value){
				if (array_key_exists($field, $conf_merged)){
					// no override
				} else {
					$conf_merged[$field] = $value;
				}
			}
		}
		return $conf_merged;
	}
	
	protected function _beforeToHtml(){
		if ( !($template = $this->getTemplate()) ){
			$this->setTemplate($this->defaultTemplate);
		}
		$this->_getProductCollection()->load();
		return $this;
		$this->stripTags($data);
	}
	
	protected function _getProductCollection()
	{
		if (is_null($this->_productCollection)) {
			$collection = Mage::getSingleton('catalog/product')->getCollection();
			$collection->addAttributeToSelect('*');
			$collection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
			$visibility = array(
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
				Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
			);
			$collection->addAttributeToFilter('visibility', $visibility);
			
			// add price data
			$collection->addPriceData();
			
			if ( $this->getProductSource() == 'product'){
				// product
				if ( $ids = $this->getProductIds() ){
					$ids = preg_split('#[\s|,]+#', $ids, -1, PREG_SPLIT_NO_EMPTY);
					$ids = array_map('intval', $ids);
					$ids = array_unique($ids);
					$collection->addIdFilter($ids);
				}
			} else {
				// catalog
				$category_ids = $this->getProductCategory() ? $this->getProductCategory() : '';
				$category_ids = preg_split('#[\s|,]+#', $category_ids, -1, PREG_SPLIT_NO_EMPTY);
				$category_ids = array_map('intval', $category_ids);
				$category_ids = array_unique($category_ids);
				$this->_addCategoryFilter($collection, $category_ids);
				// var_dump($category_ids);
			}
			
			$product_sort_by = $this->getProductOrderBy() ? trim($this->product_order_by) : 'rand()';
			
			// add more attribute
			if ( $this->getProductRatingSummary() || $product_sort_by=='top_rating'
					|| $this->getProductReviewCount() || $product_sort_by=='most_reviewed'){
				//Mage::getModel('review/review')->appendSummary($collection);
				$this->_addReviewSummary($collection);
			}
			if ( $this->getProductViewedCount() || $product_sort_by=='most_viewed' ){
				$this->_addViewedCount($collection);
			}
			if ( $this->getProductOrderedCount() || $product_sort_by=='best_sales' ){
				$this->_addOrderedCount($collection);
			}
			
			switch ($product_sort_by){
				case 'name':
				case 'created_at':
				case 'price':
					$product_sort_dir = $this->getProductOrderDir() ? $this->product_order_dir : 'ASC';
					if ( !in_array( strtoupper($product_sort_dir), array('ASC', 'DESC') ) ){
						$product_sort_dir = 'ASC';
					}
					$collection->addAttributeToSort($product_sort_by, $product_sort_dir);
					break;
				case 'position':
					break;
				case 'random':
					$collection->getSelect()->order('rand()');
					break;
				case 'top_rating':
					$collection->getSelect()->order('sns_rating_summary desc');
					break;
				case 'most_reviewed':
					$collection->getSelect()->order('sns_review_count desc');
					break;
				case 'most_viewed':
					$collection->getSelect()->order('sns_viewed_count desc');
					break;
				case 'best_sales':
					$collection->getSelect()->order('sns_ordered_count desc');
					break;
			}
			
			$collection->addStoreFilter();
			$numProducts = $this->getProductLimitation()>0 ? intval($this->product_limitation) : 0;
			$collection->setPage(1, $numProducts);
			$this->_productCollection = $collection;
		}
		return $this->_productCollection;
	}
	
	private function _addCategoryFilter(& $collection, $category_ids){
		if ( empty($category_ids) ){
			return ;
		}
		$category_collection = Mage::getModel('catalog/category')->getCollection();
		$category_collection->addAttributeToSelect('*');
		$category_collection->addIsActiveFilter();
		if (count($category_ids)>0){
			$category_collection->addIdFilter($category_ids);
		}
		$category_collection->groupByAttribute('entity_id');
		$category_products = array();
		foreach ($category_collection as $category){
			$cid = $category->getId();
			if ( !array_key_exists( $cid, $category_products) ){
				$category_products[$cid] = $category->getProductCollection()->getAllIds();
			}
		}
		$product_ids = array();
		if (count($category_products)){
			foreach ($category_products as $cp) {
				$product_ids = array_merge($product_ids, $cp);
			}
		}
		$collection->addIdFilter($product_ids);
	}
	
	private function _addViewedCount(& $collection, $viewed_count_alias='sns_viewed_count'){
		// add viewed_count
		$reports_event_table		= Mage::getSingleton('core/resource')->getTableName('reports/event');
		$reports_event_types_table 	= Mage::getSingleton('core/resource')->getTableName('reports/event_type');
		$collection->getSelect()
		->joinLeft(
				array('re_table' => $reports_event_table),
				'e.entity_id = re_table.object_id',
				array(
						$viewed_count_alias => 'COUNT(re_table.event_id)'
				)
		)->joinLeft(
				array('ret_table' => $reports_event_types_table),
				"re_table.event_type_id = ret_table.event_type_id AND ret_table.event_name = 'catalog_product_view'",
				array()
		)->group('e.entity_id');
	}
	
	private function _addReviewSummary(& $collection, $review_count_alias='sns_review_count', $rating_summary_alias='sns_rating_summary' ){
		// add review_count and rating_summary
		$review_summary_table = Mage::getSingleton('core/resource')->getTableName('review/review_aggregate');
		$collection->getSelect()->joinLeft(
				array('rs_table' => $review_summary_table),
				'e.entity_id = rs_table.entity_pk_value AND rs_table.store_id='.Mage::app()->getStore()->getId(),
				array(
						$review_count_alias  => 'rs_table.reviews_count',
						$rating_summary_alias => 'rs_table.rating_summary'
				)
		);
	}

	private function _addOrderedCount(& $collection, $ordered_qty_alias='sns_ordered_count'){
		$order_table = Mage::getSingleton('core/resource')->getTableName('sales/order');
		$read = Mage::getSingleton('core/resource')->getConnection ('core_read');
		$orders_active_query = $read->select()->from(array('o_table'=>$order_table), 'o_table.entity_id')->where("o_table.state<>'" . Mage_Sales_Model_Order::STATE_CANCELED . "'");
		$res = $orders_active_query->query()->fetchAll();
		$order_ids = array();
		if ( count($res) ){
			foreach($res as $row){
				array_key_exists('entity_id', $row) && array_push($order_ids, $row['entity_id']);
			}
		}
		$order_item_table = Mage::getSingleton('core/resource')->getTableName('sales/order_item');
		$collection->getSelect()->join(
				array('oi_table' => $order_item_table),
				'e.entity_id=oi_table.product_id'.(count($order_ids) ? ' AND oi_table.order_id IN('.implode(',', $order_ids).')' : ''),
				array(
						$ordered_qty_alias => 'SUM(oi_table.qty_ordered)'
				)
		);
		$collection->getSelect()->group('e.entity_id');
		return $this;
	}
}
