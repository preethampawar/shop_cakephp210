<?php
$title_for_layout = 'Invoice Details';
echo $this->set('title_for_layout', $title_for_layout);
?>
<h1><?php echo $title_for_layout;?></h1>

<?php if($invoiceInfo) { ?>
	<table class='table'>
		<thead>
			<tr>
				<th>Invoice</th>
				<th>Date</th>
				<th>DD.No.</th>
				<th>Supplier</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $invoiceInfo['Invoice']['name'];?></td>
				<td><?php echo date('d-m-Y', strtotime($invoiceInfo['Invoice']['invoice_date']));?></td>
				<td><?php echo $invoiceInfo['Invoice']['dd_no'];?></td>
				<td><?php echo $invoiceInfo['Invoice']['supplier_name'];?></td>
			</tr>
		</tbody>
	</table>


	<h2>Products</h2>
	<?php 
	if($invoiceProducts) { 
	?>
	<table class='table'>
		<thead>
			<tr>
				<th>S.No</th>
				<th>Category Name</th>
				<th>Product Name</th>
				<th>No. of Boxes</th>
				<th>Unit Box Price</th>
				<th>Total Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			$totalBoxes = 0;
			$totalAmount = 0;
			$totalNoOfUnits = 0;
			foreach($invoiceProducts as $row) {
				$i++;
				$totalBoxes+=$row['Purchase']['box_qty'];
				$totalAmount+=$row['Purchase']['total_amount'];
				$totalUnits = $row['Purchase']['total_units'];
				$noOfBoxes = floor($row['Purchase']['total_units']/$row['Purchase']['units_in_box']);
				$unitInBox = $row['Purchase']['units_in_box'];
				$noOfUnits = ($totalUnits)-($noOfBoxes*$unitInBox);
				$totalNoOfUnits+=$noOfUnits;
				
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Purchase']['category_name'];?></td>
				<td><?php echo $row['Purchase']['product_name'];?></td>
				<td style="text-align:center;"><?php echo $row['Purchase']['box_qty'];
					if($noOfUnits){
						echo "&nbsp;($noOfUnits)";
					}
				?></td>
				<td><?php echo $row['Purchase']['box_buying_price'];?></td>
				<td style="text-align:right;"><?php echo $row['Purchase']['total_amount'];?></td>
				</td>
			</tr>
			<?php
			}
			?>
			<tfoot style="color:red; font-weight:bold;">
				<tr>
					<td colspan='3'></td>
					<td style="text-align:center;"><?php echo $totalBoxes;
						if($totalNoOfUnits){
							echo "&nbsp;($totalNoOfUnits)";
						}
					?> Boxes</td>
					<td style="text-align:right;" colspan='2'>Total Amount: <?php echo $totalAmount;?></td>
				</tr>	
			</tfoot>
		</tbody>
	</table>
	<?php 
	} 
	else {
		echo "No products found";
	}
}
?>
