<?php

class Ip_Swatches_Block_Product_View_Options extends Mage_Catalog_Block_Product_View_Options
{

    /**
     * Get json representation of
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = array();

        foreach ($this->getOptions() as $option) {
            /* @var $option Mage_Catalog_Model_Product_Option */
            $priceValue = 0;
            if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT ||
                $option->getGroupByType() == Ip_Swatches_Model_Product_Option::OPTION_GROUP_SWATCH) {
                $_tmpPriceValues = array();
                foreach ($option->getValues() as $value) {
                    /* @var $value Mage_Catalog_Model_Product_Option_Value */
                    $id = $value->getId();
                    $_tmpPriceValues[$id] = $this->_getPriceConfiguration($value);
                }
                $priceValue = $_tmpPriceValues;
            } else {
                $priceValue = $this->_getPriceConfiguration($option);
            }
            $config[$option->getId()] = $priceValue;
        }

        return Mage::helper('core')->jsonEncode($config);
    }


}