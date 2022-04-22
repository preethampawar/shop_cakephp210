<?php $this->start('dealers_report_menu'); ?>
<?php echo $this->element('dealers_menu'); ?>
<?php $this->end(); ?>
<?php echo $this->element('dealers_top_nav_menu'); ?>

<h1>Add Brand Products</h1>
<br>
<div id="AddCategoryDiv" class="">
	<?php
	echo $this->Form->create();
	?>

	<?php
	if (!empty($products)) {
		?>
		<table class="table table-striped table-bordered" style="width:700px;">
			<thead>
			<th style="width:60px;">S.No</th>
			<th>Product Name</th>
			<th>Brand Name</th>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach ($products as $product_id => $product_name) {
				$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $product_name; ?></td>
					<td>
						<?php
						$selected_brand_id = $product_brand_list[$product_id];
						echo $this->Form->input('brand_id.' . $product_id, ['type' => 'select', 'label' => false, 'empty' => ' -- Select Brand -- ', 'required' => false, 'options' => $brands, 'selected' => $selected_brand_id, 'class' => 'autoSuggest']);
						?>
					</td>
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

	<?php
	echo $this->Form->submit('Update');
	echo $this->Form->end();
	?>
</div>
