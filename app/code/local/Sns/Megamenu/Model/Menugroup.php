<?php

class Sns_Megamenu_Model_Menugroup extends Mage_Core_Model_Abstract{
    public function _construct(){
        parent::_construct();
        $this->_init('megamenu/menugroup');
    }
}