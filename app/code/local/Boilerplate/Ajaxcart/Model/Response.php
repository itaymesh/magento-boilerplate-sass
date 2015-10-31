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

class Boilerplate_Ajaxcart_Model_Response extends Varien_Object
{
    protected $_error = array();
    protected $_msg = array();

    /**
     * @param string|array $msg
     * @return void
     */
    public function addError($msg)
    {
        if (is_array($msg)) {
            foreach($msg as $item) {
                $this->addError($item);
            }
        } else if($msg instanceof Mage_Core_Model_Message_Abstract) {
            $this->_error[] = $msg->getText();
        } else {
            $this->_error[] = $msg;
        }
    }

    public function addMessage($msg)
    {

        if (is_array($msg)) {
            foreach($msg as $item) {
                $this->addMessage($item);
            }
        } else if($msg instanceof Mage_Core_Model_Message_Abstract) {
            $this->_msg[] = $msg->getText();
        } else {
            $this->_msg[] = $msg;
        }


    }

    public function isError()
    {
        return !empty($this->_error);
    }

    public function renderBlocks() {
        /* @var $request Mage_Core_Controller_Request_Http */
        $request = Mage::app()->getFrontController()->getRequest();
        $parts = $request->getParam('block');
        if (is_array($parts)) {
            /* @var $layout Mage_Core_Model_Layout */
            $layout = Mage::app()->getFrontController()->getAction()->getLayout();

            if (count($layout->getUpdate()->getHandles()) == 0) {
                $layout->getUpdate()->load('default');
                $layout->generateXml();
                $layout->generateBlocks();
            }

            $actionData = Zend_Json::decode($request->getParam('actionData', '[]'));
            $renderer = Mage::getModel('ajaxcartpro/renderer')->setActionData($actionData);
            try {
                $html = $renderer->renderPartsFromLayout($layout, $parts);
                $this->setBlocks($html);
            } catch(Boilerplate_Ajaxcart_Exception $e) {
                $this->addError($e->getMessage());
            } catch(Exception $e) {
                $this->addError($e->getMessage());
                Mage::logException($e);
            }
        }

        return $this;
    }

    public function send()
    {
        $_quote = $this->_getQuote();
        $this->addData(array(
            'cart' =>
                array(
                    'count' => $_quote->getItemsSummaryQty(),
                    'total' => Mage::helper('core')->formatPrice($_quote->getGrandTotal(), false)
                )
            )
        );
        echo $this->toJson();
        exit();
    }

    public function toJson(array $arrAttributes = array())
    {
        $this->setSuccess(true);
        if ($this->isError()) {
            $this->setSuccess(false);
            $this->setMessage($this->_error);
        } else {


            //$this->setMsg($this->_msg);

            //adding success message
            $session = Mage::getSingleton('checkout/session');
            if ($lastMessage = $session->getMessages()->getLastAddedMessage()) {
                $messages = $session->getMessages()->getLastAddedMessage()->getCode();
                $this->setMessage($messages);
            }


        }
        return parent::toJson($arrAttributes);
    }

    /**
     *
     * @return Mage_Checkout_Model_Session
     */
    private function _getQuote() {
        return Mage::getSingleton('checkout/session')->getQuote();
    }
}
