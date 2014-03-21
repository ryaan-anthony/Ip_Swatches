<?php

class Ip_Swatches_Block_Adminhtml_Element_Gallery extends Varien_Data_Form_Element_Gallery
{
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    public function getElementHtml()
    {
        $name = $this->getName();
        $html = '<table id="gallery" class="gallery" border="0" cellspacing="3" cellpadding="0">';
        $html .= '
            <thead id="gallery_thead" class="gallery">
                <tr class="gallery">
                    <td class="gallery" valign="middle" align="left" width="200"><strong>Image</strong></td>
                    <td class="gallery" valign="middle" align="left" width="350"><strong>Default Name</strong></td>
                    <td class="gallery" valign="middle" align="left" width="170"><strong>Default Price</strong></td>
                    <td class="gallery" valign="middle" align="left" width="170"><strong>Default Sku</strong></td>
                    <td class="gallery" valign="middle" align="left" width="100"><strong>Sort Order</strong></td>
                    <td class="gallery" valign="middle" align="left"><strong>Delete</strong></td>
                </tr>
            </thead>
            <tfoot class="gallery">
                <tr class="gallery">
                    <td class="gallery" valign="middle" align="left" colspan="5">'.$this->getAddButton().'</td>
                </tr>
            </tfoot>
        ';
        $html .= '<tbody class="gallery">';
        if($options = $this->getValue()) {
            foreach($options as $option) {
                $input_name = "{$name}[{$option['option_id']}]";
                $html .= '
                    <tr>
                        <td>
                            <img src="'.Mage::getBaseUrl('media').$option['option_image'].'" height="50" />
                            <input type="hidden" name="'.$input_name.'[image]" value="'.$option['option_image'].'" />
                        </td>
                        <td>
                            <input type="hidden" name="'.$input_name.'[id]" value="'.$option['option_id'].'" />
                            <input type="input" name="'.$input_name.'[name]" value="'.$option['option_name'].'" size="50"/>
                        </td>
                        <td>
                            <input type="input" name="'.$input_name.'[price]" value="'.number_format($option['option_price'],2,'.','').'"  size="20"/>
                        </td>
                        <td>
                            <input type="input" name="'.$input_name.'[sku]" value="'.$option['option_sku'].'"  />
                        </td>
                        <td>
                            <input type="input" name="'.$input_name.'[position]" value="'.$option['option_position'].'"  size="3"/>
                        </td>
                        <td>
                            <input type="checkbox" value="Delete" onclick="this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );">
                        </td>
                    </tr>
                ';
            }
        }
        $html .= '</tbody></table>';
        $html .= <<<EndSCRIPT

        <script language="javascript">
        id = 0;

        function addNewImg(){

            document.getElementById("gallery_thead").style.visibility="visible";

            id--;

		    // Image label input
		    var new_name = document.createElement( 'input' );
		    new_name.type = 'text';
		    new_name.name = '{$name}['+id+'][name]';
		    new_name.size = '50';
		    new_name.value = '';

		    // Image file input
		    var new_image = document.createElement( 'input' );
		    new_image.type = 'file';
		    new_image.name = '{$name}_'+id;
		    new_image.size = '1';

		    // Sku input
		    var new_sku = document.createElement( 'input' );
		    new_sku.type = 'text';
		    new_sku.name = '{$name}['+id+'][sku]';
		    new_sku.size = '20';
		    new_sku.value = '';

		    // Price input
		    var new_price = document.createElement( 'input' );
		    new_price.type = 'text';
		    new_price.name = '{$name}['+id+'][price]';
		    new_price.size = '20';
		    new_price.value = '0.00';

		    // Sort order input
		    var new_position = document.createElement( 'input' );
		    new_position.type = 'text';
		    new_position.name = '{$name}['+id+'][position]';
		    new_position.size = '3';
		    new_position.value = '0';

		    // Delete button
		    var new_delete = document.createElement( 'input' );
		    new_delete.type = 'checkbox';
		    new_delete.value = 'Delete';

            table = document.getElementById( "gallery" );

            // no of rows in the table:
            noOfRows = table.rows.length;

            // insert row at pre-last:
            var x=table.insertRow(noOfRows-1);

            // insert new row
            x.insertCell(0).appendChild( new_image );
            x.insertCell(1).appendChild( new_name );
            x.insertCell(2).appendChild( new_price );
            x.insertCell(3).appendChild( new_sku );
            x.insertCell(4).appendChild( new_position );
            x.insertCell(5).appendChild( new_delete );

		    // Delete function
		    new_delete.onclick= function(){

                this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );

			    // Appease Safari
			    //    without it Safari wants to reload the browser window
			    //    which nixes your already queued uploads
			    return false;
		    };

	    }
        </script>

EndSCRIPT;
        $html.= $this->getAfterElementHtml();
        return $html;
    }


    protected function getAddButton()
    {
        $layout = $this->getForm()->getParent()->getLayout();
        return $layout->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'label'     => 'Add New Image',
                    'onclick'   => 'addNewImg()',
                    'class'     => 'add'))
            ->toHtml();
    }

}
