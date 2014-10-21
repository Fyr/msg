<div class="span8 offset2">
<?
    $id = $this->request->data('Faq.id');
    $title = ($id) ? __('Edit question') : __('Create question');
    echo $this->element('admin_title', compact('title'));
    echo $this->PHForm->create('Faq');
    echo $this->element('admin_content');
    echo $this->PHForm->input('question');
?>
<?
    
    echo $this->PHForm->editor('answer');
    // echo $this->PHForm->input('answer', array('fullwidth' => true));
    /*
    $aTabs = array(
        'Question' => $this->element('admin_edit_Contractor'),
        'Details' => $this->PHForm->editor('details', array('fullwidth' => true))
    );
    */
	// echo $this->element('admin_tabs', compact('aTabs'));
    echo $this->element('admin_content_end');
    echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
    echo $this->PHForm->end();
?>
</div>
