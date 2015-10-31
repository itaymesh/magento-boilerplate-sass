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


abstract class Boilerplate_Ajaxcart_Block_System_Config_Form_Field_Ajaxcartpro_Confirmation extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getHtmlId()
    {
        throw new Exception('Method must be implemented');
    }

    protected function _getPathToSetting()
    {
        throw new Exception('Method must be implemented');
    }

    protected function _previewJsInitString()
    {
        throw new Exception('Method must be implemented');
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $config = array(
            'name'      => $this->_getPathToSetting(),
            'html_id'   => $this->_getHtmlId(),
            'label'     => 'Content',
            'title'     => 'Content',
            'style'     => 'height:36em;width:600px',
            'required'  => true,
            'config'    => $this->_getWysiwygConfig()
        );
        $element->addData($config);
        return $element->getElementHtml();
    }

    protected function _getWysiwygConfig()
    {
        $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $config->addData(array('hidden' => true, 'enabled' => false));
        $config = $this->_addAjaxcartproVariables($config);
        $config = $this->_addPreviewButton($config);
        return $config;
    }

    protected function _addPreviewButton($config)
    {
        $plugins = $config->getData('plugins');
        if (!is_array($plugins)) {
            $plugins = array();
        }
        $plugins[] = array(
            'options' => array(
                'title' => $this->__('Preview'),
                'onclick' => $this->_previewJsInitString()
            )
        );
        $config->setData('plugins', $plugins);
        return $config;
    }

    private function _addAjaxcartproVariables($config)
    {
        $magentovariablePlugin = null;
        $plugins = $config->getData('plugins');
        foreach($plugins as $key => $item) {
            if ($item['name'] === 'magentovariable') {
                $magentovariablePlugin = array(
                    'key' => $key,
                    'data' => $item
                );
                break;
            }
        }
        if (is_null($magentovariablePlugin)) {
            return $config;
        }

        $options = $magentovariablePlugin['data']['options'];

        $originalUrl = $options['url'];
        $newUrl = Mage::getUrl('ajaxcartpro_admin/adminhtml_system_ajax/wysiwygPluginVariables');
        $options['url'] = $newUrl;
        $options['onclick']['subject'] = str_replace($originalUrl, $newUrl, $options['onclick']['subject']);

        $magentovariablePlugin['data']['options'] = $options;

        
        $plugins[$magentovariablePlugin['key']] = $magentovariablePlugin['data'];
        $config->setData('plugins', $plugins);
        return $config;
    }
}

