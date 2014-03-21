<?php

/**
 * Class Ip_Swatches_Block_Product_View_Options_Type_Swatch_Options
 * @method Mage_Catalog_Model_Product_Option getOption()
 * @method Ip_Swatches_Model_Input getSwatch()
 * @method Mage_Catalog_Model_Product_Option_Value getValue()
 * @method Mage_Catalog_Model_Product getProduct()
 */
class Ip_Swatches_Block_Product_View_Options_Type_Swatch_Options extends Mage_Core_Block_Template
{

    /**
     * @return string
     */
    public function getIsChecked()
    {
        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $this->getOption()->getId());
        $htmlValue = $this->getValue()->getOptionTypeId();
        return $configValue == $htmlValue ? 'checked' : '';
    }

    /**
     * @return decimal
     */
    public function getPrice()
    {
        $store = $this->getProduct()->getStore();
        return $this->helper('core')->currencyByStore($this->getValue()->getPrice(true), $store, false);
    }

    /**
     * @return string
     */
    public function getElementId()
    {
        return "options_{$this->getOption()->getId()}_{$this->getCount()}";
    }

    public function getOptionTitle()
    {
        $_value = $this->getValue();
        return $_value->getTitle() . ($_value->getPrice() > 0 ? "+$".$this->getPrice() : '');
    }

    public function getSwatchImage()
    {
        $_value = $this->getValue();
        $option_id = $_value->getSwatchOption();
        $swatch_option = Mage::getModel('swatches/input_option')->load($option_id);
        if($swatch_option && $swatch_option->getOptionId()){
            return $this->getUrl('media').$swatch_option->getOptionImage();
        }
        return "";
    }

}