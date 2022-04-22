<nav aria-label="breadcrumb" class="mb-4">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/admin/categories/">Categories</a></li>
		<li class="breadcrumb-item"><a href="/admin/categories/showProducts/<?= $categoryInfo['Category']['id'] ?>"><?php echo ucwords($categoryInfo['Category']['name']); ?></a></li>
		<li class="breadcrumb-item active" aria-current="page">Rearrange</li>
	</ol>
</nav>

<div class="text-end">
	<a href="/admin/categories/showProducts/<?= $categoryInfo['Category']['id'] ?>" class="btn btn-sm btn-outline-secondary">&laquo; Back</a>
</div>

<h1>Rearrange Products</h1>

<?php
	if (empty($categoryProducts)) {
		echo 'No product found.';
		return null;
	}
?>

<?php echo $this->Form->create(); ?>
<input type="hidden" name="data[sortinfo]" id="sortInfo">

<button type="submit" class="sortSaveButton btn btn-sm btn-primary mt-4 d-none">Save Changes</button>

<table class="table-bordered small table-hover mt-4" id="productsTable">

	<tr>
		<th>Product</th>
		<th>Preference</th>
	</tr>

	<?php
	foreach($categoryProducts as $row) {
		$productName = $row['Product']['name'];
		$sort = $row['CategoryProduct']['sort'];
		$categoryProductId = $row['CategoryProduct']['id'];

		if (empty($row['Product']['id'])) {
			continue;
		}
		?>
		<tr style="cursor: move;">
			<td class="small">
				<span class="text-primary"><?= $productName ?></span>
			</td>
			<td data-catproduct-id="<?= $categoryProductId ?>" data-catproduct-sort="<?= $sort ?>"><?= $sort ?></td>
		</tr>
		<?php
	}
	?>

</table>

<button type="submit" class="sortSaveButton btn btn-sm btn-primary mt-4 d-none">Save Changes</button>

<?php
echo $this->Form->end();
?>
<br><br>

<script src="/vendor/jquery-ui-1.13.0.custom/jquery-ui.min.js"></script>
<style type="text/css">
	table th, table td
	{
		width: 200px;
		padding: 5px;
		border: 1px solid #ccc;
	}
	.selected
	{
		background-color: lightcyan;
	}
</style>
<script>
	var sortData = [];
	var sortString = '';
	$(function () {
		$("#productsTable").sortable({
			items: 'tr:not(tr:first-child)',
			cursor: 'pointer',
			axis: 'y',
			dropOnEmpty: false,
			start: function (e, ui) {
				ui.item.addClass("selected");
			},
			stop: function (e, ui) {
				ui.item.removeClass("selected");
				sortData = [];
				$(this).find("tr").each(function (index) {
					if (index > 0) {
						let tableLastTd = $(this).find("td").eq(1);
						tableLastTd.data('catproduct-sort', index);
						let data = {
							catproductId: tableLastTd.data('catproduct-id'),
							sort: index
						}
						sortData.push(data);

						//console.log(tableLastTd.data('product-id'), tableLastTd.data('product-sort'));
						$(this).find("td").eq(1).html(index);
					}
				});

				if (sortData.length > 0) {
					$('.sortSaveButton').removeClass('d-none');

					sortString = JSON.stringify(sortData);
					$('#sortInfo').val(sortString);
				}
				console.log(sortData);
			}
		});
		$("#productsTable").disableSelection();
	});
</script>
