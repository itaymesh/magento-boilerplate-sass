<?php

class Boilerplate_Widget_Block_Banner extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    /**
     * @return string
     */
    public function getTitle() {
        return $this->_formatText($this->getData('title'));
    }

    public function getDescription() {
        return $this->_formatText($this->getData('description'));
    }

    public function getWidgetUrl() {
        return $this->getUrl($this->getData('url'));
    }

    /**
     * Set the posts collection
     *
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        if (!$this->getTemplate()) {
            $this->setTemplate('boilerplate/core/widget/home/banner.phtml');
        }

        return $this;
    }

    /**
     * @return string
     */
    function _formatText($data) {
        return nl2br(Mage::helper('core')->escapeHtml($data));
    }
}