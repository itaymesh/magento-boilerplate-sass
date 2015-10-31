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

class Boilerplate_Ajaxcart_AjaxController extends Mage_Core_Controller_Front_Action
{

    public function viewAction() {



        $productId  = (int) $this->getRequest()->getParam('id');
        $specifyOptions = $this->getRequest()->getParam('options');

        // Prepare helper and params
        $viewHelper = Mage::helper('ajaxcartpro/product');

        $params = new Varien_Object();

        $params->setSpecifyOptions($specifyOptions);

        // Render page
        try {
            $viewHelper->prepareAndRender($productId, $this, $params);
        } catch (Exception $e) {
            if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
                if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
                    $this->_redirect('');
                } elseif (!$this->getResponse()->isRedirect()) {
                    $this->_forward('noRoute');
                }
            } else {
                Mage::logException($e);
                $this->_forward('noRoute');
            }
        }
    }




    public function addProductConfirmationAction()
    {
        $textToGenerate = $this->getRequest()->getParam('textToGenerate', false);
        $this->loadLayout();

        $confirmationBlock = $this->_getConfirmationBlock('aw.ajaxcartpro.confirm.addproduct');
        $confirmationBlock->setData($this->_getDemoData());
        $confirmationBlock->setContent($textToGenerate);

        $this->renderLayout();
        return;
    }

    public function removeProductConfirmationAction()
    {
        $textToGenerate = $this->getRequest()->getParam('textToGenerate', false);
        $this->loadLayout();

        $confirmationBlock = $this->_getConfirmationBlock('aw.ajaxcartpro.confirm.removeproduct');
        $confirmationBlock->setData($this->_getDemoData());
        $confirmationBlock->setContent($textToGenerate);

        $this->renderLayout();
        return;
    }

    private function _getConfirmationBlock($blockName)
    {
        $layout = $this->getLayout();
        $block = $layout->getBlock($blockName);
        return $block;
    }

    private function _getDemoData()
    {
        $productCollection = Mage::getModel('catalog/product')->getResourceCollection();
        $count = $productCollection->getSize();
        $ids = $productCollection->getAllIds(1, $count -1);
        return array(
            'product_id' => $ids[0]
        );
    }
}