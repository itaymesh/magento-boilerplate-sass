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


class Boilerplate_Ajaxcart_Helper_Catalog extends Mage_Core_Helper_Abstract
{

    public function getSubmitUrl(Mage_Catalog_Block_Product_Abstract $block, $product)
    {
        if (method_exists($block, 'getSubmitUrl')) {
            return $block->getSubmitUrl($product);
        }
        return $block->getAddToCartUrl($product);
    }

    public function getProductUrl($product, $additional = array())
    {
        if ($this->hasProductUrl($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            return $product->getUrlModel()->getUrl($product, $additional);
        }

        return '#';
    }

    public function hasProductUrl($product)
    {
        if ($product->getVisibleInSiteVisibilities()) {
            return true;
        }
        if ($product->hasUrlDataObject()) {
            if (in_array($product->hasUrlDataObject()->getVisibility(), $product->getVisibleInSiteVisibilities())) {
                return true;
            }
        }
        return false;
    }

    public function hasFileOption($product)
    {
        foreach ($product->getOptions() as $option) {
            if ($option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_FILE) {
                return true;
            }
        }
        return false;
    }
}
