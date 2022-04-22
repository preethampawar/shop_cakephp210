<?php
$title_for_layout = 'Bank Report';
echo $this->set('title_for_layout', $title_for_layout);
?>
<h1><?php echo $title_for_layout;?></h1>
<h4>From <?php echo date('d M Y', strtotime($fromDate));?> to <?php echo date('d M Y', strtotime($toDate));?></h4>

<?php
if($result) {	
?>
	<table class='table'>
		<thead>
			<tr>
				<th>Sl.No</th>
				<th>Date</th>
				<th>Description</th>
				<th>Deposit</th>
				<th>Withdrawal</th>
				<th>Balance</th>
			</tr>
		</thead>
		<tbody>		
			<?php
			if($showPrevBalance) {
			?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><b>Previous Balance</b></td>					
					<td></td>
					<td></td>
					
					<td><b><?php echo number_format($prev_balance, 2, '.', '');?></b></td>
				</tr>	
			<?php
			}
			?>	
	
			<?php
			$i=0;
			$totalDeposits=0;
			$totalWithdrawals=0;
			$balance = $prev_balance;
			if($result) {		
				foreach($result as $row) {
					$i++;
					$totalDeposits+=($row['Bank']['payment_type'] == 'credit') ? $row['Bank']['amount'] : 0;
					$totalWithdrawals+=($row['Bank']['payment_type'] == 'debit') ? $row['Bank']['amount'] : 0;
					
					if($row['Bank']['payment_type'] == 'credit') {
						$balance = $balance+$row['Bank']['amount'];
					}
					if($row['Bank']['payment_type'] == 'debit') {
						$balance = $balance-$row['Bank']['amount'];
					}
				?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo date('d-m-Y', strtotime($row['Bank']['payment_date']));?></td>
						<td><?php echo $row['Bank']['title'];?></td>					
						<td><?php echo ($row['Bank']['payment_type'] == 'credit') ? $row['Bank']['amount'] : ' ';?></td>
						<td><?php echo ($row['Bank']['payment_type'] == 'debit') ? $row['Bank']['amount'] : ' ';?></td>
						<td><?php echo number_format($balance, 2, '.', '');?></td>
					</tr>			
				<?php
				}
			}
			?>	
		</tbody>
		<tfoot>
			<tr style="font-weight:bold;">
				<th colspan='3' style="text-align:right">Total: </th>
				<th><?php echo (($paymentType == 'credit') or ($paymentType == '')) ? number_format($totalDeposits, 2, '.', '') : null;?></th>
				<th><?php echo (($paymentType == 'debit') or ($paymentType == '')) ? number_format($totalWithdrawals, 2, '.', '') : null;?></th>
				<th><?php echo number_format($balance, 2, '.', '');?></th>
			</tr>
			<tr>
				<th colspan='3' style="text-align:right"></th>
				<th>Deposit</th>
				<th>Withdrawal</th>
				<th>Balance</th>
			</tr>
		</tfoot>
	</table>
<?php
}
else {
	echo 'No records found';
}
?>