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
class Boilerplate_Ajaxcart_Model_System_Config_Source_Cartanimation
{
    public function toOptionArray()
    {
        $helper = Mage::helper('ajaxcartpro');
        return array(
			array('value' => 'none',    'label' => $helper->__('None')),
            array('value' => 'opacity', 'label' => $helper->__('Opacity')),
            array('value' => 'grow',    'label' => $helper->__('Grow')),
			array('value' => 'blink',   'label' => $helper->__('Blink'))
        );
    }
}