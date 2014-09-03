<?php
require_once 'Mage/Checkout/controllers/CartController.php';
class Sns_Ajaxcart_CartController extends Mage_Checkout_CartController{
    public function indexAction(){
		echo "log..."; die();
    }
	public function getproductidAction(){
		$request_url=$this->getRequest()->getPost('str');
		$request_url = explode(Mage::getBaseUrl(),$request_url);
		$url = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToFilter('request_path',$request_url[1]);
		$url_path = "";
		foreach($url as $u){
			$url_path = $u->getTargetPath();
		}
		if(!$url_path) {
			$params=explode("/",$request_url[1]);
			$keyproduct=array_search("product", $params);
			$idproduct=$params[$keyproduct+1];
		}
		else{
			echo $url_path;die();
		}
		$this->addAction();
	}
    protected function _initProduct(){
        $productId = (int) $this->getRequest()->getParam('product');
		$productId=(int)$params['product'];
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
    public function addAction(){
        $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();
        try {
            if (isset($params['qty'])) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                $params['qty'] = $filter->filter($params['qty']);
            }
            $product = $this->_initProduct();
			$related= $params['related_product'];
            /**
             * Check product availability
             */
            if (!$product) {
                $this->_goBack();
                return;
            }
            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }
			echo"";die();
            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);
			$this->loadLayout();
			$this->renderLayout();
			die();
			return $output;
            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );
            if (!$this->_getSession()->getNoCartRedirect(true)) {
                if (!$cart->getQuote()->getHasError()){
                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                    $this->_getSession()->addSuccess($message);
                }
                $this->_goBack();
            }
        }
        catch (Mage_Core_Exception $e) {
        	if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice($e->getMessage());
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError($message);
                }
            }
            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        }
        catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
            $this->_goBack();
        }
		return true;
    }
}