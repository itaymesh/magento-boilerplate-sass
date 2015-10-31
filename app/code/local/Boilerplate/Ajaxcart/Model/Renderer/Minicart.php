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

class Boilerplate_Ajaxcart_Model_Renderer_Minicart extends Varien_Object implements Boilerplate_Ajaxcart_Model_Renderer_Interface
{
    const BLOCK_NAME = 'minicart_content';

    public function renderFromLayout($layout)
    {
        $block = $layout->getBlock(self::BLOCK_NAME);
        if (!$block) {
            return null;
        }

        // add a flag to let the block (minicart.phtml) render a message notifying customer
        // the product had been added to cart.
        // the message is being removed after the minicart window is faded out (ajaxcartpro2.js)
        $block->setShowAddToCartMessage(true);
        return $block->toHtml();
    }
}