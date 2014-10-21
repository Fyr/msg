<div class="span8 offset2">
<?
    $id = $this->request->data('Product.id');
    $title = ($id) ? __('Edit product') : __('Create product');
    echo $this->element('admin_title', compact('title'));
    
    echo $this->PHForm->create('Product');
    $aTabs = array(
        'General' => $this->element('admin_edit_Product'),
    );
    
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
    echo $this->PHForm->end();
?>
</div>
