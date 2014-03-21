<?php

class Ip_Swatches_Model_System_Config_Source_Product_Options_Type
{
    const PRODUCT_OPTIONS_GROUPS_PATH = 'global/catalog/product/options/custom/groups';

    public function toOptionArray()
    {
        $groups = array(
            array('value' => '', 'label' => Mage::helper('adminhtml')->__('-- Please select --'))
        );

        $helper = Mage::helper('catalog');

        foreach (Mage::getConfig()->getNode(self::PRODUCT_OPTIONS_GROUPS_PATH)->children() as $group) {
            $types = array();
            if($group->getName() == Ip_Swatches_Model_Product_Option::OPTION_GROUP_SWATCH){
                $swatches = Mage::getModel('swatches/input')->getCollection();
                foreach($swatches as $swatch){
                    $types[] = array(
                        'label' => $helper->jsQuoteEscape($swatch->getSwatchName()),
                        'value' => 'swatch_'.$swatch->getSwatchId()
                    );
                }
            } else {
                $typesPath = self::PRODUCT_OPTIONS_GROUPS_PATH . '/' . $group->getName() . '/types';
                foreach (Mage::getConfig()->getNode($typesPath)->children() as $type) {
                    $labelPath = self::PRODUCT_OPTIONS_GROUPS_PATH . '/' . $group->getName() . '/types/' . $type->getName()
                        . '/label';
                    $types[] = array(
                        'label' => $helper->__((string) Mage::getConfig()->getNode($labelPath)),
                        'value' => $type->getName()
                    );
                }

            }

            $labelPath = self::PRODUCT_OPTIONS_GROUPS_PATH . '/' . $group->getName() . '/label';


            $groups[] = array(
                'label' => $helper->__((string) Mage::getConfig()->getNode($labelPath)),
                'value' => $types
            );
        }

        return $groups;
    }
}
