<?
	if ($errMsg) {
?>
<div class="err-msg"><br/><?=$errMsg?></div>
<?
	}
	echo $this->element('admin_list_OrderProducts');
?>