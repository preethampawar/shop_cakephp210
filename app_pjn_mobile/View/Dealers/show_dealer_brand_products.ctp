<?php $this->start('dealers_report_menu'); ?>
<?php echo $this->element('dealers_menu'); ?>
<?php $this->end(); ?>

	<h1>Dealer Brand Products List</h1><br>

<?php if ($result) { ?>
	<div class="row">
		<div class="col-lg-9">

			<table class='table search-table' style="width:100%">
				<thead>
				<tr>
					<th style="width:10px;">S.No</th>
					<th style="width:150px;">Dealer Name</th>
					<th style="width:150px;">Brand Name</th>
					<th style="width:150px;">Product Name</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				foreach ($result as $row) {
					$i++;

					$dealer_id = $row['d']['id'];
					$dealer_name = $row['d']['name'];
					$dealer_created_date = date('d-m-Y', strtotime($row['d']['created']));

					$brand_id = $row['b']['id'];
					$brand_name = $row['b']['name'];

					$product_id = $row['p']['id'];
					$product_name = $row['p']['name'];
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $dealer_name; ?></td>
						<td><?php echo $brand_name; ?></td>
						<td><?php echo $product_name; ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>

		</div>
	</div>
<?php } else { ?>
	<p>No dealer found.</p>
<?php } ?>
