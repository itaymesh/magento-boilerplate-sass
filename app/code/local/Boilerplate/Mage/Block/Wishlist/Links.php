<?php class Boilerplate_Mage_Block_Wishlist_Links extends Mage_Wishlist_Block_Links {
	/**
	 * @return string
	 */
	protected function _toHtml()
	{
		if ($this->helper('wishlist')->isAllow()) {
			$text = $this->_createLabel($this->_getItemCount());
			$this->_label = $text;
			$this->_title = $this->__('My Wishlist');
			$this->setAParams('class="top-links-wishlist"');
			$this->_url = $this->getUrl('wishlist');
			return parent::_toHtml();
		}
		return '';
	}

	/**
	 * Define label, title and url for wishlist link
	 *
	 * @deprecated after 1.6.2.0
	 */
	public function initLinkProperties()
	{
		$text = $this->_createLabel($this->_getItemCount());
		$this->_label = $text;
		$this->_title = $this->__('My Wishlist');
		$this->_url = $this->getUrl('wishlist');
	}

	/**
	 * Create button label based on wishlist item quantity
	 *
	 * @param int $count
	 * @return string
	 */
	protected function _createLabel($count)
	{
		if ($count > 0) {
			return $this->__('%d', $count );
		}
	}
}