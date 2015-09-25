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
 */

class Boilerplate_Social_Block_Links extends Mage_Core_Block_Template {

	const XML_PATH_SOCIAL_LINKS = 'boilareplate/social_links';

	public function hasLinks() {
		return true;
		return $this->getStoreConfig() && count($this->getStoreConfig()) > 1;
	}

	public function getTitle() {
		return $this->getStoreConfig('title');
	}

	public function getLinks() {
		$config = $this->getStoreConfig();
		unset($config['title']);
		return $config;
	}

	private function getStoreConfig($path = null) {
		return Mage::getStoreConfig(self::XML_PATH_SOCIAL_LINKS . (isset($path) ? "/$path" : ''));
	}
}