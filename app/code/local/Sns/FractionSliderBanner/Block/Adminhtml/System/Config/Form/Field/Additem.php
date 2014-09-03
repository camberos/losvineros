<?php
class Sns_FractionSliderBanner_Block_Adminhtml_System_Config_Form_Field_Additem extends Mage_Adminhtml_Block_System_Config_Form_Field_Regexceptions
{
    public function __construct()
    {
        $this->addColumn('slide', array(
            'label' => Mage::helper('adminhtml')->__('Content of slide'),
            'style' => 'width:450px; height:300px',
            'type'  => 'textarea'
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add HTML Slide');
        Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract::__construct();
    }
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $html = $this->_toHtml();
        $this->_arrayRowsCache = null; // doh, the object is used as singleton!
        $html ='<div id="fractionsliderbanner_cfg_product_selection_product_additem">'.$html.'</div>';
	return $html;
    }
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn($column)
                ->toHtml();
        }

        return '<textarea name="' . $inputName . '"' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"'.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '') . '>#{' . $columnName . '}</textarea>';
    }
}