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


class Boilerplate_Ajaxcart_Model_Renderer_Confirmation_Removeproduct extends Varien_Object implements Boilerplate_Ajaxcart_Model_Renderer_Interface
{
    const BLOCK_NAME = 'aw.ajaxcartpro.confirm.removeproduct';

    public function renderFromLayout($layout)
    {
        $block = $layout->getBlock(self::BLOCK_NAME);
        if (!$block) {
            return null;
        }
        $block = $this->_addDataToBlock($block);
        return $block->toHtml();
    }

    private function _addDataToBlock($block)
    {
        $actionData = $this->getData('action_data');
        if (isset($actionData['removed_product'])) {
            $block->setData('product_id', $actionData['removed_product']);
        }
        return $block;
    }
}