<?php

class Sns_Ajaxcart_Model_Mysql4_Ajaxcart_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('ajaxcart/ajaxcart');
    }
}