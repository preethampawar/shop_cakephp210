<?php $this->start('dealers_report_menu'); ?>
<?php echo $this->element('dealers_menu'); ?>
<?php $this->end(); ?>
<?php echo $this->element('dealers_top_nav_menu'); ?>

<h1>Show All Brand Products</h1>
<br>

<div class="well well-xs">
	<?php
	echo $this->Form->create();
	echo $this->Form->input('brand_id', ['type' => 'select', 'label' => 'Select Brand', 'required' => true, 'options' => $brands, 'class' => 'autoSuggest form-control', 'multiple' => 'multiple']);
	echo $this->Form->submit('Search');
	echo $this->Form->end();
	?>
</div>
<br>
<div id="AddCategoryDiv" class="">

	<?php
	if (!empty($products)) {
		?>
		<table class="table table-striped table-bordered" style="width:700px;">
			<thead>
			<th style="width:60px;">S.No</th>
			<th>Brand Name</th>
			<th>Product Name</th>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach ($products as $row) {
				$i++;
				$product_id = $row['Product']['id'];
				$product_name = $row['Product']['name'];
				$brand_id = $row['Product']['brand_id'];
				$brand_name = (isset($row['Brand']['id'])) ? $row['Brand']['name'] : null;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $brand_name; ?></td>
					<td><?php echo $product_name; ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	} else {
		echo 'No Products found';
	}
	?>


</div>
