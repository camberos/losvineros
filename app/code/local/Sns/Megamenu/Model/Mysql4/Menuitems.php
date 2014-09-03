<?php

class Sns_Megamenu_Model_Mysql4_Menuitems extends Mage_Core_Model_Mysql4_Abstract{
    public function _construct(){
        $this->_init('megamenu/menuitems', 'id');
    }
}