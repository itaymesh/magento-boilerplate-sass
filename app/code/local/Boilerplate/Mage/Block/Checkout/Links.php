<?php class Boilerplate_Mage_Block_Checkout_Links extends Mage_Checkout_Block_Links {
	/**
	 * Add shopping cart link to parent block
	 *
	 * @return Mage_Checkout_Block_Links
	 */
	public function addCartLink()
	{
		$parentBlock = $this->getParentBlock();
		if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
			$count = $this->getSummaryQty() ? $this->getSummaryQty()
				: $this->helper('checkout/cart')->getSummaryCount();
			$text = '';
			if ($count > 0) {
				$text = $this->__('%s', $count);
			}

			$parentBlock->removeLinkByUrl($this->getUrl('checkout/cart'));
			$parentBlock->addLink($text, 'checkout/cart', $this->__('My Cart'), true, array(), 50, null, 'class="top-link-cart"');
		}
		return $this;
	}
}