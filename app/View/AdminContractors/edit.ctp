<div class="span8 offset2">
<?
	$id = $this->request->data('Contractor.id');
	$title = ($id) ? __('Edit contractor') : __('Create contractor');
	echo $this->element('admin_title', compact('title'));
	
	echo $this->PHForm->create('Contractor');
	$aTabs = array(
		'General' => $this->element('admin_edit_Contractor'),
		'Details' => $this->PHForm->editor('details', array('fullwidth' => true))
	);
	
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
	echo $this->PHForm->end();
?>
</div>
