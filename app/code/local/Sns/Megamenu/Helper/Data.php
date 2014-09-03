<?php

class Sns_Megamenu_Helper_Data extends Mage_Core_Helper_Abstract{
	public function getJQquery(){
		if (null == Mage::registry('sns.jquery')){
			Mage::register('sns.jquery', 1);
			return 'sns/megamenu/js/jquery.min.js';
		}
		return;
	}
	public function getJQqueryNoconflict(){
		if (null == Mage::registry('sns.jquerynoconflict')){
			Mage::register('sns.jquerynoconflict', 1);
			return 'sns/megamenu/js/sns.noconflict.js';
		}
		return;
	}
	public function deleteNode($id, $groupId, $nametable, $myLeft, $myRight=''){
		$query = "																											
			DELETE FROM `{$nametable}` WHERE (lft BETWEEN {$myLeft} AND {$myRight}) AND group_id='{$groupId}';					
		";
		try{
			$write= Mage::getSingleton('core/resource') ->getConnection('core_write');
			$write->query($query);
			return true;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function insertNode($position, $groupId, $nametable, $myLeft, $myRight, $item, $wDepth){
		$nametable =  Mage::getSingleton('core/resource')->getTableName('megamenu/menuitems');
		$query=array();
		$query = array_merge($query,$this->deleteNodeFake($position, $groupId, $nametable, $myLeft, $myRight));
		$itemLR['rgt'] =   $item->getData('rgt');
		$itemLR['lft'] =   $item->getData('lft');
		$query = array_merge($query,$this->updateRSideOfInsertedNode($position, $groupId, $nametable, $myLeft, $myRight, $itemLR));
		$query = array_merge($query,$this->updateRSideOfDeletedNode($position, $groupId, $nametable, $myLeft, $myRight,false, $itemLR));
		$query = array_merge($query,$this->updateLRInsertedNode($position, $groupId, $nametable, $myLeft, $myRight, $itemLR, $wDepth));
		try{
			foreach($query as $q){
				$write= Mage::getSingleton('core/resource') ->getConnection('core_write');
				$write->query($q);
			}
			return true;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage().__LINE__);
			return;
		}
	}
	public function deleteNodeFake($position, $groupId, $nametable, $myLeft, $myRight=''){
		if($position == Sns_Megamenu_Model_System_Config_Source_Position::AFTER){
			$query[] = "UPDATE `{$nametable}` SET lft = {$myLeft} - lft , rgt = {$myLeft} - rgt WHERE lft >= {$myLeft} AND rgt <= {$myRight} AND group_id='{$groupId}';";
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::BEFORE){
			$query[] = "UPDATE `{$nametable}` SET lft = lft - {$myRight}, rgt = rgt - {$myRight} WHERE lft >= {$myLeft} AND rgt <= {$myRight} AND group_id='{$groupId}';";
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::FIRST){
			$query[] = "UPDATE `{$nametable}` SET lft = {$myLeft} - lft , rgt = {$myLeft} - rgt WHERE lft >= {$myLeft} AND rgt <= {$myRight} AND group_id='{$groupId}';";
		}

		return $query;
		try{
			$write= Mage::getSingleton('core/resource') ->getConnection('core_write');
			$write->query($query[0]);
			return true;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage().__LINE__);
			return;
		}
	}
	public function updateLRInsertedNode($position, $groupId, $nametable, $myLeft, $myRight='',&$itemLR, $wDepth){
		if($position == Sns_Megamenu_Model_System_Config_Source_Position::AFTER){
			$query[] = "UPDATE `{$nametable}` SET lft = {$itemLR['rgt']} - lft + 1 , rgt = {$itemLR['rgt']} - rgt + 1, depth = depth + {$wDepth} WHERE rgt <= 0 AND group_id='{$groupId}';";
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::BEFORE){
			$query[] = "UPDATE `{$nametable}` SET lft = {$itemLR['lft']} + lft - 1 , rgt =  {$itemLR['lft']} + rgt - 1, depth = depth + {$wDepth} WHERE rgt <= 0 AND group_id='{$groupId}';";
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::FIRST){
			$query[] = "UPDATE `{$nametable}` SET lft = {$itemLR['lft']} - lft +1 , rgt = {$itemLR['lft']} - rgt +1, depth = depth + {$wDepth} WHERE rgt <= 0 AND group_id='{$groupId}';";
		}
		return $query;
		try{
			$write= Mage::getSingleton('core/resource') ->getConnection('core_write');
			$write->query($query[0]);
			return true;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage().__LINE__);
			return;
		}
	}
	public function updateRSideOfInsertedNode($position, $groupId, $nametable, $myLeft, $myRight='',&$itemLR){
		$myWidth = $myRight - $myLeft +1;
		if($position == Sns_Megamenu_Model_System_Config_Source_Position::AFTER){
			$query[] = "UPDATE `{$nametable}` SET lft = lft + {$myWidth} WHERE lft > {$itemLR['rgt']} AND group_id='{$groupId}';";
			$query[] = "UPDATE `{$nametable}` SET rgt = rgt + {$myWidth} WHERE rgt > {$itemLR['rgt']} AND group_id='{$groupId}';";
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::BEFORE){
			$query[] = "UPDATE `{$nametable}` SET lft = lft + {$myWidth} WHERE lft >= {$itemLR['lft']} AND group_id='{$groupId}';";
			$query[] = "UPDATE `{$nametable}` SET rgt = rgt + {$myWidth} WHERE rgt > {$itemLR['lft']} AND group_id='{$groupId}';";
			$itemLR['lft'] = $itemLR['lft'] + $myWidth;
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::FIRST){
			$query[] = "UPDATE `{$nametable}` SET lft = lft + {$myWidth} WHERE lft > {$itemLR['lft']} AND group_id='{$groupId}';";
			$query[] = "UPDATE `{$nametable}` SET rgt = rgt + {$myWidth} WHERE rgt > {$itemLR['lft']} AND group_id='{$groupId}';";
		}
		return $query;
		try{
			foreach($query as $q){
				$write= Mage::getSingleton('core/resource') ->getConnection('core_write');
				$write->query($query);
			}
			return true;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage().__LINE__);
			return false;
		}
	}
	public function updateRSideOfDeletedNode($position, $groupId, $nametable, $myLeft, $myRight='',$execute=true, &$itemLR){
		$myWidth = $myRight - $myLeft +1;
		$query[] = "UPDATE `{$nametable}` SET rgt = rgt - {$myWidth} WHERE rgt > {$myRight} AND group_id='{$groupId}';";
		$query[] = "UPDATE `{$nametable}` SET lft = lft - {$myWidth} WHERE lft > {$myRight} AND group_id='{$groupId}';";
		if($position == Sns_Megamenu_Model_System_Config_Source_Position::AFTER){
			if($itemLR['rgt'] > $myRight){
				$itemLR['rgt'] = $itemLR['rgt'] - $myWidth;
			}
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::BEFORE){
			if($itemLR['lft'] > $myRight){
				$itemLR['lft'] = $itemLR['lft'] - $myWidth;
			}
		}
		elseif($position == Sns_Megamenu_Model_System_Config_Source_Position::FIRST){
			if($itemLR['lft'] > $myRight){
				$itemLR['rgt'] = $itemLR['lft'] - $myWidth;
			}
		}
		if(!$execute){
			return $query;
		}
		try{
			foreach($query as $q){
				$write= Mage::getSingleton('core/resource') ->getConnection('core_write');			
				$write->query($q);
			}
			return true;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage().__LINE__);
			return false;
		}
	}
	public function updateRSide($NodeId, $groupId, $nametable, $myLeft, $myRight='' ){
		$query = "																										
			UPDATE `{$nametable}` SET rgt = rgt + 2 WHERE rgt >= {$myLeft} AND group_id='{$groupId}' AND id!='{$NodeId}' ;											
			UPDATE `{$nametable}` SET lft = lft + 2 WHERE lft >= {$myLeft} AND group_id='{$groupId}' AND id!='{$NodeId}' ;								
		";					
		try{
			$write= Mage::getSingleton('core/resource') ->getConnection('core_write');	
			$write->query($query);
			return true;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return false;
		}
	}
	public function getItemsCanMoveup($id, $groupId, $nametable, $myLeft, $myRight='' ){

	}
	public function getItemsCanMovedown($id, $groupId, $nametable, $myLeft, $myRight='' ){

	}
	public function getPosNodeById($id, $groupId, $nametable, $myLeft, $myRight='' ){

	}
	public function getPosItemsChildByParentId($parentId, $groupId, $nametable, $myLeft, $myRight='' ){

	}
	public function getAvailableNodes($id, $groupId, $nametable, $myLeft, $myRight='' ){

	}
	public function getNotAvailableNodes($id, $groupId, $nametable, $myLeft, $myRight='' ){
	}
	public function getParentIdNode($id, $groupId, $nametable, $myLeft, $myRight='' ){
		$query = "																										
			SELECT * FROM {$nametable}														
			WHERE (lft < '{$myLeft}' and rgt > '{$myRight}') AND group_id ='{$groupId}'			
			ORDER BY lft DESC															
			LIMIT 1																							
		";		

		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');
			$data = $read->fetchAll($query);
			$item = new Varien_Object();
			$item->setData($data[0]);
			return $item;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function getSinglePath($id, $groupId, $nametable, $myLeft, $myRight='', $mode=true ){
		if($mode){
			$query = "
			SELECT * FROM {$nametable}
			WHERE (lft >= '{$myLeft}' and rgt <= '{$myRight}') AND group_id ='{$groupId}'
			ORDER BY lft ASC
			";
		}
		else{
			$query = "
			SELECT * FROM {$nametable}
			WHERE ('{$myLeft}' >= lft and '{$myRight}' <= rgt) AND group_id ='{$groupId}'
			ORDER BY lft ASC
			";
		}
		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');
			$items = $read->fetchAll($query);
			$collection = new Varien_Data_Collection();
			foreach($items as $item){
				$collection->addItem(new Varien_Object($item));
			}
			return $collection;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function getAllActivedItems($type, $itemId, $groupId){
		$nametable = Mage::getSingleton('core/resource')->getTableName('megamenu/menuitems');
		$status_child =	Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED;		
		$query = "
		SELECT * FROM {$nametable}
		WHERE  type ='{$type}' AND data_type ='{$itemId}' AND group_id ='{$groupId}' AND status ='{$status_child}'
		";
		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');
			$items = $read->fetchAll($query);
			$collection = new Varien_Data_Collection();
			foreach($items as $item){
				foreach($this->getSinglePath($item['id'], $groupId, $nametable, $item['lft'], $item['rgt'], FALSE) as $item_collection){
					$collection->addItem($item_collection);
				}
			}
			return $collection;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function getNodesByGroupId($groupId, $addPrefix){
			$collection = Mage::getModel("megamenu/menuitems")->getCollection();
			$prefix = ($addPrefix)?Sns_Megamenu_Model_System_Config_Source_Prefix::PREFIX:"";
			$collection ->getSelect()
					->join(array('parent' => $collection->getTable('menuitems') ),'',array())
					->columns('CONCAT( REPEAT( "'.$prefix.'", (COUNT(parent.depth) - 1) ) , main_table.title) AS name')
					->where('main_table.lft BETWEEN parent.lft AND parent.rgt')
					->where('main_table.group_id ="'.$groupId.'"')
					->where('parent.group_id ="'.$groupId.'"')
					->group('main_table.id')
					->order('main_table.lft');
			return  $collection->getItems();
	}
	public function getChildsDirectlyByItem($parent,$mode = 1){
		$nametable = Mage::getSingleton('core/resource')->getTableName('megamenu/menuitems');
		$myLeft = $parent->getLft();
		$myRight = $parent->getRgt();
		$depth_child_directly = $parent->getDepth()+1;
		$status_child =	Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED;
		$filter_status = "AND status ='{$status_child}'";
		if($mode ==2){
			$filter_status = '';
		}
		$groupId = $parent->getGroupId();
		$query = "																					
			SELECT * FROM {$nametable}																
			WHERE (depth = '{$depth_child_directly}') AND (lft >= '{$myLeft}' and rgt <= '{$myRight}') AND group_id ='{$groupId}' {$filter_status}				
			ORDER BY lft ASC																		
		";

		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');
			$items = $read->fetchAll($query);
			$collection = new Varien_Data_Collection();
			foreach($items as $item){
				$collection->addItem(new Varien_Object($item));
			}
			return $collection;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function getRootByGroupId($groupId){
		$lvRoot = 0;
		$nametable = Mage::getSingleton('core/resource')->getTableName('megamenu/menuitems');
		$query = "																										
			SELECT * FROM {$nametable}														
			WHERE depth = '{$lvRoot}' AND group_id ='{$groupId}'			
		";
		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');
			$data = $read->fetchAll($query);
			$item = new Varien_Object();
			$item->setData($data[0]);
			return $item;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function getAllLeafByGroupId($groupId){
		$nametable = Mage::getSingleton('core/resource')->getTableName('megamenu/menuitems');
		$status_child =	Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED; 
		$query = "
			SELECT * FROM {$nametable}																
			WHERE  (rgt = lft+1) AND group_id ='{$groupId}' AND status ='{$status_child}'																						
		";
		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');
			$items = $read->fetchAll($query);
			$collection = new Varien_Data_Collection();
			foreach($items as $item){
				$collection->addItem(new Varien_Object($item));
			}
			return $collection;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function getAllItemsFirstByGroupId($groupId){
		$nametable = Mage::getSingleton('core/resource')->getTableName('megamenu/menuitems');
		$status_child =	Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED; 
		$query = "																								
			SELECT child.* FROM {$nametable} AS child JOIN {$nametable}	AS parent 																											
			WHERE  (child.lft = parent.lft+1) AND child.group_id ='{$groupId}' AND parent.group_id='{$groupId}' AND child.status ='{$status_child}'
			GROUP BY child.id																									
		";
		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');
			$items = $read->fetchAll($query);
			$collection = new Varien_Data_Collection();
			foreach($items as $item){
				$collection->addItem(new Varien_Object($item));
			}
			return $collection;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}
	public function getItemsByLv($lv, $groupId){
		$nametable = Mage::getSingleton('core/resource')->getTableName('megamenu/menuitems');
		$status_child =	Sns_Megamenu_Model_System_Config_Source_Status::STATUS_ENABLED; 
		$query = "	
			SELECT * FROM {$nametable}																
			WHERE  depth ='{$lv}' AND group_id ='{$groupId}' AND status ='{$status_child}' ORDER BY lft ASC																						
		";
		try{
			$read= Mage::getSingleton('core/resource') ->getConnection('core_read');	
			$items = $read->fetchAll($query);
			$collection = new Varien_Data_Collection();
			foreach($items as $item){
				$collection->addItem(new Varien_Object($item));
			}
			return $collection;
		}
		catch(Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			return;
		}
	}

}