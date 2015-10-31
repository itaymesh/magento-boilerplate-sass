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


class Boilerplate_Ajaxcart_Block_System_Config_Form_Field_Ajaxcartpro_Confirmation_Removeproduct extends Boilerplate_Ajaxcart_Block_System_Config_Form_Field_Ajaxcartpro_Confirmation
{

    protected function _getHtmlId()
    {
        return 'removeproductconfirmationcontent';
    }

    protected function _getPathToSetting()
    {
        return 'groups[removeproductconfirmation][fields][content][value]';
    }

    protected function _previewJsInitString()
    {
        $url = Mage::getUrl('ajaxcartpro/ajax/removeProductConfirmation');
        return
            "Object.extend({" .
                "contentSelector:'#{$this->_getHtmlId()}', " .
                "confirmationSelector:'#ajaxcartpro-remove-confirm' " .
            "}, AJAXCARTPRO_CONFIRMATIONBLOCK).preview('" . $url . "'); " .
            "return false;";
    }
}

