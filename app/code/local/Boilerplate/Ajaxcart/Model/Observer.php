<?php

/**
 * Studio Raz.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the  Studio Raz EULA that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://studioraz.co.il/eula.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://studioraz.co.il/ for more information
 * or send an email to support@studioraz.co.il
 *
 * @category   Boilerplate
 * @package    Boilerplate_Ajaxcart
 * @version    1.0.0
 * @copyright  Copyright (c) 2015 Studio Raz (http://studioraz.co.il/)
 * @license    http://studioraz.co.il/eula.html
 */
class Boilerplate_Ajaxcart_Model_Observer {

	public function beforeRenderLayout( $observer ) {
		$request = Mage::app()->getFrontController()->getRequest();
		if ( $request->getParam( 'awacp', false ) ) {

			$response = Mage::getModel( 'ajaxcartpro/response' );

			$response->renderBlocks()->send();

		}
	}

	public function sendResponseBefore( $observer ) {
		$request = Mage::app()->getFrontController()->getRequest();
		if ( $request->getParam( 'awacp', false ) ) {
			$response = Mage::getModel( 'ajaxcartpro/response' );
			$messages = $this->_getErrorMessages();
			if ( count( $messages ) > 0 ) {
				$response->setStatus( 'failed' );
				/* if ( $url = $this->_getRedirectUrl($response) ) {
					 $response->setRedirectTo($url);
					 $response->addMessage($messages);
				 } else {
					 $response->addError($messages);
				 }*/
			} else {
				$response->setStatus( 'success' );
			}
			if ( $response->getData( 'status' ) == 'success' ) {
				$session  = Mage::getSingleton( 'checkout/session' );
				$messages = $session->getMessages()->getLastAddedMessage()->getCode();
				// Mage::log('messages'.$messages);
				$response->addMessage( $messages );
			}
			$response->setData( 'data', Mage::registry( 'ajax_cart_data' ) );

			$response->renderBlocks()->send();
		}
	}

	public function loadLayoutBefore( $observer ) {
		$controllerAction = $observer->getAction();
		$layout           = $observer->getLayout();
		//add wysiwyg on system config section
		if ( $controllerAction->getFullActionName() === 'adminhtml_system_config_edit' &&
		     $controllerAction->getRequest()->getParam( 'section', false ) === 'ajaxcartpro'
		) {
			$layout->getUpdate()->addHandle( 'editor' );
		}
		//remove ACP from checkout (cart page is exception)
		if ( strpos( $controllerAction->getFullActionName(), 'checkout_' ) === 0 &&
		     strpos( $controllerAction->getFullActionName(), 'checkout_cart' ) !== 0
		) {
			$layout->getUpdate()->addHandle( 'remove_ajaxcartpro' );
		}
	}


	//ADD TO CART HACK
	public function checkoutCartProductAddAfter( $observer ) {
		$product = $observer->getProduct();

		if ( Mage::registry( 'ajax_cart_data' ) ) {
			return;
		}

		$data = new Varien_Object();
		$data->setAction( 'addProduct' );
		$data->setProductId( $product->getId() );
		$data->setProductName( $product->getName() );

		Mage::register( 'ajax_cart_data', $data );
	}

	/*
	private function _getRedirectUrl()
	{
		$request = Mage::app()->getFrontController()->getRequest();
		$action = Mage::app()->getFrontController()->getAction();

		if ($action instanceof Mage_Checkout_CartController && $request->getActionName() === 'add') {

			$productId = (int)$request->getParam('product', false);
			if (!$productId) {
				return false;
			}
			$product = Mage::getModel('catalog/product')->load($productId);
			if (!$product->isGrouped() && !$product->getTypeInstance(true)->hasRequiredOptions($product)) {
				return false;
			}
			$url = Mage::helper('ajaxcartpro/catalog')->getProductUrl($product, array('_query' => array('options' => 'cart')));
			return $url;

		} else if ($action instanceof Mage_Wishlist_IndexController && $request->getActionName() === 'cart') {
			$itemId = (int)$request->getParam('item', false);
			if (!$itemId) {
				return false;
			}
			$item = Mage::getModel('wishlist/item')->load($itemId);
			$productId = $item->getProductId();
			if (!$productId) {
				return false;
			}
			$product = Mage::getModel('catalog/product')->load($productId);
			if (!$product->isGrouped() && !$product->getTypeInstance(true)->hasRequiredOptions($product)) {
				return false;
			}
			$url = Mage::getUrl('wishlist/index/configure', array('id' => $itemId));
			return $url;
		}

		return false;
	}
	*/

	private function _getErrorMessages() {
		$allMessages = array_merge(
			$this->_getErrorMessagesFromSession( Mage::getSingleton( 'checkout/session' ) ),
			$this->_getErrorMessagesFromSession( Mage::getSingleton( 'wishlist/session' ) ),
			$this->_getErrorMessagesFromSession( Mage::getSingleton( 'catalog/session' ) )
		);

		return $allMessages;
	}

	private function _getErrorMessagesFromSession( $session ) {
		$messages        = $session->getMessages( true );
		$sessionMessages = array_merge(
			$messages->getItems( Mage_Core_Model_Message::ERROR ),
			$messages->getItems( Mage_Core_Model_Message::WARNING ),
			$messages->getItems( Mage_Core_Model_Message::NOTICE )
		);

		return $sessionMessages;
	}


}
