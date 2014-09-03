<?php
class Sns_Ajaxcart_Block_Cart_Ajaxcart extends Mage_Checkout_Block_Cart_Sidebar
{
	protected $_config = null;
	protected $_storeId = null;
	protected $_defaultTemplate = 'sns/ajaxcart/checkout/cart/mini-cart.phtml';
	protected $_defaultTemplateItemRenderer = 'sns/ajaxcart/checkout/cart/mini-cart/default.phtml';
	protected $_defaultSessionName = null;
	protected $_sessionReset = false;
    /**
     * Position in link list
     * @var int
     */
    protected $_position = 51;
	/**
	 * Class constructor
	 */
	public function _construct(){
		$selfData = $this->getData();
		$configuration = $this->_getConfiguration();
		if ( count($configuration) ){
			foreach ($configuration as $field => $value) {
				if (!array_key_exists($field, $selfData)){
					$selfData[$field] = $value;
				}
			}
		}
		$this->setData($selfData);

		$this->addItemRender('default', 'checkout/cart_item_renderer', 'sns/ajaxcart/checkout/cart/mini-cart/default.phtml');
	}
	protected function _getConfiguration($path = 'ajaxcart_cfg'){
		$configuration = Mage::getStoreConfig($path);
		$conf_merged = array();
		foreach( $configuration as $group ){
			foreach($group as $field => $value){
				if (!array_key_exists($field, $conf_merged)){
					$conf_merged[$field] = $value;
				}
			}
		}
		return $conf_merged;
	}
	public function getDefaultTemplate(){
		if (!$this->hasData('template')) {
			$this->setData('template', (($this->getTemplate())?$this->getTemplate():$this->_defaultTemplate));
		}
		return $this->getData('template');
	}
	public function getTemplateItemRenderer(){
		if (!$this->hasData('template_item_renderer')) {
			$this->setData('template_item_renderer', $this->_defaultTemplateItemRenderer);
		}
		return $this->getData('template_item_renderer');
	}
	public function _prepareLayout(){
		return parent::_prepareLayout();
	}

	protected function _toHtml(){
		if(!$this->getData('enable')){ return ; }
		$layout= $this	->getLayout();
		$this->setTemplate($this->getDefaultTemplate());

		$templateItemRenderer = $this->getTemplateItemRenderer();
		$this->addItemRender('default', 'checkout/cart_item_renderer', $templateItemRenderer)
		->addItemRender('simple', 'checkout/cart_item_renderer', $templateItemRenderer)
		->addItemRender('grouped', 'checkout/cart_item_renderer_grouped', $templateItemRenderer)
		->addItemRender('configurable', 'checkout/cart_item_renderer_configurable', $templateItemRenderer)
		->addItemRender('bundle', 'bundle/checkout_cart_item_renderer', $templateItemRenderer);
		if(version_compare(Mage::getVersion(),'1.4.0.1','>=')){
			$paypal			= $layout	->createBlock('paypal/express_shortcut','paypal_cart_sidebar.shortcut')
										->setTemplate('paypal/express/shortcut.phtml');
			$paypaluk		= $layout	->createBlock('paypaluk/express_shortcut','paypaluk_cart_sidebar.shortcut')
										->setTemplate('paypal/express/shortcut.phtml');
			$extra_actions	= $layout	->createBlock('core/text_list','extra_actions')
										->append($paypal)
										->append($paypaluk);
			$this->append($extra_actions);
		}
		return parent::_toHtml();
	}
	/**
	 * Get session data
	 *
	 * @param   Mage_Sales_Model_Quote_Item $item
	 * @return  string
	 */
	public function updateDataBySessionName($session_name = NULL ){
		$session_name = ($session_name)?$session_name:$this->getSessionName();
		$session_method = 'getAjaxcart'.$session_name;
		$session_data = call_user_func(
								array(
										Mage::getSingleton('checkout/session'),
										$session_method
									)
								);
		if(is_array($session_data) && count($session_data)){
			$this->setData(	$session_data );
		}
		return $this;
	}
	/**
	 * create session data
	 *
	 * @param   Mage_Sales_Model_Quote_Item $item
	 * @return  string
	 */
	public function createSessionDataByName($session_name = NULL )
	{
		$session_name = ($session_name)? $session_name: $this->getSessionName();
		$session_method = 'setAjaxcart'.$session_name;
		$this->setData(	'template' , $this->getDefaultTemplate() );
		call_user_func(
						array(
								Mage::getSingleton('checkout/session'),
								$session_method
						),
						$this->getData()
					);
		return $this;
	}
	public function getSessionName(){
		if (!$this->hasData('session_name')) {
			$this->setData('session_name', $this->_defaultSessionName);
		}
		return $this->getData('session_name');
	}
	public function getSessionReset(){
		if (!$this->hasData('session_reset')) {
			$this->setData('session_reset', $this->_sessionReset);
		}
		return $this->getData('session_reset');
	}
	/**
	 * Get item row html
	 *
	 * @param   Mage_Sales_Model_Quote_Item $item
	 * @return  string
	 */
	public function getItemHtml(Mage_Sales_Model_Quote_Item $item){
		$renderer = $this->getItemRenderer($item->getProductType())->setItem($item);
		$renderer ->setConfig($this->getData());
		return $renderer->toHtml();
	}
    /**
     * Get renderer block instance by product type code
     *
     * @param   string $type
     * @return  array
     */
    public function getItemRenderer($type){
        if (!isset($this->_itemRenders[$type])) {
            $type = 'default';
        }
        if (is_null($this->_itemRenders[$type]['blockInstance'])) {
             $this->_itemRenders[$type]['blockInstance'] = $this->getLayout()
                ->createBlock($this->_itemRenders[$type]['block'])
                    ->setTemplate($this->_itemRenders[$type]['template'])
                    ->setConfig($this->getData())
                    ->setRenderedBlock($this);
        }
        return $this->_itemRenders[$type]['blockInstance'];
    }
	public function setConfig($key = null, $value = null){
		if ( is_array($key) ){
			foreach ($key as $k => $v){
				$this->setData($k, $v);
			}
		} else if ( !is_null($key) ) {
			$this->setData($key, $value);
		}
	}

	public function getAjaxcart(){
		if (!$this->hasData('ajaxcart')) {
			$this->setData('ajaxcart', Mage::registry('ajaxcart'));
		}
		return $this->getData('ajaxcart');

	}

	public function getStoreId(){
		if (is_null($this->_storeId)){
			$this->_storeId = Mage::app()->getStore()->getId();
		}
		return $this->_storeId;
	}
	public function setStoreId($storeId=null){
		$this->_storeId = $storeId;
	}
	/**
	 * Return link position in link list
	 *
	 * @return in
	 */
	public function getPosition(){
		return $this->_position;
	}
}