<?php

class Ip_Swatches_Adminhtml_SwatchController extends Mage_Adminhtml_Controller_Action
{
    protected $posttype_data = 'swatch_data';
    protected $posttype_model = 'swatches/input';
    protected $posttype_id = 'swatch_id';
    protected $posttype_name = 'swatch_name';
    protected $posttype_options = 'swatch_options';
    protected $posttype_grid = 'swatches/adminhtml_input_grid';

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $model = Mage::getModel($this->posttype_model);
        if($id = $this->getRequest()->getParam($this->posttype_id, null)){
            $model->load($id);
        }
        Mage::register($this->posttype_data, $model);
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            $model = Mage::getSingleton($this->posttype_model);
            if($id = $this->getRequest()->getParam($this->posttype_id, null)){
                $model->setData($this->posttype_id, $id);
            }
            try {
                $model->setData(
                    $this->posttype_name,
                    $this->getRequest()->getParam($this->posttype_name, null)
                );
                $swatch_options = array();
                $post_options = $this->getRequest()->getParam($this->posttype_options, array());
                foreach($post_options as $key => $option){
                    $image_id = $this->posttype_options.'_'.$key;
                    // set id if already exists
                    if(intval($key) > 0){
                        $option['id'] = $key;
                    } elseif(isset($_FILES[$image_id]['name'])) {
                        $uploader = new Varien_File_Uploader($image_id);
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        $path = $this->check_path(Mage::getBaseDir('media').DS.'catalog'.DS.'swatches');
                        $image_name = $uploader->getCorrectFileName($_FILES[$image_id]['name']);
                        $uploader->save($path, $image_name);
                        $image_url = 'catalog/swatches/'.$image_name;
                        $this->resize_image($path.DS.$image_name);
                        $option['image'] = $image_url;
                    }
                    $swatch_options[] = $option;
                }
                $model->setData($this->posttype_options, $swatch_options);
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Your changes have been saved.'));
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array($this->posttype_id => $model->getData($this->posttype_id)));
                } else {
                    $this->_redirect('*/*/');
                }
                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            Mage::getSingleton('adminhtml/session')->setData($this->posttype_data, $postData);
            $this->_redirectReferer();
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam($this->posttype_id) > 0 ) {
            try {
                $model = Mage::getSingleton($this->posttype_model);

                $model->setData($this->posttype_id, $this->getRequest()->getParam($this->posttype_id))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array($this->posttype_id => $this->getRequest()->getParam($this->posttype_id)));
            }
        }
        $this->_redirect('*/*/');
    }


    public function exportCsvAction()
    {
        $content = $this->getLayout()->createBlock()->getCsv($this->posttype_grid);
        $this->_prepareDownloadResponse($this->posttype_data.'_export.csv', $content);
    }

    /**
     * @param $path
     */
    protected function resize_image($path)
    {
        $image = new Varien_Image($path);
        $image->constrainOnly(true);
        $image->keepAspectRatio(true);
        $image->keepFrame(false);
        $image->keepTransparency(true);
        $image->setImageBackgroundColor(false);
        $image->backgroundColor(false);
        $image->quality(100);
        $image->setWatermarkImageOpacity(0);
        $image->resize(120, 120);
        $image->save($path);
    }

    /**
     * @param $path
     * @return mixed
     */
    protected function check_path($path)
    {
        $io = new Varien_Io_File();
        if (!$io->isWriteable($path) && !$io->mkdir($path, 0777, true)) {
            Mage::throwException(Mage::helper('adminhtml')->__("Cannot create writeable directory '%s'", $path));
        }
        return $path;
    }


}