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

class Boilerplate_Core_Helper_Url extends Mage_Core_Helper_Abstract {

    /**
     * get absolute url.
     * if argument passed is relative
     * the function will add store base url, otherwise it will return the original url
     * @param $url
     *
     * @return string
     */
	public function getAbsoluteUrl($url) {

        if ($this->isValidUrl($url)) {
            return $url;
        }

        return Mage::getUrl($url);
	}

    /**
     * check if string is valid URL
     * return true if valid otherwise false.
     *
     * @see https://api.drupal.org/api/drupal/includes%21common.inc/function/valid_url/7
     *
     * @param $url
     *
     * @return bool
     */
    public function isValidUrl($url) {
        return (bool) preg_match("
      /^                                                      # Start at the beginning of the text
      (?:ftp|https?|feed):\/\/                                # Look for ftp, http, https or feed schemes
      (?:                                                     # Userinfo (optional) which is typically
        (?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*      # a username or a username and password
        (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@          # combination
      )?
      (?:
        (?:[a-z0-9\-\.]|%[0-9a-f]{2})+                        # A domain name or a IPv4 address
        |(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\])         # or a well formed IPv6 address
      )
      (?::[0-9]+)?                                            # Server port number (optional)
      (?:[\/|\?]
        (?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})   # The path and query (optional)
      *)?
    $/xi", $url);
    }
}