<?php

class Ip_Swatches_Block_Adminhtml_Input_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected $posttype_data = 'swatch_data';
    protected $posttype_model = 'swatches/input';
    protected $posttype_id = 'swatch_id';
    protected $posttype_form = 'swatch_form';

    protected function _prepareForm()
    {
        $data = Mage::registry($this->posttype_data);

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array($this->posttype_id => $this->getRequest()->getParam($this->posttype_id))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset($this->posttype_form, array(
            'legend' =>Mage::helper('adminhtml')->__('Swatches')
        ));
        $fieldset->addField('swatch_name', 'text', array(
            'label'     => Mage::helper('adminhtml')->__('Swatch Name'),
            'name'      => 'swatch_name',
        ));
        $fieldset->addType('swatch','Ip_Swatches_Block_Adminhtml_Element_Gallery');
        $fieldset->addField('swatch_options', 'swatch', array(
            'label'     => Mage::helper('adminhtml')->__('Swatch Options'),
            'name'      => 'swatch_options',
        ));


        $form->setValues($data);

        return parent::_prepareForm();
    }

}