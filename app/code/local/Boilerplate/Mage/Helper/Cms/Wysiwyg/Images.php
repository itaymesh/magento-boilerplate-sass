<?php
/**
 * Created by PhpStorm.
 * User: omri
 * Date: 2/22/15
 * Time: 3:53 PM
 */
class  Boilerplate_Core_Helper_Cms_Wysiwyg_Images extends Mage_Cms_Helper_Wysiwyg_Images {
	public function convertIdToPath($id)
	{
		$path = $this->idDecode($id);

		$realpath = $this->getStorageRoot();
		$mediadir = Mage::getConfig()->getOptions()->getMediaDir();
		if (is_link(rtrim($mediadir, '/')) || is_link(rtrim($realpath, '/'))) {
			$realpath = realpath($realpath);
		}
		if (!strstr($path, $realpath)) {
			$path = $realpath . $path;
		}
		return $path;
	}


	/**
	 * Prepare Image insertion declaration for Wysiwyg or textarea(as_is mode)
	 *
	 * @param string $filename Filename transferred via Ajax
	 * @param bool $renderAsTag Leave image HTML as is or transform it to controller directive
	 * @return string
	 */
	public function getImageHtmlDeclaration($filename, $renderAsTag = false)
	{

		$fileurl = $this->getCurrentUrl() . $filename;
		$mediadir = Mage::getConfig()->getOptions()->getMediaDir();

		// check if media folder is symlink to remove full path from URL
		if (is_link(rtrim($mediadir, '/'))) {
			$realpath = realpath($mediadir);
			$realpath = $this->convertPathToUrl($realpath);
			$fileurl = str_replace($realpath, '', $fileurl);
		}

		$mediaPath = str_replace(Mage::getBaseUrl('media'), '', $fileurl);

		$directive = sprintf('{{media url="%s"}}', $mediaPath);
		if ($renderAsTag) {
			$html = sprintf('<img src="%s" alt="" />', $this->isUsingStaticUrlsAllowed() ? $fileurl : $directive);
		} else {
			if ($this->isUsingStaticUrlsAllowed()) {
				$html = $fileurl; // $mediaPath;
			} else {
				$directive = Mage::helper('core')->urlEncode($directive);
				$html = Mage::helper('adminhtml')->getUrl('*/cms_wysiwyg/directive', array('___directive' => $directive));
			}
		}

		return $html;
	}
}