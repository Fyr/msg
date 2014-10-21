<div class="span8 offset2">
<?
	$id = $this->request->data('Order.id');
	$title = ($id) ? __('Edit order') : __('Create order');
	echo $this->element('admin_title', compact('title'));
	
	echo $this->PHForm->create('Order');
	$aTabs = array(
		'General' => $this->element('admin_edit_Order'),
	);
	
	if ($id) {
		// $aTabs['Product Types'] = $this->element('admin_edit_OrderTypes');
		$aTabs['Products'] = $this->element('admin_edit_OrderProducts');
	}
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
	echo $this->PHForm->end();
?>
</div>
