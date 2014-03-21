<?php

class Ip_Swatches_Block_Sales_Email_Items_Order_Default extends Mage_Sales_Block_Order_Email_Items_Order_Default
{

    /**
     * Add support for getFormatedOptionValue to enable custom_view
     * @see Ip_Swatches_Model_Product_Option_Type_Swatch::getCustomizedView
     * @return array
     */
    public function getItemOptions()
    {
        $result = array();
        $options = parent::getItemOptions();
        foreach($options as $option){
            $value = $this->getFormatedOptionValue($option);
            $option['print_value'] = $value['value'];
            $option['value'] = $value['value'];
            $result[] = $option;
        }
        return $result;
    }

    /**
     * Accept option value and return its formatted view
     * Copied from Mage_Sales_Block_Order_Item_Renderer_Default
     * @param mixed $optionValue
     * Method works well with these $optionValue format:
     *      1. String
     *      2. Indexed array e.g. array(val1, val2, ...)
     *      3. Associative array, containing additional option info, including option value, e.g.
     *          array
     *          (
     *              [label] => ...,
     *              [value] => ...,
     *              [print_value] => ...,
     *              [option_id] => ...,
     *              [option_type] => ...,
     *              [custom_view] =>...,
     *          )
     *
     * @return array
     */
    public function getFormatedOptionValue($optionValue)
    {
        $optionInfo = array();

        // define input data format
        if (is_array($optionValue)) {
            if (isset($optionValue['option_id'])) {
                $optionInfo = $optionValue;
                if (isset($optionInfo['value'])) {
                    $optionValue = $optionInfo['value'];
                }
            } elseif (isset($optionValue['value'])) {
                $optionValue = $optionValue['value'];
            }
        }

        // render customized option view
        if (isset($optionInfo['custom_view']) && $optionInfo['custom_view']) {
            $_default = array('value' => $optionValue);
            if (isset($optionInfo['option_type'])) {
                try {
                    $group = Mage::getModel('catalog/product_option')->groupFactory($optionInfo['option_type']);
                    return array('value' => $group->getCustomizedView($optionInfo));
                } catch (Exception $e) {
                    return $_default;
                }
            }
            return $_default;
        }

        // truncate standard view
        $result = array();
        if (is_array($optionValue)) {
            $_truncatedValue = implode("\n", $optionValue);
            $_truncatedValue = nl2br($_truncatedValue);
            return array('value' => $_truncatedValue);
        } else {
            $_truncatedValue = Mage::helper('core/string')->truncate($optionValue, 55, '');
            $_truncatedValue = nl2br($_truncatedValue);
        }

        $result = array('value' => $_truncatedValue);

        if (Mage::helper('core/string')->strlen($optionValue) > 55) {
            $result['value'] = $result['value'] . ' <a href="#" class="dots" onclick="return false">...</a>';
            $optionValue = nl2br($optionValue);
            $result = array_merge($result, array('full_view' => $optionValue));
        }

        return $result;
    }

}