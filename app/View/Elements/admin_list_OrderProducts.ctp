<table class="grid table-bordered shadow">
<thead>
	<tr class="first table-gradient">
		<th class="nowrap"></th>
		<th data-grid_col="ProductType.title" class="nowrap"><a class="grid-unsortable" href="javascript:void(0)">Product type</a></th>
		<th data-grid_col="Product.serial" class="nowrap"><a class="grid-unsortable" href="javascript:void(0)">Serial</a></th>
	</tr>
</thead>
<tbody>
<?
	foreach($aProducts as $product) {
?>
	<tr class="grid-row">
		<td class="nowrap text-center">
			<a onclick="if (confirm('<?=__('Are you sure to delete this record?')?>')) { deleteOrderProduct(<?=$product['Product']['id']?>) } return false;" title="<?=__('Delete record')?>" class="icon-color icon-delete" href="javascript::void(0)"></a>
		</td>
		<td><?=$product['ProductType']['title']?></td>
		<td><?=$product['Product']['serial']?></td>
	</tr>
<?
	}
?>
	<tr class="grid-footer table-gradient" id="last-tr">
		<td class="nowrap" colspan="3">
			&nbsp;
		</td>
	</tr>
	</tbody>
</table>