<?php

class Ip_Swatches_Model_Resource_Input_Option_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('swatches/input_option');
    }
}