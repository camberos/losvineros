<?php

class Sns_Megamenu_Model_Menuitems extends Mage_Core_Model_Abstract{
    public function _construct(){
        parent::_construct();
        $this->_init('megamenu/menuitems');
    }
}