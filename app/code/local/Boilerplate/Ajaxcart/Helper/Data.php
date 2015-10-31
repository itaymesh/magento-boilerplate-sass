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

class Boilerplate_Ajaxcart_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function hasFileOption()
    {
        $product = Mage::registry('current_product');
        if ($product) {
            foreach ($product->getOptions() as $option) {
                if ($option->getType() == Mage_Catalog_Model_Product_Option::OPTION_TYPE_FILE) {
                    return true;
                }
            }
        }
        return false;
    }

    public function extensionEnabled($extension_name)
    {
        if (!isset($this->extensionEnabled[$extension_name]))
        {
            $modules = (array)Mage::getConfig()->getNode('modules')->children();
            if (!isset($modules[$extension_name])
                || $modules[$extension_name]->descend('active')->asArray()=='false'
                || Mage::getStoreConfig('advanced/modules_disable_output/'.$extension_name)
            ) $this->extensionEnabled[$extension_name] = false;
            else $this->extensionEnabled[$extension_name] = true;
        }
        return $this->extensionEnabled[$extension_name];
    }

    public function getWysiwygVariables()
    {
        $variables = array(
            'label' => $this->__('Studioraz Ajaxcartpro extension'),
            'value' => array(
                array(
                    'label' => $this->__('Product image'),
                    'value' => '{{block type="ajaxcartpro/confirmation_items_productimage" product="$product" resize="135"}}'
                ),
                array(
                    'label' => $this->__("'Continue' button"),
                    'value' => '{{block type="ajaxcartpro/confirmation_items_continue"}}'
                ),
                array(
                    'label' => $this->__("'Go to checkout' button"),
                    'value' => '{{block type="ajaxcartpro/confirmation_items_gotocheckout"}}'
                ),
            )
        );
        return $variables;
    }
}
