<?
	echo $this->PHForm->hidden('id');
	echo $this->PHForm->input('contractor_id', array('options' => $aContractorOptions));
	echo $this->PHForm->input('period', array('class' => 'input-small'));
	echo $this->PHForm->input('status', array('label' => false, 'multiple' => 'checkbox', 'options' => array('paid' => __('Paid'), 'shipped' => __('Shipped')), 'class' => 'checkbox inline'));
