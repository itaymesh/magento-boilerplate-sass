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
class Boilerplate_Ajaxcart_Model_Renderer_Product extends Varien_Object implements Boilerplate_Ajaxcart_Model_Renderer_Interface {
	//const BLOCK_NAME = 'product.info.options.wrapper';

	const BLOCK_NAME = 'product.info';

	const TEMPLATE_PATH = 'boilerplate/ajaxcartpro/';


	/**
	 * Render quick view block HTML
	 *
	 * @param $layout Mage_Core_Model_Layout
	 *
	 * @return string
	 */
	public function renderFromLayout( $layout ) {

		$block = $layout->getBlock( 'product.info' );

		if ( ! $block ) {
			return null;
		}


		$block = $this->_setTemplates( $block, $layout );

		switch ( $block->getProduct()->getTypeId() ) {

			case Mage_Downloadable_Model_Product_Type::TYPE_DOWNLOADABLE:
				$block = $this->_addDownloadableBlock( $block, $layout );
				break;

			case Mage_Catalog_Model_Product_Type_Grouped::TYPE_CODE:
				$block = $this->_addGroupedBlock( $block, $layout );
				break;

			case Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE:
				$block = $this->_addConfigurableBlock( $block, $layout );
				break;

			case 'bundle':

				$block = $this->_addBundleBlock( $block, $layout );
				break;

		}


		$block = $this->_addGiftBlock( $block, $layout );


		return $this->_renderBlock( $block );
	}


	/**
	 * set product view custom template
	 *
	 * @param $block Mage_Catalog_Block_Product_View
	 * @param $layout Mage_Core_Model_Layout
	 *
	 * @return Mage_Catalog_Block_Product_View
	 */
	protected function _setTemplates( $block, $layout ) {

		$block->setTemplate(self::TEMPLATE_PATH . 'view.phtml' );

		$price = $layout->getBlock( 'product.clone_prices' );

		//$layout->removeOutputBlock('ajaxcartpro.product.list.after');

		//remove catalog_msrp
		$block->append( $price, 'product_price' );

		return $block;
	}

	/**
	 * @param $block Mage_Catalog_Block_Product_View
	 * @param $layout Mage_Core_Model_Layout
	 *
	 * @return Mage_Catalog_Block_Product_View
	 */
	protected function _addConfigurableBlock( $block, $layout ) {

		/*
		$configurableData = $layout->getBlock( 'product.info.configurable' );

		if ( ! $configurableData ) {
			return $block;
		}

		$configurable = $layout->getBlock( 'product.info.options.configurable' );

		$configurable->setTemplate(self::TEMPLATE_PATH . 'options/configurable.phtml' );

		$block->append( $configurableData, 'product_type_data' );
		$block->append( $configurable, 'product_configurable_options' );
		*/

		return $block;
	}

	/**
	 * @param $block Mage_Catalog_Block_Product_View
	 * @param $layout Mage_Core_Model_Layout
	 *
	 * @return Mage_Catalog_Block_Product_View
	 */

	protected function _addDownloadableBlock( $block, $layout ) {
		/*$downloadableData = $layout->getBlock( 'product.info.downloadable' );
		if ( ! $downloadableData ) {
			return $block;
		}
		$downloadable = $layout->getBlock( 'product.info.downloadable.options' );
		$downloadable->setTemplate(self::TEMPLATE_PATH . 'options/downloadable.phtml' );

		$block->append( $downloadableData, 'product_type_data' );
		$block->append( $downloadable, 'product_downloadable_options' );*/

		return $block;
	}

	/**
	 * @param $block Mage_Catalog_Block_Product_View
	 * @param $layout Mage_Core_Model_Layout
	 *
	 * @return Mage_Catalog_Block_Product_View
	 */
	protected function _addGroupedBlock( $block, $layout ) {
		$grouped = $layout->getBlock( 'product.info.grouped' );
		if ( ! $grouped ) {
			return $block;
		}
		$block->append( $grouped, 'product_type_data' );

		return $block;
	}

	/**
	 * @param $block Mage_Catalog_Block_Product_View
	 * @param $layout Mage_Core_Model_Layout
	 *
	 * @return Mage_Catalog_Block_Product_View
	 */
	protected function _addBundleBlock( $block, $layout ) {
		$bundleData = $layout->getBlock( 'product.info.bundle' );
		if ( ! $bundleData ) {
			return $block;
		}
		$bundle = $layout->getBlock( 'product.info.bundle.options' );

		$block->append( $bundleData, 'product_type_data' );
		$block->append( $bundle, 'product_bundle_options' );

		return $block;
	}

	/**
	 * @param $block Mage_Catalog_Block_Product_View
	 * @param $layout Mage_Core_Model_Layout
	 *
	 * @return Mage_Catalog_Block_Product_View
	 */
	protected function _addGiftBlock( $block, $layout ) {
		$giftData = $layout->getBlock( 'product.info.giftcard' );
		if ( ! $giftData ) {
			return $block;
		}

		$block->append( $giftData, 'product_type_data' );

		return $block;
	}

	/**
	 * render quick view
	 * @param $block Mage_Catalog_Block_Product_View
	 *
	 * @return string
	 */
	protected function _renderBlock( $block ) {
		$path          = 'sales/msrp/enabled';
		$oldMsrpConfig = Mage::app()->getStore()->getConfig( $path );
		Mage::app()->getStore()->setConfig( $path, '0' );
		$html = $block->toHtml();
		Mage::app()->getStore()->setConfig( $path, $oldMsrpConfig );

		return $html;
	}
}