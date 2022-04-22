<?php
$title_for_layout = 'Dealer Brand Purchase Report';
echo $this->set('title_for_layout', $title_for_layout);
?>
<h1><?php echo $title_for_layout;?></h1>
<h4>From <?php echo date('d M Y', strtotime($fromDate));?> to <?php echo date('d M Y', strtotime($toDate));?></h4>

<div class="">
	<div class="row">
		<div class="col-lg-8">
<?php
if($show_brand_purchase_report) {
	if($dealer_brand_result) {
	?>
		<h2>Brand Purchases</h2>
		<table class='table'>
			<thead>
				<tr>
					<th>Sl.No</th>
					<th>Dealer Name</th>
					<th>Brand Name</th>				
					<th style="text-align:center;">Boxes</th>
					<th style="text-align:center;">Units</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i=0;		
			$brand_name = null;
			$total_boxes = 0;
			$total_units = 0;
			
			foreach($dealer_brand_result as $dealer_name => $row) {
				$i++;
				$brand_name = null;			
				
				if(!empty($row)) {
					foreach($row as $brand_name => $row2) {
						$total_boxes = 0;
						$total_units = 0;
						foreach($row2['boxes'] as $brand_boxes) {
							$total_boxes += $brand_boxes;
						}
						foreach($row2['units'] as $units) {
							$total_units += $units;
						}
						
						$dealer_brand_result[$dealer_name][$brand_name]['total_boxes'] = $total_boxes;
						$dealer_brand_result[$dealer_name][$brand_name]['total_units'] = $total_units;
						?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $dealer_name;?></td>
							<td><?php echo $brand_name;?></td>
							<td style="text-align:center;"><?php echo $total_boxes;?></td>
							<td style="text-align:center;"><?php echo $total_units;?></td>
						</tr>
						<?php
						$i++;
					}
				} else {
				?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo $dealer_name;?></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				<?php
				}
			}
			?>
			</tbody>		
		</table>
	<?php
	}
}

if($show_product_purchase_report) {
	if($detailed_result) {
	?>
		<br>
		<h2>Product Purchases</h2>
		<table class='table'>
			<thead>
				<tr>
					<th>Sl.No</th>
					<th>Dealer Name</th>
					<th>Brand Name</th>
					<th>Product Name</th>
					<th>Units Per Box</th>
					<th>Total Purchases</th>
					<th style="text-align:center;">Boxes</th>
					<th style="text-align:center;">Units</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i=0;		
			foreach($detailed_result as $row) {
				$i++;
				
				$dealer_name = $row['dealer_name'];
				$brand_name = ($row['brand_name']) ? $row['brand_name'] : '-';
				$product_name = ($row['product_name']) ? $row['product_name'] : '-';
				$total_units =  $row['total_units'];
				$units_per_box = $row['units_per_box'];
				$total_boxes = $row['total_boxes'];
				$balance_units = $row['balance_units'];			
			?>
				<tr>
					<td><?php echo $i;?></td>
					<td><?php echo $dealer_name;?></td>
					<td><?php echo $brand_name;?></td>
					<td><?php echo $product_name;?> </td>
					<td><?php echo ($units_per_box) ? $units_per_box : '-';?></td>
					<td><?php echo ($total_units>0) ? $total_units.' units' : '-';?></td>
					<td style="text-align:center; font-weight: bold;"><?php echo ($total_boxes>0) ? $total_boxes : '-';?></td>
					<td style="text-align:center; font-weight: bold;"><?php echo ($balance_units>0) ? $balance_units : '-';?></td>
				</tr>			
			<?php
			}
			?>
			</tbody>		
		</table>
	<?php
	} else {
		echo 'No records found';
	}
}
?>
		</div>
	</div>
</div>