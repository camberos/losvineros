<?php

class Sns_Megamenu_Block_Adminhtml_Widget_AddField extends Mage_Core_Block_Template{
	protected	$_p ;
	protected	$_b ;

	public function __construct(){
		$this->_p = new Varien_Object();
		$this->_b = new Varien_Object();
	}
	public function addFieldWidget($arr, $fieldset){
		$param = $this->_p;
		$button = $this->_b;
		$param->setKey(($arr['id'])?$arr['id']:'empty_id');
		$param->setVisible(($arr['visible'])?$arr['visible']:1);
		$param->setRequired(($arr['required'])?$arr['required']:1);
		$param->setType(($arr['type'])?$arr['type']:'Label');
		$param->setSortOrder(($arr['sort_order'])?$arr['sort_order']:1);
		$param->setValues(($arr['values'])?$arr['values']:array());
		$param->setLabel(($arr['label'])?$arr['label']:'Empty');

		$button->setButton(($arr['button']['text'])?$arr['button']['text']:array('open'=>'Select...'));
		$button->setType(($arr['button']['type'])?$arr['button']['type']:'');
		$param->setHelperBlock($button);
		return $this->_addField($param,$fieldset);
	}
    public function getMainFieldset($fieldset){
        if ($this->_getData('main_fieldset') instanceof Varien_Data_Form_Element_Fieldset) {
            return $this->_getData('main_fieldset');
        }
        $this->setData('main_fieldset', $fieldset);
        return $fieldset;
    }
    public function _addField($parameter,$fieldset){
        $form = $this->getForm();
        $fieldset = $this->getMainFieldset($fieldset);
        $fieldName = $parameter->getKey();
        $data = array(
			'name'      => $fieldName,
            'label'     => Mage::helper('megamenu')->__($parameter->getLabel()),
            'class'     => 'widget-option '.$fieldName,
            'note'      => Mage::helper('megamenu')->__($parameter->getDescription()),
        );
        if ($values = $this->getWidgetValues()) {
            $data['value'] = (isset($values[$fieldName]) ? $values[$fieldName] : '');
        }
        else {
            $data['value'] = $parameter->getValue();
            if ($fieldName == 'unique_id' && $data['value'] == '') {
				$data['value'] = microtime(1);
            }
        }

        if ($values  = $parameter->getValues()) {
            $data['values'] = array();
            foreach ($values as $option) {
                $data['values'][] = array(
                    'label' => Mage::helper('megamenu')->__($option['label']),
                    'value' => $option['value']
                );
            }
        }
        elseif ($sourceModel = $parameter->getSourceModel()) {
            $data['values'] = Mage::getModel($sourceModel)->toOptionArray();
        }
        $fieldRenderer = null;
        $fieldType = $parameter->getType();
        if (!$parameter->getVisible()) {
            $fieldType = 'hidden';
        }
        elseif (false !== strpos($fieldType, '/')) {
            $fieldRenderer = $this->getLayout()->createBlock($fieldType);
            $fieldType = $this->_defaultElementType;
        }
		$field = $fieldset->addField($fieldName, $fieldType, $data);
        if ($fieldRenderer) {
            $field->setRenderer($fieldRenderer);
        }
        if ($helper = $parameter->getHelperBlock()) {
			Mage::register('megamenu_adminhtml_widget_chooser',1);
            $helperBlock = $this->getLayout()->createBlock($helper->getType(), '', $helper->getData());
            if ($helperBlock instanceof Varien_Object) {
                $helperBlock->setConfig($helper->getData())
                    ->setFieldsetId($fieldset->getId())
                    ->setTranslationHelper(Mage::helper('megamenu'))
                    ->prepareElementHtml($field);
            }
		}
        return $field;
    }
    protected function _getButtonHtml($data){
        $html = '<button type="button"';
        $html.= ' class="scalable '.(isset($data['class']) ? $data['class'] : '').'"';
        $html.= isset($data['onclick']) ? ' onclick="'.$data['onclick'].'"' : '';
        $html.= isset($data['style']) ? ' style="'.$data['style'].'"' : '';
        $html.= isset($data['id']) ? ' id="'.$data['id'].'"' : '';
        $html.= '>';
        $html.= isset($data['title']) ? '<span>'.$data['title'].'</span>' : '';
        $html.= '</button>';

        return $html;
    }
}