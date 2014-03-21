<?php

class Ip_Swatches_Model_Resource_Input extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $posttype_id = 'swatch_id';

    protected function _construct()
    {
        $this->_init('swatches/input', $this->posttype_id);
    }

    /**
     * Perform actions after object load
     *
     * @param Varien_Object $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $options = Mage::getModel('swatches/input_option')
            ->getCollection()
            ->addFieldToFilter($this->posttype_id, $object->getData($this->posttype_id))
            ->addOrder('option_position', 'asc');
        $object->setSwatchOptions($options);
        return parent::_afterLoad($object);
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract|void
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $preserve_ids = array();
        foreach($object->getSwatchOptions() as $option){
            $input_option = Mage::getModel('swatches/input_option');
            if(isset($option['id'])){
                $input_option->setOptionId($option['id']);
            }
            $input_option->setOptionName($option['name']);
            $input_option->setOptionPrice($option['price']);
            $input_option->setOptionImage($option['image']);
            $input_option->setOptionPosition($option['position']);
            $input_option->setSwatchId($object->getData($this->posttype_id));
            $input_option->save();
            $preserve_ids[] = $input_option->getOptionId();
        }
        $collection = Mage::getModel('swatches/input_option')->getCollection()
            ->addFieldToFilter($this->posttype_id, $object->getData($this->posttype_id))
            ->addFieldToFilter('option_id', array('nin' => $preserve_ids));
        $collection->walk('delete');
    }

}