<?php

class Ip_Swatches_Block_Adminhtml_Input_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected $posttype_data = 'swatch_data';
    protected $posttype_name = 'swatch_name';
    protected $posttype_id = 'swatch_id';

    public function __construct()
    {
        //do this before parent constructor to enable delete button
        $this->_objectId = $this->posttype_id;

        parent::__construct();

        $this->_blockGroup = 'swatches';
        $this->_controller = 'adminhtml_input';
        $this->_mode = 'edit';
        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry($this->posttype_data) && Mage::registry($this->posttype_data)->getData($this->posttype_id)){
            return Mage::helper('adminhtml')->__("%s", $this->htmlEscape(Mage::registry($this->posttype_data)->getData($this->posttype_name)));
        } else {
            return Mage::helper('adminhtml')->__('New Swatch');
        }
    }

}