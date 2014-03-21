<?php

class Ip_Swatches_Block_Adminhtml_Input_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $posttype_id = 'swatch_id';
    protected $posttype_name = 'swatch_name';
    protected $posttype_grid_id = 'swatch_grid';
    protected $posttype_model = 'swatches/input';


    public function __construct()
    {
        parent::__construct();
        $this->setId($this->posttype_grid_id);
        $this->setDefaultSort($this->posttype_id);
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel($this->posttype_model)->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addExportType('*/*/exportCsv', Mage::helper('adminhtml')->__('CSV'));

        $this->addColumn($this->posttype_id, array(
            'header'    => Mage::helper('adminhtml')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => $this->posttype_id,
        ));

        $this->addColumn($this->posttype_name, array(
            'header'    => Mage::helper('adminhtml')->__('Swatch Name'),
            'align'     =>'left',
            'index'     => $this->posttype_name,
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array($this->posttype_id => $row->getData($this->posttype_id)));
    }
}