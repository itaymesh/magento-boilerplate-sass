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

class Boilerplate_Ajaxcart_Block_Confirmation_Items_Productimage extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        $product = $this->getProduct();
        if (!$product instanceof Varien_Object || !$product->getId()) {
            return '';
        }

        $resize = $this->getResize();
        if (is_null($resize)) {
            $resize = 265;
        }
        $helper = Mage::helper('catalog/image');

        $label = $product->getData('small_image_label');
        if (empty($label)) {
            $label = $product->getName();
        }

        $img = '<img src="' . $helper->init($product, 'small_image')->resize($resize) .
               '" alt="' . $this->htmlEscape($label) .
               '" title="' . $this->htmlEscape($label) .
               '" width="' . $resize .
               '" height="' . $resize .
               '" />';
        return $img;
    }
}