<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 11/3/14
 * Time: 11:08 AM
 */ 
class Boilerplate_Mage_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation {
    public function removeLink($name) {
        if (isset($this->_links[$name])) {
            unset($this->_links[$name]);
        }
    }
}