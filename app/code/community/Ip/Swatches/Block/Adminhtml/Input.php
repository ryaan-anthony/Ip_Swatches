<?php

class Ip_Swatches_Block_Adminhtml_Input extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_addButtonLabel = 'Add New';

    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_input';
        $this->_blockGroup = 'swatches';
        $this->_headerText = Mage::helper('adminhtml')->__('Color Swatches');
    }

}