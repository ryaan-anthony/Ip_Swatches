<?php

/**
 * Class Ip_Swatches_Model_Product_Option_Type_Swatch
 * called from Mage_Catalog_Model_Product_Option::groupFactory
 */
class Ip_Swatches_Model_Product_Option_Type_Swatch extends Mage_Catalog_Model_Product_Option_Type_Default
{


    /**
     * Flag to indicate that custom option has own customized output (blocks, native html etc.)
     *
     * @return boolean
     */
    public function isCustomizedView()
    {
        return true;
    }

    /**
     * Return option html
     *
     * @param array $optionInfo
     * @return string
     */
    public function getCustomizedView($optionInfo)
    {
        $_value = Mage::getModel('catalog/product_option_value')->load($optionInfo['value']);
        $swatch_option = Mage::getModel('swatches/input_option')->load($_value->getSwatchOption());
        if($swatch_option && $swatch_option->getOptionId()){
            $url = Mage::getBaseUrl('media').$swatch_option->getOptionImage();
            return "<img src='{$url}' width='20' height='20' />";
        }
        return isset($optionInfo['value']) ? $optionInfo['value'] : $optionInfo;
    }

}