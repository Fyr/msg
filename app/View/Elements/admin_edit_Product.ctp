<?
	echo $this->PHForm->hidden('id');
	echo $this->PHForm->input('product_type_id', array('options' => $aProductTypeOptions));
	echo $this->PHForm->input('serial');
