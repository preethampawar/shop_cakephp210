<h1>Transactions  - <?php echo date('j F, Y');?></h1>
<div class="clear"></div>
<br><br>
<h2>Sales Account</h2>
<?php
$scredit = 0;
$pdebit = 0;

$totalPendingPaymentsReceivable = 0;
$totalPendingPaymentsPayable = 0;

if(!empty($sales)) {
?>
	<table style="width:950px;">
		<thead>
			<tr>
				<th width='40'>Sl.No.</th>		
				<th>Particular</th>				
				<th width='150'>Debit</th>
				<th width='150'>Credit</th>						
				<th width='180'>Payments to be received</th>			
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			$ppr = 0; //pending payment receivable	
			$ppp = 0; //pending payment payable	
			foreach($sales as $row) {
				$i++;
				$scredit+=$row['Ledger']['amount'];
				$totalPendingPaymentsReceivable+=($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0;
				$ppr+=($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Ledger']['category_name'].': '.$row['Ledger']['particular'];?></td>
				<td class="debit" style="text-align:center;">-</td>
				<td class="credit"><?php echo $this->Number->currency($row['Ledger']['amount'], $CUR);?> </td>
				<td class="credit"><?php echo ($row['Ledger']['pending_payment']) ? $this->Number->currency($row['Ledger']['pending_amount'], $CUR) : '-';?> </td>
			</tr>			
			<?php
			}
			?>
			<tr>
				<td colspan='2' style="text-align:right; font-weight:bold;">Total</td>
				<td class="debit" style="text-align:center;">-</td>
				<td class="credit"><b><?php echo $this->Number->currency($scredit, $CUR);?></b></td>
				<td class="credit"><b>Receivable: <?php echo $this->Number->currency($ppr, $CUR);?></b></td>
			</tr>
			
		</tbody>
	</table>
	<h3><b>Sales Account Balance: <?php echo $this->Number->currency($scredit, $CUR);?></b></h3>
	<br>	
<?php	
}
else {
	echo 'No Sale Records <br>';
}
?>

<br><br>
<h2>Purchases Account</h2>
<?php
if(!empty($purchases)) {
?>
	<table style="width:950px;">
		<thead>
			<tr>
				<th width='40'>Sl.No.</th>		
				<th>Particular</th>				
				<th width='150'>Debit</th>
				<th width='150'>Credit</th>
				<th width='150'>Payments to be made</th>				
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			$pdebit = 0;	
			$ppp = 0;	
			foreach($purchases as $row) {
				$i++;
				$pdebit+=$row['Ledger']['amount'];
				$totalPendingPaymentsPayable+=($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0;
				$ppp+=($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Ledger']['category_name'].': '.$row['Ledger']['particular'];?></td>
				<td class="debit"><?php echo $this->Number->currency($row['Ledger']['amount'], $CUR);?> </td>
				<td class="credit" style="text-align:center;">-</td>
				<td class="debit"><?php echo ($row['Ledger']['pending_payment']) ? $this->Number->currency($row['Ledger']['amount'], $CUR) : '-';?> </td>
			</tr>			
			<?php
			}
			?>
			<tr>
				<td colspan='2' style="text-align:right; font-weight:bold;">Total</td>
				<td class="debit"><b><?php echo $this->Number->currency($pdebit, $CUR);?></b></td>
				<td class="credit" style="text-align:center;">-</td>
				<td class="debit"><b>Payable: <?php echo $this->Number->currency($ppp, $CUR);?></b></td>
			</tr>
			
		</tbody>
	</table>
	<h3 class=""><b>Purchases Account Balance: <?php echo $this->Number->currency($pdebit, $CUR);?></b></h3>
	<br>	
<?php	
}
else {
	echo 'No Purchase Records <br>';
}
?>


<br><br>
<h2>Cash Account</h2>
<?php
$credit = 0;
$debit = 0;
$balance = 0;
$ppr = 0;
$ppp = 0;
if(!empty($cash)) {
?>
	<table style="width:950px;">
		<thead>
			<tr>
				<th width='40'>Sl.No.</th>		
				<th>Particular</th>				
				<th width='150'>Debit</th>
				<th width='150'>Credit</th>		
				<th width='150'>Pending Payments</th>	
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			
			foreach($cash as $row) {
				$i++;
				$credit+=($row['Ledger']['transaction_type'] == 'credit') ? $row['Ledger']['amount'] : 0;
				$debit+=($row['Ledger']['transaction_type'] == 'debit') ? $row['Ledger']['amount'] : 0;
				$cbalance = $credit-$debit;
				
				$totalPendingPaymentsReceivable+=($row['Ledger']['transaction_type'] == 'credit') ? (($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0) : 0;
				$totalPendingPaymentsPayable+=($row['Ledger']['transaction_type'] == 'debit') ? (($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0) : 0;
				
				$ppr+=($row['Ledger']['transaction_type'] == 'credit') ? (($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0) : 0;
				$ppp+=($row['Ledger']['transaction_type'] == 'debit') ? (($row['Ledger']['pending_payment']) ? $row['Ledger']['pending_amount'] : 0) : 0;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['Ledger']['category_name'].': '.$row['Ledger']['particular'];?></td>
				<td class="debit"><?php echo ($row['Ledger']['transaction_type'] == 'debit') ? $this->Number->currency($row['Ledger']['amount'], $CUR) : '-';?> </td>
				<td class="credit"><?php echo ($row['Ledger']['transaction_type'] == 'credit') ? $this->Number->currency($row['Ledger']['amount'], $CUR) : '-';?> </td>
				<?php
				if($row['Ledger']['transaction_type'] == 'credit') {
				?>
					<td class="credit">
						<?php echo ($row['Ledger']['pending_payment']) ? $this->Number->currency($row['Ledger']['pending_amount'], $CUR) : '-';?> 
					</td>
				<?php
				}
				else {
				?>
					<td class="debit">
						<?php echo ($row['Ledger']['pending_payment']) ? $this->Number->currency($row['Ledger']['pending_amount'], $CUR) : '-';?> 
					</td>
				
				<?php
				}
				?>
				
			</tr>			
			<?php
			}
			?>
			<tr>
				<td colspan='2' style="text-align:right; font-weight:bold;">Total</td>
				<td class="debit"><b><?php echo $this->Number->currency($debit, $CUR);?></b></td>
				<td class="credit"><b><?php echo $this->Number->currency($credit, $CUR);?></b></td>
				<td>
					<span class="credit"><b>Receivable: <?php echo $this->Number->currency($ppr, $CUR);?></b></span> <br>
					<span class="debit"><b>Payable: <?php echo $this->Number->currency($ppp, $CUR);?></b></span>
				</td>
			</tr>
			
		</tbody>
	</table>
	<h3><b>Cash Account Balance: <?php echo $this->Number->currency(($cbalance), $CUR, array('negative'=>'(-) '));?></b></h3>
	<br>	
<?php	
}
else {
	echo 'No Cash Records <br><br>';
}
?>
<hr>
<br>
<br>

<h3>Profit (or) Loss - <?php echo date('j F, Y');?></h3>

<table style="width:500px;">
	<thead>
		<tr>
			<th >Account Type</th>							
			<th width='150'>Debit</th>
			<th width='150'>Credit</th>						
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Sales</td>
			<td>&nbsp;</td>
			<td class="credit"><?php echo $this->Number->currency(($scredit), $CUR );?></td>
		</tr>
		<tr>
			<td>Purchases</td>
			<td class="debit"><?php echo $this->Number->currency(($pdebit), $CUR );?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Cash</td>				
			<td class="debit"><?php echo $this->Number->currency(($debit), $CUR );?></td>				
			<td class="credit"><?php echo $this->Number->currency(($credit), $CUR );?></td>		
		</tr>
		<tr>
			<td><b>Total</b></td>
			<td class='debit'><?php echo $this->Number->currency(($pdebit+$debit), $CUR );?></td>
			<td class='credit'><?php echo $this->Number->currency(($scredit+$credit), $CUR );?></td>
		</tr>	
	</tbody>
</table>
<?php 
$balance = ($scredit+$credit)-($pdebit+$debit);
?>
<h4 class="<?php echo ($balance >0) ? 'credit' : 'debit';?>">
	<?php echo ($balance >0) ? 'At Profit, ' : (($balance <0) ? 'At Loss, ' : '');?>
	Balance: <?php echo $this->Number->currency($balance, $CUR, array('negative'=>'(-) '));?>
</h4>
<br>

<hr>
<br>
<br>
<h3>Pending Payments - <?php echo date('j F, Y');?></h3>
<h4 class="credit">Payments to be received: <b><?php echo $this->Number->currency($totalPendingPaymentsReceivable, $CUR, array('negative'=>'(-) '));?></b></h4>
<h4 class="debit">Payments to be made: <b><?php echo $this->Number->currency($totalPendingPaymentsPayable, $CUR, array('negative'=>'(-) '));?></b></h4>
