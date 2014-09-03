<?php
class Sns_Ajaxcart_IndexController extends Mage_Core_Controller_Front_Action{
	public function getblockAction(){
		$param = Mage::app()->getFrontController()->getRequest()->getParams();
		if($param["create"]!=="false"){
			$this->loadLayout();
			if($item_block = $this->getLayout()->getBlock($param["name"])){
				if($param["template"]){
					$item_block->setTemplate($param["template"]);
				}
			}
		}
		else {
			$item_layout = Mage::getSingleton('core/layout');
			if($item_block = $item_layout->createBlock($param["class_name"], $param["name"])){
				if($param["template"]){
					$item_block->setTemplate($param["template"]);
				}
			}
			else {
				echo"not exist this class ".$param["class_name"];
				die;
			}
		}
		if($param["onlychild"]=="true"){
			Zend_Debug::dump($item_block->getChild());
		}
		else{
			echo $item_block->renderView();
		}
		exit();
	}
	public function testAction(){
		$this->loadLayout();
		$this->renderLayout();
		$block = $this->getLayout()->createBlock('wishlist/customer_wishlist','name',array('template'=>'wishlist/view.phtml'));
		$this->getLayout()->addBlock($block);
		echo $this->getLayout()->getBlock('top.links')->toHtml();
	}
    public function indexAction(){
		$this->loadLayout();
		$this->renderLayout();
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
            if (!$product) {
                $this->_goBack();
                return;
            }
            $cart->addProduct($product, $params);
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }
            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);
			$this->loadLayout();
			$this->getLayout()->getBlock('checkout/cart_sidebar')->setTemplate('checkout/cart/sidebar.phtml');$this->renderLayout();
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