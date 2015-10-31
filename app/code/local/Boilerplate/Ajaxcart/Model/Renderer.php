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

class Boilerplate_Ajaxcart_Model_Renderer extends Varien_Object
{

    protected $_sources = array(
        'cart'          => 'ajaxcartpro/renderer_cart',
        'minicart'      => 'ajaxcartpro/renderer_minicart',
        'topLinks'      => 'ajaxcartpro/renderer_toplinks',
        'product'       => 'ajaxcartpro/renderer_product',
        'wishlist'      => 'ajaxcartpro/renderer_wishlist',
        'miniWishlist'  => 'ajaxcartpro/renderer_miniwishlist',
        'addProductConfirmation'     => 'ajaxcartpro/renderer_confirmation_addproduct',
        'removeProductConfirmation'  => 'ajaxcartpro/renderer_confirmation_removeproduct',
    );

    public function renderPartsFromLayout($layout, $partsToRender)
    {
        $html = array();
        $renderers = $this->_getRenderers($partsToRender);
        foreach($renderers as $name => $renderer) {
            $renderer->setActionData($this->getActionData());
            $html[$name] = $renderer->renderFromLayout($layout);
        }
        return $html;
    }

    protected function _getRenderers($partsToRender)
    {
        if (!is_array($partsToRender)) {
            return array();
        }
        $renderers = array();
        foreach ($partsToRender as $partName) {
            if (!isset($this->_sources[$partName])) {
                throw new Exception('Renderer is not specified: ' . $partName);
            }
            $renderers[$partName] = Mage::getModel($this->_sources[$partName]);
        }
        return $renderers;
    }

}
