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

class Boilerplate_Social_Model_System_Config_Source_Perms {
	public function toOptionArray()
	{
		return array(
			array('value'=>Mage::helper('boilerplate_social')->__('email'), 'label'=>Mage::helper('boilerplate_social')->__('email')),
			array('value'=>Mage::helper('boilerplate_social')->__('user_birthday'), 'label'=>Mage::helper('boilerplate_social')->__('user_birthday')),
			array('value'=>Mage::helper('boilerplate_social')->__('read_stream'), 'label'=>Mage::helper('boilerplate_social')->__('read_stream')),
			array('value'=>Mage::helper('boilerplate_social')->__('publish_stream'), 'label'=>Mage::helper('boilerplate_social')->__('publish_stream')),
			array('value'=>Mage::helper('boilerplate_social')->__('offline_access'), 'label'=>Mage::helper('boilerplate_social')->__('offline_access')),
			array('value'=>Mage::helper('boilerplate_social')->__('user_checkins'), 'label'=>Mage::helper('boilerplate_social')->__('user_checkins')),
			array('value'=>Mage::helper('boilerplate_social')->__('rsvp_event'), 'label'=>Mage::helper('boilerplate_social')->__('rsvp_event')),
			array('value'=>Mage::helper('boilerplate_social')->__('sms'), 'label'=>Mage::helper('boilerplate_social')->__('sms')),
			array('value'=>Mage::helper('boilerplate_social')->__('publish_checkins'), 'label'=>Mage::helper('boilerplate_social')->__('publish_checkins')),
			array('value'=>Mage::helper('boilerplate_social')->__('user_likes'), 'label'=>Mage::helper('boilerplate_social')->__('user_likes')),
		);
	}
}