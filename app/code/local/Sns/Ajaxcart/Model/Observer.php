<?php
/**
 * AdminNotification observer
 *
 * @category   Mage
 * @package    Mage_AdminNotification
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Sns_Ajaxcart_Model_Observer
{
    /**
     * Predispath admin action controller
     *
     * @param Varien_Event_Observer $observer
     */
	public function preDispatch(Varien_Event_Observer $observer){
		return;
		$params=Mage::app()->getFrontController()->getRequest()->getParams();
		Zend_Debug::dump(Mage::getSingleton('core/layout')->getUpdate()->asString());die;
	}

	/**
	 * Postdispath admin action controller
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function postDispatch(Varien_Event_Observer $observer){
		$params= Mage::app()->getFrontController()->getRequest()->getParams();

		if(!$params["isajax"] AND !Mage::getSingleton('checkout/session')->getAjax()){
			return false;
		}
		if(!Mage::getSingleton('checkout/session')->getAjax()){
			Mage::getSingleton('checkout/session')->setAjax("1");
		}
		else{
		}
		if($params){
			Mage::getSingleton('checkout/session')->setAjaxParams($params);
		}
		else{
			$params = Mage::getSingleton('checkout/session')->getAjaxParams();
		}
		if($params["create"]=="false"){
			$layout = Mage::getSingleton('core/layout');
			$item_block = $layout->getBlock( $params["name"] );
			if($item_block){
				if($params["template"]){
					$item_block->setTemplate($params["template"]);
				}
				Mage::getSingleton('checkout/session')->setAjax("0");
				Mage::getSingleton('checkout/session')->setAjaxParams("0");
				echo $item_block->toHtml();die;
			}
			else{
				Mage::setIsDeveloperMode(true);
				Mage::helper("logger")->info("[create=false] not exist this block have name > ".$params["name"]);
				return false;
			}
		}
		else {
			$item_layout = Mage::getSingleton('core/layout');
			if($item_block = $item_layout->createBlock($params["class_name"], $params["name"])){
				if($params["template"]){
					$item_block->setTemplate($params["template"]);
				}
			}
			else {
				Mage::setIsDeveloperMode(true);
				Mage::helper("logger")->info("[create=true] not exist this block have name > ".$params["name"]);
				return;
			}
		}
		if($params["onlychild"]=="true"){
			Zend_Debug::dump($item_block->getChild());
		}
		else{
			echo $item_block->renderView();
		}
		exit();
	}
    protected function _initProduct()
    {
        $productId = (int) Mage::app()->getFrontController()->getRequest()->getParam('product');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }
	/**
	 * @return Ambigous <boolean, Mage_Core_Model_Abstract, Mage_Core_Model_Abstract>
	 */
	public function getProduct(){
		return $this->_initProduct();
	}
    /**
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

	public function getNameProduct($observer){
		Mage::getSingleton('checkout/session')->setNameitem($observer->getProduct()->getName());
		return;
	}
	public function Wishtocheckout(){
			if(Mage::getSingleton('checkout/session')->getIsajax()=='1'){
				if (Mage::getSingleton('checkout/session')->getIspage()!='1'){
					$cart = Mage::helper('ajaxcart')->renderCartBlock();
					$text = Mage::helper('ajaxcart')->renderCartTitle();
					$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock();
					Mage::helper('ajaxcart')->sendResponse($cart, $text, $ajaxcart);
				}else{
					Mage::getSingleton('checkout/session')->setIspage("0");
					$cart = Mage::helper('ajaxcart')->renderCartCO();
					$text = Mage::helper('ajaxcart')->renderCartTitle();
					$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock();
					Mage::helper('ajaxcart')->sendResponse($cart, $text, $ajaxcart);
				}

			}
	}

	public function addToCartWishList(){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=""){

				if(version_compare(Mage::getVersion(),'1.4.0.1','>=')){
					Mage::getSingleton('checkout/session')->setIsfirst("1");
				}else{
					$message=Mage::getSingleton('checkout/session')->getMessages();

					if($message->getItems('error')) {
						Mage::getSingleton('checkout/session')->setIsfirst("2");
					}else{
						Mage::getSingleton('checkout/session')->setIsfirst("1");
					}
				}
				if(Mage::app()->getFrontController()->getRequest()->getParam('isWLPage')){
					Mage::getSingleton('checkout/session')->setIswishlist("1");
				}else{
					Mage::getSingleton('checkout/session')->setIswishlist("2");
				}
				Mage::getSingleton('checkout/session')->setIsajax("1");	// isajax =1 allow when request go to controller product/view with only product type is group , can check this request is ajax
				Mage::getSingleton('catalog/session')->getData('messages')->clear();
				if(Mage::getSingleton('wishlist/session')->getMessages()->getItems('error')){
					Mage::getSingleton('wishlist/session')->getData('messages')->clear();
					$itemId     = (int)Mage::app()->getFrontController()->getRequest()->getParam('item');
					/* @var $item Mage_Wishlist_Model_Item */
					if(Mage::getModel('wishlist/item')->load($itemId)){
						$item       = Mage::getModel('wishlist/item')->load($itemId);
					}else{
						return;
					}
					$redirectUrl= $item->getProductUrl();
					return Mage::app()->getResponse()->setRedirect($redirectUrl);
				}
				return;
		}
	}
	public function addWishlist(){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isWLPage')!=""){

				$product=$this->_initProduct();
				Mage::getSingleton('customer/session')->getData('messages')->clear();
				if(Mage::app()->getFrontController()->getRequest()->getParam('isWLPage')=='0'){
					$wishlist = Mage::helper('ajaxcart')->renderMiniWish();
				}
				else{
					$wishlist= Mage::helper('ajaxcart')->renderWishlist();
				}
				$text = Mage::helper('ajaxcart')->renderWishlistTitle();

				Mage::helper('ajaxcart/Sendhtml')->setAddwhat("1");
				Mage::helper('ajaxcart/Sendhtml')->ACProductName=$product->getName();
				Mage::helper('ajaxcart/Sendhtml')->sendResponse($wishlist, $text);

		}
	}
	public function addProductCompare(){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=""){
				$product=$this->_initProduct();
				Mage::getSingleton('catalog/session')->getData('messages')->clear();
				$productcompare = Mage::helper('ajaxcart')->renderProductCompare();
				$text = "";
				Mage::helper('ajaxcart/Sendhtml')->setAddwhat("2");
				Mage::helper('ajaxcart/Sendhtml')->ACProductName=$product->getName();
				Mage::helper('ajaxcart/Sendhtml')->sendResponse($productcompare, $text);
		}
	}

	public function addToCart($observer){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=""){
			$product=$this->_initProduct();

			$message=Mage::getSingleton('checkout/session')->getMessages();
			$hasnotice=false;

			if($message->getItems('notice')) {
				$hasnotice=true;
			}

			Mage::getSingleton('checkout/session')->getData('messages')->clear(); 

			$ajax=Mage::app()->getFrontController()->getRequest()->getParams(); 

			if ($product->getTypeId() == 'grouped' AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=null AND !isset($ajax['related_product'])) {
					Mage::getSingleton('checkout/session')->setIsajax("1");
					return;
			}

			if($product->getHasOptions() AND $hasnotice){
				$hasnotice=false;
				Mage::getSingleton('checkout/session')->setIsajax("1");
				Mage::getSingleton('checkout/session')->setIspage(Mage::app()->getFrontController()->getRequest()->getParam('isCOPage'));
				return;
			}
			if(Mage::app()->getFrontController()->getRequest()->getParam('miniwishtocart')){
				Mage::getSingleton('checkout/session')->setIsfirst("1");
			}
			if (!Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')){
					Mage::helper('ajaxcart')->ACProductName=$product->getName();
					$cart = Mage::helper('ajaxcart')->renderCartBlock($ajax);
					$text = Mage::helper('ajaxcart')->renderCartTitle();
					$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock($ajax);
					//$releated = Mage::helper('ajaxcart')->renderRelatedBlock();
					Mage::helper('ajaxcart')->sendResponse($cart, $text, $ajaxcart);
			}else{
					Mage::helper('ajaxcart')->ACProductName=$product->getName();
					$cart = Mage::helper('ajaxcart')->renderCartCO();
					$text = Mage::helper('ajaxcart')->renderCartTitle();
					$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock($ajax);
					Mage::helper('ajaxcart')->sendResponse($cart, $text, $ajaxcart);
			}
		}
	}

	public function updateCart($observer){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=""){
			$ajax=Mage::app()->getFrontController()->getRequest()->getParams();
			if (!Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')){
					$cart = Mage::helper('ajaxcart')->renderCartBlock($ajax);
					$text = Mage::helper('ajaxcart')->renderCartTitle();
					$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock($ajax);
					Mage::helper('ajaxcart')->sendResponse($cart, $text, $ajaxcart);
			}else{
					$cart = Mage::helper('ajaxcart')->renderCartCO();
					$text = Mage::helper('ajaxcart')->renderCartTitle();
					$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock($ajax);
					Mage::helper('ajaxcart')->sendResponse($cart, $text, $ajaxcart);
			}
		}
	}
	public function addOptionsWishList($observer){
		$this->addOptions($observer);
	}
	public function addOptions($observer){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable')){

			$ajax = Mage::app()->getFrontController()->getRequest()->getParams();//

			$isajax=Mage::getSingleton('checkout/session')->getIsajax();
			if(!isset($isajax)){
				Mage::getSingleton('checkout/session')->setIsajax('0');
				$isajax=0;
			}
			if (!isset($ajax['isCOPage']) AND $isajax=='0') return;

			Mage::getSingleton('checkout/session')->setIsajax('0');
			$cart = Mage::helper('ajaxcart')->renderOptions();
			$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock();
			Mage::helper('ajaxcart')->sendResponse($cart,"", $ajaxcart);
		}
	}
	public function removeProduct() {
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=""){
			Mage::getSingleton('checkout/session')->getData('messages')->clear();
			if(!Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')){
				$cart = Mage::helper('ajaxcart')->renderCartBlock();
			}else{
				$cart = Mage::helper('ajaxcart')->renderCartCO();
			}
			$text = Mage::helper('ajaxcart')->renderCartTitle();
			$ajaxcart = Mage::helper('ajaxcart')->renderMiniCartBlock();
			Mage::helper('ajaxcart')->sendResponse($cart, $text, $ajaxcart);
			exit();
		}
	}
	public function removeWish(){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isWLPage')!=""){
			Mage::getSingleton('customer/session')->getData('messages')->clear();
			Mage::helper('ajaxcart/Sendhtml')->setAddwhat("1");
			if(!Mage::app()->getFrontController()->getRequest()->getParam('isWLPage')){
				$wish=Mage::helper('ajaxcart')->renderMiniWish();
			}else{
				$wish=Mage::helper('ajaxcart')->renderWishlist();
			}
			$text=Mage::helper('ajaxcart')->renderWishlistTitle();
			Mage::helper('ajaxcart/Sendhtml')->sendResponse($wish, $text);
			exit();
		}
	}
	public function removeProductCompare(){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=""){
				Mage::getSingleton('catalog/session')->getData('messages')->clear();
				$productcompare = Mage::helper('ajaxcart')->renderProductCompare();
				$text = "";
				Mage::helper('ajaxcart/Sendhtml')->setAddwhat("2");
				Mage::helper('ajaxcart/Sendhtml')->sendResponse($productcompare, $text);
				exit();
		}
	}
	public function clearProductCompare(){
		if(Mage::getStoreConfig('ajaxcart_cfg/general/enable') AND Mage::app()->getFrontController()->getRequest()->getParam('isCOPage')!=""){
				Mage::getSingleton('catalog/session')->getData('messages')->clear();
				$productcompare = Mage::helper('ajaxcart')->renderProductCompare();
				$text = "";
				Mage::helper('ajaxcart/Sendhtml')->setAddwhat("2");
				Mage::helper('ajaxcart/Sendhtml')->sendResponse($productcompare, $text);
				exit();
		}
	}
}
