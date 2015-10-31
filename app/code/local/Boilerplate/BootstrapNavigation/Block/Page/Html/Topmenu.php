<?php

class Boilerplate_BootstrapNavigation_Block_Page_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{
    /**
     * {@inheritDoc}
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '')
    {

        $html = parent::getHtml($outermostClass, $childrenWrapClass);


        $html = $this->_addToggle($html);
        $html = $this->_addCaret($html);
        $html = $this->_addDropdown($html);

        return $html;
    }


    /**
     * Adds a dropdown HTML5 attribute to top level links.
     *
     * @param  string  $html
     * @return string
     */
    protected function _addToggle($html)
    {
        return preg_replace('/(<li\s+class="level0.*?parent.*?<a.*?)(>)/', '$1 data-hover="dropdown" $2', $html);
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
