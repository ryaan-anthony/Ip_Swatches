<?php

class Ip_Swatches_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Option extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('swatches/product/edit/options/option.phtml');
        $this->setSwatchGroup(Ip_Swatches_Model_Product_Option::OPTION_GROUP_SWATCH);
    }

    /**
     * Retrieve html templates for different types of product custom options
     *
     * @return string
     */
    public function getTemplatesHtml()
    {
        $canEditPrice = $this->getCanEditPrice();
        $canReadPrice = $this->getCanReadPrice();
        $this->getChild('swatch_option_type')
            ->setCanReadPrice($canReadPrice)
            ->setCanEditPrice($canEditPrice);

        $templates = parent::getTemplatesHtml() . "\n" .
            $this->getChildHtml('swatch_option_type');

        return $templates;
    }

    public function addSwatchTemplates()
    {
        $swatches = Mage::getModel('swatches/input')->getCollection();
        if($swatches->count()){
            foreach($swatches as $swatch){
                echo "
                    case 'swatch_{$swatch->getSwatchId()}':
                ";
            }
            echo "
                template = OptionTemplateSwatch;
                group = '{$this->getSwatchGroup()}';
                break;
            ";
        }
    }

    public function addSwatchOptionTemplates()
    {
        $swatches = Mage::getModel('swatches/input')->getCollection();
        if($swatches->count()){
            foreach($swatches as $swatch){
                /* @var $swatch Ip_Swatches_Model_Input */
                echo "
                    case '{$this->getSwatchGroup()}_{$swatch->getSwatchId()}':
                        data.optionValues.each(function(value) {
                            swatchOptionType.add(value, '');
                        });
                        break;
                ";
            }
        }
    }

    public function getDefaultValues()
    {
        $values = array();
        $swatches = Mage::getModel('swatches/input')->getCollection();
        if($swatches->count()){
            $swatchCount = 0;
            foreach($swatches as $swatch){
                $swatchCount++;
                $optionCount = 0;
                $value = array();
                $value['id'] = $swatchCount;
                $value['item_count'] = $swatchCount;
                $value['option_id'] = $swatchCount;
                $value['title'] = $this->htmlEscape($swatch->getSwatchName());
                $value['type'] = $this->getSwatchGroup();
                $value['is_require'] = 1;
                $value['sort_order'] = 0;
                $value['can_edit_price'] = true;
                foreach($swatch->getSortedOptions() as $_value){
                    $optionCount++;
                    $value['optionValues'][] = array(
                        'image_url' => $_value->getOptionImage(),
                        'swatch_option' => $_value->getOptionId(),
                        'item_count' => $optionCount,
                        'option_id' => $swatchCount,
                        'option_type_id' => -1,
                        'title' => $this->htmlEscape($_value->getOptionName()),
                        'price' => $_value->getOptionPrice(),
                        'price_type' => 'fixed',
                        'sku' => $this->htmlEscape($_value->getOptionSku()),
                        'sort_order' => $_value->getOptionPosition(),
                    );
                }
                $values[$this->getSwatchGroup().'_'.$swatch->getSwatchId()] = $value;
            }
        }
        return json_encode($values);
    }


    public function getOptionValues()
    {
        $optionsArr = array_reverse($this->getProduct()->getOptions(), true);
        if (!$this->_values) {
            $showPrice = $this->getCanReadPrice();
            $values = array();
            $scope = (int) Mage::app()->getStore()->getConfig(Mage_Core_Model_Store::XML_PATH_PRICE_SCOPE);
            foreach ($optionsArr as $option) {
                /* @var $option Mage_Catalog_Model_Product_Option */
                $this->setItemCount($option->getOptionId());
                $value = array();
                $value['id'] = $option->getOptionId();
                $value['item_count'] = $this->getItemCount();
                $value['option_id'] = $option->getOptionId();
                $value['title'] = $this->htmlEscape($option->getTitle());
                $value['type'] = $option->getType();
                $value['is_require'] = $option->getIsRequire();
                $value['sort_order'] = $option->getSortOrder();
                $value['can_edit_price'] = $this->getCanEditPrice();
                if ($this->getProduct()->getStoreId() != '0') {
                    $value['checkboxScopeTitle'] = $this->getCheckboxScopeHtml($option->getOptionId(), 'title',
                        is_null($option->getStoreTitle()));
                    $value['scopeTitleDisabled'] = is_null($option->getStoreTitle())?'disabled':null;
                }
                // add swatch group option values same as 'select'
                if($option->getGroupByType() == $this->getSwatchGroup() ||
                    $option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
                    $i = 0;
                    $itemCount = 0;
                    foreach ($option->getValues() as $_value) {
                        /* @var $_value Mage_Catalog_Model_Product_Option_Value */
                        $value['optionValues'][$i] = array(
                            'swatch_option' => $_value->getSwatchOption(),//added swatch option_id
                            'image_url' => $_value->getSwatchOption() ? $this->getImageUrl($_value->getSwatchOption()) : '',
                            'item_count' => max($itemCount, $_value->getOptionTypeId()),
                            'option_id' => $_value->getOptionId(),
                            'option_type_id' => $_value->getOptionTypeId(),
                            'title' => $this->htmlEscape($_value->getTitle()),
                            'price' => ($showPrice)
                                    ? $this->getPriceValue($_value->getPrice(), $_value->getPriceType()) : '',
                            'price_type' => ($showPrice) ? $_value->getPriceType() : 0,
                            'sku' => $this->htmlEscape($_value->getSku()),
                            'sort_order' => $_value->getSortOrder(),
                        );

                        if ($this->getProduct()->getStoreId() != '0') {
                            $value['optionValues'][$i]['checkboxScopeTitle'] = $this->getCheckboxScopeHtml(
                                $_value->getOptionId(), 'title', is_null($_value->getStoreTitle()),
                                $_value->getOptionTypeId());
                            $value['optionValues'][$i]['scopeTitleDisabled'] = is_null($_value->getStoreTitle())
                                ? 'disabled' : null;
                            if ($scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {
                                $value['optionValues'][$i]['checkboxScopePrice'] = $this->getCheckboxScopeHtml(
                                    $_value->getOptionId(), 'price', is_null($_value->getstorePrice()),
                                    $_value->getOptionTypeId());
                                $value['optionValues'][$i]['scopePriceDisabled'] = is_null($_value->getStorePrice())
                                    ? 'disabled' : null;
                            }
                        }
                        $i++;
                    }
                } else {
                    $value['price'] = ($showPrice)
                        ? $this->getPriceValue($option->getPrice(), $option->getPriceType()) : '';
                    $value['price_type'] = $option->getPriceType();
                    $value['sku'] = $this->htmlEscape($option->getSku());
                    $value['max_characters'] = $option->getMaxCharacters();
                    $value['file_extension'] = $option->getFileExtension();
                    $value['image_size_x'] = $option->getImageSizeX();
                    $value['image_size_y'] = $option->getImageSizeY();
                    if ($this->getProduct()->getStoreId() != '0' &&
                        $scope == Mage_Core_Model_Store::PRICE_SCOPE_WEBSITE) {
                        $value['checkboxScopePrice'] = $this->getCheckboxScopeHtml($option->getOptionId(),
                            'price', is_null($option->getStorePrice()));
                        $value['scopePriceDisabled'] = is_null($option->getStorePrice())?'disabled':null;
                    }
                }
                $values[] = new Varien_Object($value);
            }
            $this->_values = $values;
        }

        return $this->_values;
    }

    public function getImageUrl($option_id)
    {
        $option = Mage::getModel('swatches/input_option')->load($option_id);
        if($option && $option->getOptionId()){
            return $option->getOptionImage();
        }
        return '';
    }
}