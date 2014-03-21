<?php

class Ip_Swatches_Model_Product_Option extends Mage_Catalog_Model_Product_Option
{
    const OPTION_GROUP_SWATCH   = 'swatch';

    /**
     * Get group name of option by given option type
     *
     * @param string $type
     * @return string
     */ 
    public function getGroupByType($type = null)
    {
        if (is_null($type)) {
            $type = $this->getType();
        }
        $swatch_id = str_replace(self::OPTION_GROUP_SWATCH.'_','',$type);
        $swatch = Mage::getModel('swatches/input')->load($swatch_id);
        if($swatch && $swatch->getSwatchId()){
            return self::OPTION_GROUP_SWATCH;
        }
        return parent::getGroupByType($type);
    }


    /**
     * Save options.
     *
     * @return Mage_Catalog_Model_Product_Option
     */
    public function saveOptions()
    {
        foreach ($this->getOptions() as $option) {
            $this->setData($option)
                ->setData('product_id', $this->getProduct()->getId())
                ->setData('store_id', $this->getProduct()->getStoreId());

            if ($this->getData('option_id') == '0') {
                $this->unsetData('option_id');
            } else {
                $this->setId($this->getData('option_id'));
            }
            $isEdit = (bool)$this->getId()? true:false;

            if ($this->getData('is_delete') == '1') {
                if ($isEdit) {
                    $this->getValueInstance()->deleteValue($this->getId());
                    $this->deletePrices($this->getId());
                    $this->deleteTitles($this->getId());
                    $this->delete();
                }
            } else {
                if ($this->getData('previous_type') != '') {
                    $previousType = $this->getData('previous_type');

                    /**
                     * if previous option has different group from one is came now
                     * need to remove all data of previous group
                     */
                    if ($this->getGroupByType($previousType) != $this->getGroupByType($this->getData('type'))) {

                        switch ($this->getGroupByType($previousType)) {
                            case self::OPTION_GROUP_SELECT:
                                if($this->getGroupByType($this->getData('type')) != self::OPTION_GROUP_SWATCH){
                                    $this->unsetData('values');
                                    if ($isEdit) {
                                        $this->getValueInstance()->deleteValue($this->getId());
                                    }
                                }
                                break;
                            case self::OPTION_GROUP_FILE:
                                $this->setData('file_extension', '');
                                $this->setData('image_size_x', '0');
                                $this->setData('image_size_y', '0');
                                break;
                            case self::OPTION_GROUP_TEXT:
                                $this->setData('max_characters', '0');
                                break;
                            case self::OPTION_GROUP_DATE:
                                break;
                        }
                        if ($this->getGroupByType($this->getData('type')) == self::OPTION_GROUP_SELECT) {
                            $this->setData('sku', '');
                            $this->unsetData('price');
                            $this->unsetData('price_type');
                            if ($isEdit) {
                                $this->deletePrices($this->getId());
                            }
                        }
                    }
                }
                $this->save();
            }
        }//eof foreach()
        return $this;
    }
}