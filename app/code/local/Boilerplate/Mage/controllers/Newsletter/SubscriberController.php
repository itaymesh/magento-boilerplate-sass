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
 * @copyright  Copyright (c) ${YEAR} Studio Raz (http://studioraz.co.il/)
 * @license    http://studioraz.co.il/eula.html
 */

/**
 * Newsletter subscribe controller
 *
 * @category    Boilerplate
 * @package     Boilerplate_Mage
 * @author      Studio Raz Team <dev@studioraz.co.il>
 */

require_once 'Mage/Newsletter/controllers/SubscriberController.php';

class Boilerplate_Mage_Newsletter_SubscriberController extends Mage_Newsletter_SubscriberController {

	public function newAction() {
		if ( ! $this->getRequest()->isAjax() ) {
			parent::newAction();
		}

		$customerSession = Mage::getSingleton( 'customer/session' );
		$email           = (string) $this->getRequest()->getPost( 'email' );

		try {

			$respond = new Varien_Object();

			if ( ! Zend_Validate::is( $email, 'EmailAddress' ) ) {
				Mage::throwException( $this->__( 'Please enter a valid email address.' ) );
			}

			if ( Mage::getStoreConfig( Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG ) != 1 &&
			     ! $customerSession->isLoggedIn()
			) {

				$respond->setStatus( 'failed' );
				$respond->setMessage( $this->__( 'Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper( 'customer' )->getRegisterUrl() ) );
			}


			$ownerId = Mage::getModel( 'newsletter/subscriber' )->loadByEmail( $email )->getId();


			if ( $ownerId !== null && $ownerId != $customerSession->getId() ) {
				$respond->setStatus( 'failed' );
				$respond->setMessage( $this->__( 'This email address is already assigned to another user.' ) );
			} else {
				$ownerId = Mage::getModel( 'customer/customer' )
				               ->setWebsiteId( Mage::app()->getStore()->getWebsiteId() )
				               ->loadByEmail( $email )
				               ->getId();

				if ( $ownerId !== null && $ownerId != $customerSession->getId() ) {
					$respond->setStatus( 'failed' );
					$respond->setMessage( $this->__( 'This email address is already assigned to another user.' ) );
				} else {

					$status = Mage::getModel( 'newsletter/subscriber' )->subscribe( $email );

					if ( $status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE ) {

						$respond->setStatus( 'success' );
						$respond->setMessage( $this->__( 'Confirmation request has been sent.' ) );

					} else {
						$respond->setStatus( 'success' );
						$respond->setMessage( $this->__( 'Thank you for your subscription.' ) );
					}

				}

			}


		} catch ( Mage_Core_Exception $e ) {
			$respond->setStatus( 'failed' );
			$respond->setMessage( $this->__( 'There was a problem with the subscription: %s', $e->getMessage() ) );
		} catch ( Exception $e ) {
			$respond->setStatus( 'failed' );
			$respond->setMessage( $this->__( 'There was a problem with the subscription: %s' ) );
		}
		$this->getResponse()->setHeader('Content-type', 'application/json');
		$this->getResponse()->setBody( Mage::helper( 'core' )->jsonEncode( $respond ) );
	}
}