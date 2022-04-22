<div>
	<h1>Default Price List - <?php echo $this->Html->link('Download', '/bevarages_default_price_list.xlsx');?></h1>
	<br>
	<?php 
	//echo $this->Html->link('Products Price list - Download Excel', '/bevarages_default_price_list.xlsx').'<br><br>';
	
	if(!empty($products)) {
	?>	
	<table class="table table-striped table-condensed search-table">
		<thead>
			<tr>
				<th>#</th>
				<th>Brand No</th>
				<th>Size Code</th>
				<th>Pack Type</th>
				<th>Product Name</th>
				<th>Issue Price</th>
				<th>Special Margin</th>
				<th>MRP</th>
				<th>Type</th>
			</tr>
		</thead>
		<tbody>
		<?php	
		foreach($products as $row) {
			$sno = $row['DefaultPriceList']['id'];
			$brand_number = $row['DefaultPriceList']['brand_number'];
			$size_code = $row['DefaultPriceList']['size_code'];
			$pack_type = $row['DefaultPriceList']['pack_type'];
			$product_name = $row['DefaultPriceList']['product_name'];
			$issue_price = $row['DefaultPriceList']['issue_price'];
			$special_margin = $row['DefaultPriceList']['special_margin'];
			$mrp = $row['DefaultPriceList']['mrp'];
			$type = $row['DefaultPriceList']['type'];
		?>
			<tr>
				<td><?php echo $sno;?></td>
				<td><?php echo $brand_number;?></td>
				<td><?php echo $size_code;?></td>
				<td><?php echo $pack_type;?></td>
				<td><?php echo $product_name;?></td>
				<td><?php echo $issue_price;?></td>
				<td><?php echo $special_margin;?></td>
				<td><?php echo $mrp;?></td>
				<td><?php echo $type;?></td>
			</tr>
		<?php	
		}
		?>
		</tbody>	
	</table>
	<?php
	}
	?>
</div>