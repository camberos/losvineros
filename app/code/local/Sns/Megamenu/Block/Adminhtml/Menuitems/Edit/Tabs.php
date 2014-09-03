<?php

class Sns_Megamenu_Block_Adminhtml_Menuitems_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{

  public function __construct(){
      parent::__construct();
      $this->setId('menuitems_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('megamenu')->__('Menu Items Information'));
  }

  protected function _beforeToHtml(){
      $this->addTab('form_section', array(
          'label'     => Mage::helper('megamenu')->__('Menu Items Information'),
          'title'     => Mage::helper('megamenu')->__('Menu Items Information'),
          'content'   => $this->getLayout()->createBlock('megamenu/adminhtml_menuitems_edit_tab_form')->toHtml(),
      ));
      return parent::_beforeToHtml();
  }
}