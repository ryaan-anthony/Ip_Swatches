<?php

class Ip_Swatches_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Swatch extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Abstract
{

    protected $posttype_id = 'swatch_id';

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('swatches/product/edit/options/type/swatch.phtml');
    }

    public function getSwatches()
    {
        return Mage::getModel('swatches/input')->getCollection();
    }

    /**
     * @param Ip_Swatches_Model_Input $swatch
     * @return string
     */
    public function renderSwatch()
    {
        $output = $this->getLayout()
            ->createBlock('core/template')
            ->setTemplate('swatches/product/edit/options/type/swatch/options.phtml')
            ->toHtml();
        return json_encode($output);
    }

    public function renderSwatchContainer()
    {
        $output = $this->getLayout()
            ->createBlock('core/template')
            ->setTemplate('swatches/product/edit/options/type/swatch/container.phtml')
            ->toHtml();
        return json_encode($output);
    }

}