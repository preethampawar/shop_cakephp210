<?php echo $this->element('message');?>

<!-- Invoice Info -->
<h2><?php echo $invoiceInfo['Invoice']['name'];?></h2>		
<?php
if(!empty($invoiceInfo['Data'])) {
?>	
	<table style="width:100%;" cellpadding='0' cellspacing='0' class="borderTable">
		<thead>
			<tr>
				<th style="width:40px;">Sl.No.</th>
				<th>Product</th>
				<th style="width:100px;">Quantity</th>
				<th style="width:100px;">Price/Case</th>
				<th style="width:120px;">Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k=0;
		$total_amount = 0;
		$total_qty = 0;
		foreach($invoiceInfo['Data'] as $row) {
			$k++;
		?>
			<tr id="row<?php echo $k;?>">
				<td style="text-align:center;"><?php echo $k;?></td>
				<td><?php echo $row['category_name'];?></td>
				<td>
					<?php 
						$total_qty+=$row['no_of_cases'];
						echo ($row['no_of_cases']) ? $row['no_of_cases'].' ('.$row['quantity'].' units)' : '-';
					?>
				</td>
				<td><?php echo ($row['price_per_case']) ? $row['price_per_case'] : '-';?></td>
				<td>
					<?php 
					if($row['transaction_type'] == 'debit') {
						$total_amount+=$row['total_amount'];					
					}
					if($row['transaction_type'] == 'credit') {
						$total_amount-=$row['total_amount'];
					}
					echo $row['total_amount'];
					?>
				</td>
				
			</tr>	
		<?php		
		}
		?>
		</tbody>
		<tfoot>			
			<tr>
				<td></td>
				<td style="text-align:right;">Total: </td>
				<td><?php echo $total_qty;?></td>
				<td></td>
				<td>	
					<?php echo number_format($total_amount, 2, '.', '');?>
				</td>				
			</tr>
		</tfoot>
	</table>	
	<?php
}
?>

