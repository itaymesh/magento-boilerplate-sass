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


abstract class Boilerplate_Ajaxcart_Block_Confirmation_Abstract extends Mage_Core_Block_Template
{
    protected $_content = null;

    public function getContent($storeId = null)
    {
        throw new Exception('Method must be implemented');
    }

    public function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }

    protected function _toHtml()
    {
        $content = $this->_process($this->getContent());
        $this->setContent($content);
        return parent::_toHtml();
    }

    protected function _process($text)
    {
        $helper = Mage::helper('cms');
        $processor = $helper->getPageTemplateProcessor();
        $processor->setVariables($this->_getVariables());
        return $processor->filter($text);
    }

    private function _getVariables()
    {
        $variables = array();
        if ($productId = $this->getData('product_id')) {
            $product = Mage::getModel('catalog/product')->load($productId);
            if (!is_null($product->getId())) {
                $variables['product'] = $product;
            }
        }
        return $variables;
    }
}