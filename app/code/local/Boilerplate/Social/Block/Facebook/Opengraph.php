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
 * @copyright  Copyright (c) 2015 Studio Raz (http://studioraz.co.il/)
 * @license    http://studioraz.co.il/eula.html
 */
class Boilerplate_Social_Block_Facebook_Opengraph extends Mage_Core_Block_Template {


	private $_product;
	private $_category;

	public function getTitle() {
		if ( $this->getProduct() ) {
			return $this->getProductTitle();
		} elseif ( $this->getCategory() ) {
			return $this->getCategoryTitle();
		} else {
			return $this->getCurrentTitle();
		}

	}

	public function getType() {
		if ( $this->getProduct() ) {
			return 'product';
		} else {
			return 'website';
		}
	}

	public function getPageUrl() {
		if ( $this->getProduct() ) {
			return $this->getProductUrl();
		} else {
			return $this->getCurrentUrl();
		}
	}

	public function getImage() {
		if ( $this->getProduct() ) {
			return $this->getProductImage();
		} elseif ( $this->getCategory() ) {
			return $this->getCategoryImage();
		} else {
			return $this->getSiteLogoSrc();
		}
	}


	public function getDescription() {
		if ( $this->getProduct() ) {
			return $this->getProductDescription();
		} else {
			$head = $this->getLayout()->getBlock( 'head' );

			return $head->getDescription();
		}
	}


	private function getSiteTitle() {
		if ( empty( $this->_data['title'] ) ) {
			$this->_data['title'] = Mage::getStoreConfig( 'design/head/default_title' );
		}

		return htmlspecialchars( html_entity_decode( trim( $this->_data['title'] ), ENT_QUOTES, 'UTF-8' ) );
	}

	private function getSiteDescription() {
		if ( empty( $this->_data['description'] ) ) {
			$this->_data['description'] = Mage::getStoreConfig( 'design/head/default_description' );
		}

		return $this->_data['description'];
	}


	private function getSiteLogoSrc() {
		if ( empty( $this->_data['logo_src'] ) ) {
			$this->_data['logo_src'] = Mage::getStoreConfig( 'design/header/logo_src' );
		}

		return $this->getSkinUrl( $this->_data['logo_src'] );
	}

	private function getCurrentUrl() {
		return Mage::helper( 'core/url' )->getCurrentUrl();
	}

	private function getCurrentTitle() {
		return Mage::getSingleton( 'cms/page' )->getTitle();
	}


	private function getCategory() {
		if ( ! $this->_category ) {
			$this->_category = $this->hasData( 'category' ) ? $this->getData( 'category' ) : Mage::registry( 'current_category' );
		}

		return $this->_category;
	}

	private function getCategoryTitle() {
		return $this->getSiteTitle() . ' | ' . $this->getCategory()->getName();
	}

	private function getCategoryImage() {
		if ( $image = $this->getCategory()->getThumbnail() ) {
			return Mage::getBaseUrl( 'media' ) . 'catalog/category/' . $image;
		} elseif ( $image = $this->getCategory()->getImageUrl() ) {
			return $image;
		} else {
			return $this->getSiteLogoSrc();
		}
	}


	private function getProduct() {
		if ( ! $this->_product ) {
			$this->_product = $this->hasData( 'product' ) ? $this->getData( 'product' ) : Mage::registry( 'product' );
		}

		return $this->_product;
	}

	private function getProductImage() {
		if ( $product = $this->getProduct() ) {
			return Mage::helper( 'catalog/image' )->init( $product, 'image' );
		} else {
			return false;
		}
	}

	private function getProductTitle() {
		if ( $product = $this->getProduct() ) {
			return $product->getName();
		} else {
			return '';
		}
	}

	private function getProductUrl() {
		return Mage::helper( 'catalog/product' )->getProductUrl( $this->getProduct() );
	}

	private function getProductDescription() {
		$product = $this->getProduct();
		if ( $description = $product->getShortDescription() ) {
			return htmlspecialchars( strip_tags( $description ) );
		} elseif ( $description = $product->getDescription() ) {
			return htmlspecialchars( strip_tags( $description ) );
		} else {
			return $this->getSiteDescription();
		}
	}

}
