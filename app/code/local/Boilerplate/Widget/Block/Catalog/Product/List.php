<?php
class Boilerplate_Widget_Block_Catalog_Product_List
	extends Mage_Catalog_Block_Product_Abstract
	implements Mage_Widget_Block_Interface {

	var $_category = null;
	var $_productCollection = null;


	protected function _construct()
	{
		parent::_construct();
		$this->addPriceBlockType('bundle', 'bundle/catalog_product_price', 'bundle/catalog/product/price.phtml');

		$_cacheLifetime = $this->getData('cache_lifetime');
		$this->addData(array('cache_lifetime' => $_cacheLifetime ? $_cacheLifetime : 86400));
		$this->addCacheTag(Mage_Catalog_Model_Product::CACHE_TAG);
	}

	/**
	 * Get Key pieces for caching block content
	 *
	 * @return array
	 */
	public function getCacheKeyInfo()
	{
		return array(
			'CATALOG_CATEGORY_PRODUCT_WIDGET',
			Mage::app()->getStore()->getId(),
			Mage::getDesign()->getPackageName(),
			Mage::getDesign()->getTheme('template'),
			Mage::getSingleton('customer/session')->getCustomerGroupId(),
			'template' => $this->getTemplate(),
			$this->getProductsCount()
		);
	}

	/**
	 * Prepare and return product collection
	 *
	 * @return Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
	 */
	protected function _getProductCollection()
	{
		/** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
		$collection = Mage::getResourceModel('catalog/product_collection');
		$collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());
		$collection = $this->_addProductAttributesAndPrices($collection);
		$collection->addCategoryFilter($this->getCategory());
		$collection->setPageSize($this->getProductsCount())
		->setCurPage(1);

		return $collection;
	}

	/**
	 * Prepare collection with new products
	 *
	 * @return Mage_Core_Block_Abstract
	 */
	protected function _beforeToHtml()
	{
		$this->setProductCollection($this->_getProductCollection());
		return parent::_beforeToHtml();
	}




	public function getCategory()
	{
		if (!$this->_category) {
			$path = $this->getData('id_path');
			$categoryId = str_replace('category/', '', $path);

			$_category = Mage::getModel('catalog/category')->load($categoryId);

			if ($_category->getId()) {
				$this->_category = $_category;
			}
		}

		return $this->_category;
	}

	public function getId() {
		return 'pc-' . str_replace('.', '-', $this->getNameInLayout());
	}
}