<?php

class Boilerplate_BootstrapNavigation_Block_Page_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{
    /**
     * {@inheritDoc}
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '')
    {
        $html = parent::getHtml($outermostClass, $childrenWrapClass);

        if (Mage::getStoreConfig('catalog/navigation/top_in_dropdown')) {
            $html = $this->_addDropdownLink($html);
        }

        $html = $this->_addToggle($html);
        $html = $this->_addCaret($html);
        $html = $this->_addDropdown($html);

        return $html;
    }

	/**
	 * Recursively generates top menu html from data that is specified in $menuTree
	 *
	 * @param Varien_Data_Tree_Node $menuTree
	 * @param string $childrenWrapClass
	 * @return string
	 * @deprecated since 1.8.2.0 use child block catalog.topnav.renderer instead
	 */
	protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
	{
		$html = '';

		$children = $menuTree->getChildren();
		$parentLevel = $menuTree->getLevel();
		$childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

		$counter = 1;
		$childrenCount = $children->count();

		$parentPositionClass = $menuTree->getPositionClass();
		$itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

		foreach ($children as $child) {

			$child->setLevel($childLevel);
			$child->setIsFirst($counter == 1);
			$child->setIsLast($counter == $childrenCount);
			$child->setPositionClass($itemPositionClassPrefix . $counter);

			$outermostClassCode = '';
			$outermostClass = $menuTree->getOutermostClass();

			if ($childLevel == 0 && $outermostClass) {
				$outermostClassCode = ' class="' . $outermostClass . '" ';
				$child->setClass($outermostClass);
			}

			$html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
			$html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>'
			         . $this->escapeHtml($child->getName()) . '</span></a>';

			if ($child->hasChildren()) {
				if (!empty($childrenWrapClass)) {
					$html .= '<div class="' . $childrenWrapClass . '">';
				}
				$cmsBlock = null;
				if ($childLevel == 0 && $cmsId = $child->getCmsBlock()) {
					$cmsBlock = $this->getLayout()->createBlock('cms/block')
					                 ->setBlockId($cmsId)
					                 ->toHtml();
				}


				$html .= '<ul class="level' . $childLevel . '">';
				$html .= $this->_getHtml($child, $childrenWrapClass);
				$html .= '</ul>';

				if (!is_null($cmsBlock)){
					$html .='<div class="menu-cms-block">'.$cmsBlock.'</div>';
				}


				if (!empty($childrenWrapClass)) {
					$html .= '</div>';
				}
			}
			$html .= '</li>';

			$counter++;
		}

		return $html;
	}

    /**
     * {@inheritDoc}
     */
    protected function _getMenuItemClasses(Varien_Data_Tree_Node $item)
    {
        return parent::_getMenuItemClasses($item);
    }

    /**
     * Takes a html string and appends a modified top level link inside the dropdown.
     *
     * @param  string  $html
     * @return string
     */
    protected function _addDropdownLink($html)
    {
        return preg_replace_callback('/<li\s+class="level0.*?parent.*?<a\s+href="(.*?)".*?>.*?<span>(.*?)<\/span>.*?<ul\s+class="level0.*?>/', array($this, '_addDropdownLinkCallback'), $html);
    }

    /**
     * Callback executed to add a modified top level link inside the dropdown. We're
     * doing this just to ensure we're supporting PHP 5.2 as Magento does.
     *
     * @param  array  $matches
     * @return string
     */
    protected function _addDropdownLinkCallback(array $matches)
    {
        $prefix = Mage::getStoreConfig('catalog/navigation/top_in_dropdown_prefix');
        $suffix = Mage::getStoreConfig('catalog/navigation/top_in_dropdown_suffix');

        $html = '<li class="level1 level-top-in-dropdown"><a href="'.$matches[1].'"><span>'.$prefix.' '.$matches[2].' '.$suffix.'</span></a>';
        $html .= '<li class="divider"></li>';

        return $matches[0].$html;
    }

    /**
     * Adds a dropdown HTML5 attribute to top level links.
     *
     * @param  string  $html
     * @return string
     */
    protected function _addToggle($html)
    {
        return preg_replace('/(<li\s+class="level0.*?parent.*?<a.*?)(>)/', '$1 data-toggle="dropdown"$2', $html);
    }

    /**
     * Adds a caret HTML symbol to top level links.
     *
     * @param  string  $html
     */
    protected function _addCaret($html)
    {
        return preg_replace('/(<li\s+class="level0.*?parent.*?)(<\/a>)/', '$1 $2', $html);
    }

    /**
     * Adds a CSS class to top level dropdowns.
     *
     * @param  string  $html
     * @return string
     */
    protected function _addDropdown($html)
    {
        return $html;
    }
}
