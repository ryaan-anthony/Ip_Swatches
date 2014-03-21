<?php

class Ip_Swatches_Block_Product_View_Options_Type_Swatch extends Mage_Catalog_Block_Product_View_Options_Type_Select
{

    /**
     * Return html for control element
     *
     * @return string
     */
    public function getValuesHtml()
    {
        $_option = $this->getOption();
        $group = Ip_Swatches_Model_Product_Option::OPTION_GROUP_SWATCH;
        $swatch_id = str_replace($group.'_','',$_option->getType());
        $swatch = Mage::getModel('swatches/input')->load($swatch_id);
        if($swatch && $swatch->getSwatchId()){
            $swatchHtml = array();
            foreach ($_option->getValues() as $key => $_value) {
                $priceStr = $this->_formatPrice(array(
                    'is_percent'    => ($_value->getPriceType() == 'percent'),
                    'pricing_value' => $_value->getPrice(($_value->getPriceType() == 'percent'))
                ), false);
                $swatchHtml[] = $this->createHtml(array(
                    'count' => $key,
                    'price_str' => $priceStr,
                    'swatch' => $swatch,
                    'product' => $this->getProduct(),
                    'option' => $_option,
                    'value' => $_value,
                ));
            }
            return implode('',$swatchHtml);
        } else {
            $message = Mage::helper('core')->__('Some product options no longer exist and will not be displayed.');
            Mage::getSingleton('core/session')->addNotice($message);
        }
        return '';
    }


    /**
     * @param Array $data
     * @return string
     */
    protected function createHtml($data)
    {
        $options = $this->getLayout()->createBlock('swatches/product_view_options_type_swatch_options');
        $options->setData($data);
        $options->setTemplate('swatches/product/view/options/type/swatch/option.phtml');
        return $options->toHtml();
    }


}