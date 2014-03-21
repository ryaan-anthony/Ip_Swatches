<?php

class Ip_Swatches_Model_Input_Option extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('swatches/input_option');
    }

    public function loadByAttribute($attr, $value)
    {
        $id = $this->getResource()->loadByAttribute($attr, $value);
        $this->load($id);
        return $this;
    }

    public function getTitle($escape = true)
    {
        $option_title = $this->getOptionPrice() > 0 ?
            $this->getOptionName().' +'.Mage::helper('core')->currency($this->getOptionPrice(), true, false) :
            $this->getOptionName();
        if($escape){
            return Mage::helper('core')->escapeHtml($option_title);
        }
        return $option_title;
    }

    public function getImageUrl()
    {
        return Mage::getBaseUrl('media').$this->getOptionImage();
    }

}