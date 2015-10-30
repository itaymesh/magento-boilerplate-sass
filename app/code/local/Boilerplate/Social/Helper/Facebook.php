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

class Boilerplate_Social_Helper_Facebook extends Mage_Core_Helper_Abstract {

	public function getAppConfig($configName)
	{
		return Mage::getStoreConfig('fbopengraph/open_graph/' . $configName);
	}

	public function getStoreLocale()
	{
		return Mage::app()->getLocale()->getLocaleCode();
	}

	public function getFacebookLoginUrl()
	{
		$params['redirect_uri'] = $this->_getUrl('fbopengraph/account/loginwithfacebook');
		$params['scope'] = Mage::helper('fbopengraph')->getAppConfig('fb_application_perms');
		return Mage::getSingleton('fbopengraph/facebooksdk')->getLoginUrl($params);
	}

	public function getFacebookLogoutUrl()
	{
		$params['next'] = Mage::helper('customer')->getLogoutUrl();
		return Mage::getSingleton('fbopengraph/facebooksdk')->getLogoutUrl($params);
	}

	public function loadUserByFacebookId($facebookId)
	{
		$facebookId = (string) $facebookId;
		$connection = Mage::getSingleton('core/resource') ->getConnection('core_read');
		$select = $connection->select()->
		from(
			Mage::getSingleton('core/resource')->getTableName('customer_entity'),
			array('entity_id')
		)->where('facebook_id=:facebook_id');

		if ($id = $connection->fetchOne($select, array('facebook_id' => $facebookId))) {
			if($customer = Mage::getModel('customer/customer')->load($id))
				return $customer;
		}
		return array();
	}
}