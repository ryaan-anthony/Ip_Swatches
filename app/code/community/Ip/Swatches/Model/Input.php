<?php

class Ip_Swatches_Model_Input extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('swatches/input');
    }

    public function getSortedOptions()
    {
        return Mage::getModel('swatches/input_option')
            ->getCollection()
            ->addFieldToFilter('swatch_id', $this->getSwatchId())
            ->addOrder('option_position', 'asc');
    }
}