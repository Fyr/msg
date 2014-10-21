<div id="addProductForm" class="inline">
	<div class="inline">
		<label for="ProductTypeId">Product Type</label>
	</div>
	<select id="ProductTypeId" name="data[OrderProduct][product_type_id]" autocomplete="off">
<?
	foreach($aProductTypeOptions as $val => $title) {
?>
	<option value="<?=$val?>"><?=$title?></option>
<?
	}
?>
	</select>
	
	<div class="inline" style="margin-left: 10px;">
		<label class="inline" for="OrderSerial">Serial</label>
	</div>
	<input type="text" id="OrderSerial" class="input-small" name="data[OrderProduct][serial]" autocomplete="off">
	
	<input type="button" id="addProductBtn" class="btn" value="<?=__('Add')?>" style="margin-left: 10px;">
</div>
<div id="productList">
	<?=$this->element('admin_list_OrderProducts')?>
</div>
<script type="text/javascript">
function deleteOrderProduct(productID) {
	$('#productList').load('<?=$this->Html->url(array('controller' => 'AdminAjax', 'action' => 'delOrderProduct', $this->request->data('Order.id')))?>/' + productID);
}
$(document).ready(function(){
	$('#addProductBtn').click(function(){
		$.post(
			'<?=$this->Html->url(array('controller' => 'AdminAjax', 'action' => 'addOrderProduct'))?>', 
			$('#OrderEditForm').serialize(), 
			function(response){
				$('#productList').html(response);
			}
		);
	});
});
</script>