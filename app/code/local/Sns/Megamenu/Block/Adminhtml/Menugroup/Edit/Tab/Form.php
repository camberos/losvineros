<?php

class Sns_Megamenu_Block_Adminhtml_Menugroup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{
	protected function _prepareForm(){
		if ( Mage::getSingleton('adminhtml/session')->getMenugroupData() ){
			$data = Mage::getSingleton('adminhtml/session')->getMenugroupData();
			Mage::getSingleton('adminhtml/session')->getMenugroupData(null);
		} elseif ( Mage::registry('menugroup_data') ) {
			$data = Mage::registry('menugroup_data')->getData();
		}
		$form = new Varien_Data_Form();
		$this->setForm($form);

		$form->setHtmlIdPrefix('megamenu_');

		$fieldset = $form->addFieldset('menugroup_form', array('legend'=>Mage::helper('megamenu')->__('group information')));

		$fieldset->addField('title', 'text', array(
			'label'     => Mage::helper('megamenu')->__('Title'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'title',
		));

		$fieldset->addField('status', 'select', array(
			'label'     => Mage::helper('megamenu')->__('Status'),
			'name'      => 'status',
			'values'    => array(
				array(
				  'value'     => 1,
				  'label'     => Mage::helper('megamenu')->__('Enabled'),
				),

				array(
				  'value'     => 2,
				  'label'     => Mage::helper('megamenu')->__('Disabled'),
				),
			),
		));
		$wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('add_variables' => false, 'add_widgets' => false,'files_browser_window_url'=>$this->getBaseUrl().'admin/cms_wysiwyg_images/index/'));

		if ( $data ){
			$form->setValues($data);
		}
		return parent::_prepareForm();
	}
}