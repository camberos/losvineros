<?php

class Sns_Megamenu_Model_Mysql4_Menugroup extends Mage_Core_Model_Mysql4_Abstract{
    public function _construct(){
        $this->_init('megamenu/menugroup', 'id');
    }
}