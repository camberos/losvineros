<?php

class Sns_Ajaxcart_Helper_Data extends Mage_Core_Helper_Abstract{
	public $ACProductName="";

	public function renderCartBlock(){
		$b =	Mage::getSingleton('core/layout');
        $sidebar =	$b	->createBlock('ajaxcart/cart_ajaxcart','cart_sidebar_clone');
        $sidebar		->updateDataBySessionName('sidebar');
		return  $sidebar->toHtml();
	}
	public function renderMiniCartBlock(){
		$b =	Mage::getSingleton('core/layout');
        $minicart=	$b	->createBlock('ajaxcart/cart_ajaxcart','mini-cart');
        $minicart		->updateDataBySessionName('minicart');
        return  $minicart->toHtml();
	}
	public function renderTopLink(){
		$layout = Mage::getSingleton('core/layout');
		$update = $layout->getUpdate();
        $update->load('checkout_cart_index');
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout->getBlock('content')->toHtml();
	}
	public function renderRelatedBlock(){
		Mage::helper('catalog/product_view')->initProductLayout();
		$b=Mage::getSingleton('core/layout');
		$productreleated= $b->createBlock('catalog/product_list_related')
							->setTemplate('catalog/product/list/related.phtml')  ;
		return $productreleated->renderView();
	}
	public function renderMiniWish(){
		$b=Mage::getSingleton('core/layout');
		$miniwish=	$b 	->createBlock('wishlist/customer_sidebar')
						->setTemplate('wishlist/sidebar.phtml')  ;;
		return  $miniwish->renderView();
	}
	public function renderProductCompare(){
		$b=Mage::getSingleton('core/layout');
		$productcompare= $b	->createBlock('catalog/product_compare_sidebar')
							->setTemplate('catalog/product/compare/sidebar.phtml')  ;
		return $productcompare->renderView();
	}
	public function renderCartCO(){
		$bc = Mage::getSingleton('core/layout');

		$totals = $bc	->createBlock('checkout/cart_totals')
						->setTemplate('checkout/cart/totals.phtml');
		$shipping = $bc ->createBlock('checkout/cart_shipping')
						->setTemplate('checkout/cart/shipping.phtml');
		$coupon = $bc	->createBlock('checkout/cart_coupon')
						->setTemplate('checkout/cart/coupon.phtml');
		// top methods
		$t_onepage = $bc->createBlock('checkout/onepage_link')
						->setTemplate('checkout/onepage/link.phtml');
		$t_methods = $bc->createBlock('core/text_list')
						->append($t_onepage, 'top_methods');
		//methods
		$onepage = $bc	->createBlock('checkout/onepage_link')
						->setTemplate('checkout/onepage/link.phtml');
		$multishipping = $bc	->createBlock('checkout/multishipping_link')
								->setTemplate('checkout/multishipping/link.phtml');
		$methods = 	$bc	->createBlock('core/text_list')
						->append($onepage, "onepage")
						->append($multishipping, "multishipping");
		$crossel = $bc
				->createBlock('checkout/cart_crosssell')
				->setTemplate('checkout/cart/crosssell.phtml');
        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
		$main = $bc
					->createBlock('checkout/cart')
					->setEmptyTemplate('checkout/cart/noItems.phtml')
					->setCartTemplate('checkout/cart.phtml')
					->setTemplate('checkout/cart.phtml')
					->setChild('top_methods',$t_methods)
					->setChild('totals', $totals)
					->setChild('shipping', $shipping)
					->setChild('coupon', $coupon)
					->setChild('methods', $methods)
					->setChild('crosssell', $crossel);
		$main->chooseTemplate();
		return $main->renderView();
	}
	public function renderOptions(){
        $product = Mage::registry('current_product');
        if (!$product->isConfigurable() && $product->getTypeId() != 'bundle' && $product->getTypeId() != 'simple' && $product->getTypeId() != 'downloadable'&& $product->getTypeId() != 'virtual' && $product->getTypeId() != 'grouped') {
				echo 'false'; die();
		}
        if (Mage::helper('ajaxcart')->hasFileOption()) {
			//
		}
		if($product->getTypeId()=='grouped'){

			$g=Mage::getSingleton('core/layout');

			$product_type_data_extra=	$g	->createBlock('core/text_list','product_type_data_extra');
			if(version_compare(Mage::getVersion(),'1.4.0.1','>')){
				$reference_product_type_data_extra= $g	->createBlock('cataloginventory/stockqty_type_grouped','reference_product_type_data_extra')
													->setTemplate('cataloginventory/stockqty/composite.phtml');
				$product_type_data_extra->append($reference_product_type_data_extra);
			}
			$addtocart = $g		->createBlock('catalog/product_view','addtocart')
								->setTemplate('catalog/product/view/addtocart.phtml');
			$grouped=	$g	->createBlock('catalog/product_view_type_grouped','product_type_data')
							->setTemplate('sns/ajaxcart/grouped.phtml')
							->append($product_type_data_extra)
							->append($addtocart);
			return  $grouped->renderView();
		}else{
			$block = Mage::getSingleton('core/layout');
			//options.phtml
			$options = $block->createBlock('catalog/product_view_options', 'product_options')
								->setTemplate('catalog/product/view/options.phtml')
								->addOptionRenderer('text', 'catalog/product_view_options_type_text', 'catalog/product/view/options/type/text.phtml')
								->addOptionRenderer('file', 'catalog/product_view_options_type_file', 'catalog/product/view/options/type/file.phtml')
								->addOptionRenderer('select', 'catalog/product_view_options_type_select', 'catalog/product/view/options/type/select.phtml')
								->addOptionRenderer('date', 'catalog/product_view_options_type_date', 'catalog/product/view/options/type/date.phtml');
			$price = $block->createBlock('catalog/product_view', 'product_price')
								->setTemplate('catalog/product/view/price_clone.phtml');
			$js = $block->createBlock('core/template', 'product_js')
								->setTemplate('catalog/product/view/options/js.phtml');
			if ($product->getTypeId() == 'bundle'){
				$price->addPriceBlockType('bundle','bundle/catalog_product_price','sns/ajaxcart/bundle/catalog/product/view/price.phtml') ;
				$tierprices=$block->createBlock('bundle/catalog_product_view','tierprices')
								->setTemplate('bundle/catalog/product/view/tierprices.phtml');
				$extrahind=$block->createBlock('cataloginventory/qtyincrements','extrahint')
								->setTemplate('cataloginventory/qtyincrements.phtml');
				$bundle = $block->createBlock('bundle/catalog_product_view_type_bundle', 'type_bundle_options')
								->setTemplate('bundle/catalog/product/view/type/bundle/options.phtml');
								$bundle->addRenderer('select', 'bundle/catalog_product_view_type_bundle_option_select');
								$bundle->addRenderer('multi', 'bundle/catalog_product_view_type_bundle_option_multi');
								$bundle->addRenderer('radio', 'bundle/catalog_product_view_type_bundle_option_radio');
								$bundle->addRenderer('checkbox', 'bundle/catalog_product_view_type_bundle_option_checkbox');
				$bundlejs=$block->createBlock('bundle/catalog_product_view_type_bundle','jsforbundle')
								->setTemplate('sns/ajaxcart/bundle.phtml');
			}
			if ($product->isConfigurable()){
				$configurable = $block->createBlock('catalog/product_view_type_configurable', 'product_configurable_options')
								->setTemplate('catalog/product/view/type/options/configurable.phtml');
				$configurableData = $block->createBlock('catalog/product_view_type_configurable', 'product_type_data')
								->setTemplate('catalog/product/view/type/configurable.phtml');
			}
			if ($product->getTypeId() == 'downloadable'){
				$downloadable = $block->createBlock('downloadable/catalog_product_links', 'product_downloadable_options')
								->setTemplate('sns/ajaxcart/downloadable/catalog/product/links.phtml');
				$downloadableData = $block->createBlock('downloadable/catalog_product_view_type', 'product_type_data')
								->setTemplate('downloadable/catalog/product/type.phtml');
			}
			$addtocart = $block->createBlock('catalog/product_view','addtocart')
							->setTemplate('catalog/product/view/addtocart.phtml');
			$main = $block->createBlock('catalog/product_view')
							->setTemplate('sns/ajaxcart/wrapper.phtml')
							->append($js)
							->append($options);
			if(version_compare(Mage::getVersion(),'1.4.0.1','>')){
				$calendar = $block->createBlock('core/html_calendar', 'html_calendar')
						->setTemplate('page/js/calendar.phtml');
				$main ->append($calendar);
			}
			if ($product->isConfigurable())
			{
				$main->append($configurableData);
				$main->append($configurable);
			}
			if ($product->getTypeId() == 'downloadable')
			{
				$main->append($downloadableData);
				$main->append($downloadable);
				$main->insert($downloadable);
			}
			if ($product->getTypeId() == 'bundle')
			{
				$main->append($bundle);
				$main->insert($bundle);
				$main->append($tierprices);
				$main->append($extrahind);
				$main->append($bundlejs);
			}
			$main->append($price)->append($addtocart);
			return $main->renderView();
		}
	}

