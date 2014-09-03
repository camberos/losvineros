<?php

class Sns_Ajaxcart_Model_Mysql4_Ajaxcart extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct(){
        $this->_init('ajaxcart/ajaxcart', 'ajaxcart_id');
    }
}