<?php

/**
 * Created by PhpStorm.
 * User: omri
 * Date: 11/5/14
 * Time: 3:26 PM
 */
class Boilerplate_BootstrapNavigation_Model_Observer extends Mage_Catalog_Model_Observer
{
	/**
	 * add menu column for top categories
	 * @param Varien_Event_Observer $observer
	 * @return $this
	 */
	public function catalogCategoryCollectionLoadBefore(Varien_Event_Observer $observer)
	{

		$collection = $observer->getEvent()->getCategoryCollection();
		$collection->addAttributeToSelect('category_menu_cms_block');
		return $this;
	}



	/**
	 * Adds catalog categories to top menu
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function addCatalogToTopmenuItems(Varien_Event_Observer $observer)
	{

		$block = $observer->getEvent()->getBlock();

		$block->addCacheTag(Mage_Catalog_Model_Category::CACHE_TAG);

		parent::addCatalogToTopmenuItems($observer);

		$rootCategory = Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId());

		$children = $observer->getMenu()->getChildren();

		$idFormat = 'category-node-%s';




		foreach ($rootCategory->getChildrenCategories() as $category) {
			if ($category->hasCategoryMenuCmsBlock()) {
				if ($child = $children->searchById(sprintf($idFormat, $category->getId()))) {
					$child->setCmsBlock($category->getCategoryMenuCmsBlock());
				}
			}

		}

	}

}