	public function renderWishlist(){
		$wishlist = Mage::getModel('wishlist/wishlist')
                ->loadByCustomer(Mage::getSingleton('customer/session')->getCustomer(), true);
		if(Mage::registry('wishlist')){
				Mage::unregister('wishlist');
		}
		Mage::register('wishlist', $wishlist);
		$block=Mage::getSingleton('core/layout');

		if(version_compare(Mage::getVersion(),'1.7.0.0','>')){
			$items = $block	->createBlock('wishlist/customer_wishlist_items','items')
							->setTemplate('wishlist/item/list.phtml');
				$item_image = $block	->createBlock('wishlist/customer_wishlist_item_column_image')
										->setTemplate('wishlist/item/column/image.phtml');
				$item_info = $block	->createBlock('wishlist/customer_wishlist_item_column_comment')
									->setTemplate('wishlist/item/column/info.phtml')
									->setTitle('title','Product Details and Comment');
				$item_cart = $block	->createBlock('wishlist/customer_wishlist_item_column_cart')
									->setTemplate('wishlist/item/column/cart.phtml')
									->setTitle('title','Add to Cart');
					$item_options = $block	->createBlock('wishlist/customer_wishlist_item_options');
				$item_cart ->append($item_options);
				$item_remove = $block	->createBlock('wishlist/customer_wishlist_item_column_remove')
										->setTemplate('wishlist/item/column/remove.phtml');
			$items 	->append($item_image)
					->append($item_info)
					->append($item_cart)
					->append($item_remove);
			$buttons = $block	->createBlock('core/text_list','control_buttons')
								->setTemplate('wishlist/item/list.phtml');
				$btn_share = $block	->createBlock('wishlist/customer_wishlist_button')
				->setTemplate('wishlist/button/share.phtml');
				$btn_tocart = $block	->createBlock('wishlist/customer_wishlist_button')
				->setTemplate('wishlist/button/tocart.phtml');
				$btn_update = $block	->createBlock('wishlist/customer_wishlist_button')
				->setTemplate('wishlist/button/update.phtml');
			$buttons 	->append($btn_share)
						->append($btn_tocart)
						->append($btn_update);
			$wishlist=	$block	->createBlock('wishlist/customer_wishlist')
								->setTemplate('wishlist/view.phtml')
								->append($items)
								->append($buttons)
								->setTitle('title','My Wishlist');
		}else{
			$wishitem= $block ->createBlock('"wishlist/customer_wishlist_item_options','item_options');
			$wishlist=	$block	->createBlock('wishlist/customer_wishlist')
								->setTemplate('wishlist/view.phtml')
								->append($wishitem);
		}
		return $wishlist->renderView();
	}
	public function renderCartTitle(){
		$count = Mage::helper('checkout/cart')->getSummaryCount();
        if( $count == 1 ) {
            $text = Mage::helper('ajaxcart')->__('My Cart (%s item)', $count);
        } elseif( $count > 0 ) {
            $text = Mage::helper('ajaxcart')->__('My Cart (%s items)', $count);
        } else {
            $text = Mage::helper('ajaxcart')->__('My Cart');
        }
        return $text;
	}
	public function renderWishlistTitle(){
		$count = Mage::helper('wishlist')->getItemCount();
        if( $count == 1 ) {
            $text = Mage::helper('ajaxcart')->__('My Wishlist (%s item)', $count);
        } elseif( $count > 0 ) {
            $text = Mage::helper('ajaxcart')->__('My Wishlist (%s items)', $count);
        } else {
            $text = Mage::helper('ajaxcart')->__('My Wishlist');
        }
        return $text;
	}
	public function sendResponse($co_sb, $carttitle, $ajaxcart, $releated = ''){
		$options="0";
		$wishlist="";
		$wishtitle="";
		$addtype="0";
		$hasproduct="0";

		if ($product = Mage::registry('current_product'))
        {
			$options=$product->getHasOptions();
			$hasproduct="1";
			if($product->getTypeId()=='grouped'){
				$options="1";
			}
	    }

	    Mage::getSingleton('checkout/session')->setIsajax("0");
		$iswishlist=Mage::getSingleton('checkout/session')->getIswishlist();

		$pdname=($this->ACProductName)?$this->ACProductName:"";
		$this->ACProductName='';

		$isfirst=Mage::getSingleton('checkout/session')->getIsfirst(); 	// check product will delete from wishlist with first click add to cart
		if($iswishlist==1 AND $isfirst==1){
			if($pdname==''){
				$pdname=(Mage::getSingleton('checkout/session')->getNameitem())?Mage::getSingleton('checkout/session')->getNameitem():"";
			}
			Mage::getSingleton('checkout/session')->setIsfirst('0');
			Mage::getSingleton('checkout/session')->setNameitem('');
			Mage::getSingleton('checkout/session')->setIswishlist('0');
			$wishlist	=	$this->renderWishlist();
			$wishtitle	=	$this->renderWishlistTitle();
			header('content-type: text/javascript');
			echo '{"addtype":"'.$addtype.'", "links":"'.$carttitle.'", "options":'.$options.', "wishlinks":"'.$wishtitle.'", "wishlist":'.json_encode($wishlist).', "ajaxcart":' . json_encode($ajaxcart). ', "co_sb":' . json_encode($co_sb) .', "pdname":'.json_encode($pdname).', "related":""}';
			die();
		}
		elseif($iswishlist==2 AND $isfirst==1){
			if($pdname==''){
				$pdname=(Mage::getSingleton('checkout/session')->getNameitem())?Mage::getSingleton('checkout/session')->getNameitem():"";
			}
			Mage::getSingleton('checkout/session')->setIsfirst('0');
			Mage::getSingleton('checkout/session')->setNameitem('');
			Mage::getSingleton('checkout/session')->setIswishlist('0');
			$miniwish	=	$this->renderMiniWish();
			$wishtitle	=	$this->renderWishlistTitle();
			header('content-type: text/javascript');
			echo '{"addtype":"'.$addtype.'", "links":"'.$carttitle.'","options":'.$options.', "wishlinks":"'.$wishtitle.'", "wishlist":'.json_encode($miniwish).', "ajaxcart":' . json_encode($ajaxcart).  ', "co_sb":' . json_encode($co_sb) .', "pdname":'.json_encode($pdname).', "related":""}';
			die();
		}
		else{
			try{
				if($product = Mage::getModel("ajaxcart/observer")->getProduct()){
					$hasproduct=1;
				}
			} catch (Mage_Core_Exception $e) {
				Mage::helper("logger")->error($e->getMessage());
			}

			$item_block = null;
			$added_info = null;
			if($hasproduct AND !$options){
				$item_layout = Mage::getSingleton('core/layout');
				$item_block = $item_layout->createBlock('catalog/product_list','item')->setTemplate('sns/ajaxcart/catalog/product/item.phtml')->setData('product', $product)->renderView();
				$added_info 	=	"";
			}
			header('content-type: text/javascript');
			echo '{"addtype":"'.$addtype.'", "links":"'.$carttitle.'","options":'.$options.', "ajaxcart":' . json_encode($ajaxcart). ', "co_sb":' . json_encode($co_sb) .', "pdname":'.json_encode($pdname).', "related":"", "pdinfo":{ "item":'.json_encode($item_block).', "other":'.json_encode($added_info).'}}';
			die();
		}
	}
    public function hasFileOption()
    {
        $product = Mage::registry('current_product');
        if ($product)
        {
            foreach ($product->getOptions() as $option)
            {
                if ($option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_FILE) return true;
            }
        }
        return false;
    }
    public function getJQquery(){
		//if (Mage::getStoreConfigFlag('slider_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquery')){
				Mage::register('sns.jquery', 1);
				return 'sns/ajaxcart/js/jquery.min.js';
			}
		//}
		return;
	}
	public function getJQqueryNoconflict(){
		//if (Mage::getStoreConfigFlag('slider_cfg/advanced/include_jquery')){
			if (null == Mage::registry('sns.jquerynoconflict')){
				Mage::register('sns.jquerynoconflict', 1);
				return 'sns/ajaxcart/js/sns.noconflict.js';
			}
		//}
		return;
	}
    public function getEffectCart(){
		//if (Mage::getStoreConfigFlag('slider_cfg/advanced/include_jquery')){
			//if (null == Mage::registry('sns.jquerynoconflict')){
				//Mage::register('sns.jquerynoconflict', 1);
				return 'sns/ajaxcart/js/effect.js';
			//}
		//}
		//return;
	}
}
