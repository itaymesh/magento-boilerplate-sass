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
 * @copyright  Copyright (c) 2015 Studio Raz (http://studioraz.co.il/)
 * @license    http://studioraz.co.il/eula.html
 *
 *
 * Image chooser element for widgets.
 *
 */
class Boilerplate_Widget_Block_Adminhtml_Chooser extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{

	/**
	 * Render element.
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return string
	 */
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$previewHtml = '';
		if ($element->getValue()) {

			// Add image preview.
			$url = $element->getValue();

			if( !preg_match("/^http\:\/\/|https\:\/\//", $url) ) {
				$url = Mage::getBaseUrl('media') . $url;
			}

			$previewHtml = '<a href="' . $url . '"'
			               . ' onclick="imagePreview(\'' . $element->getHtmlId() . '_image\'); return false;">'
			               . '<img src="' . $url . '" id="' . $element->getHtmlId() . '_image" title="' . $element->getValue() . '"'
			               . ' alt="' . $element->getValue() . '" height="40" class="small-image-preview v-middle"'
			               . ' style="margin-top:7px; border:1px solid grey" />'
			               . '</a> ';
		}

		$prefix = $element->getForm()->getHtmlIdPrefix();
		$elementId = $prefix . $element->getId();

		$chooserUrl = $this->getUrl('*/cms_wysiwyg_images_chooser/index', array('target_element_id' => $elementId));

		$label = ($element->getValue()) ? $this->__('Change Image') : $this->__('Insert Image');

		$chooseButton = $this->getLayout()->createBlock('adminhtml/widget_button')
		                     ->setType('button')
		                     ->setClass('btn-chooser')
		                     ->setLabel($label)
		                     ->setOnclick('MediabrowserUtility.openDialog(\'' . $chooserUrl . '\')')
		                     ->setDisabled($element->getReadonly())
		                     ->setStyle('display:inline;margin-top:7px');

		// Add delete button.
		$removeButton = $this->getLayout()->createBlock('adminhtml/widget_button')
		                     ->setType('button')
		                     ->setClass('delete')
		                     ->setLabel($this->__('Remove Image'))
		                     ->setOnclick('document.getElementById(\''. $elementId .'\').value=\'\';if(document.getElementById(\''. $elementId .'_image\'))document.getElementById(\''. $elementId .'_image\').parentNode.remove()')
		                     ->setDisabled($element->getReadonly())
		                     ->setStyle('margin-left:10px;margin-top:7px');

		$element->setData('after_element_html', $previewHtml . $chooseButton->toHtml() . $removeButton->toHtml());

		$this->_element = $element;
		return $this->toHtml();
	}
}