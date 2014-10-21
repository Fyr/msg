<div class="span8 offset2">
<?
    $id = $this->request->data('ProductType.id');
    $title = ($id) ? __('Edit product type') : __('Create product type');
    echo $this->element('admin_title', compact('title'));
    
    echo $this->PHForm->create('ProductType');
    $aTabs = array(
        'General' => $this->element('admin_edit_ProductType'),
		'Text' => $this->PHForm->editor('descr', array('fullwidth' => true))
    );
    
    if ($id) {
        $aTabs['Media'] = $this->element('Media.edit', array('object_type' => 'ProductType', 'object_id' => $id));
    }
    
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
    echo $this->PHForm->end();
?>
</div>
