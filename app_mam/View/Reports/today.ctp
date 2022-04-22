<?php
	$inventory = false;
	if($this->Session->check('UserCompany')) {	
		switch($this->Session->read('Company.business_type')) {
			case 'personal':			
				break;			
			case 'general':
				break;			
			case 'inventory':
				$inventory = true;
				break;		
			case 'wineshop':
				$inventory = true;
				break;			
			case 'finance':
				break;		
			case 'default':
				break;
		}	
	}
?>

<h1><?php echo $this->Session->read('Company.title');?>: Transactions - <?php echo date(' F, Y');?></h1>
<br>
<?php
if(!empty($results)) {	
	$transactions['totalIncome'] = 0;
	$transactions['totalExpenses'] = 0;
	$transactions['subTotalIncome'] = 0;
	$transactions['subTotalExpenses'] = 0;
	$transactions['totalSales'] = 0;
	$transactions['totalPurchases'] = 0;
	$transactions['totalCashCredit'] = 0;
	$transactions['totalCashDebit'] = 0;	
	$transactions['pendingIncome'] = 0;
	$transactions['pendingExpenses'] = 0;
	$transactions['pendingSales'] = 0;
	$transactions['pendingPurchases'] = 0;
	$transactions['pendingCashCredit'] = 0;
	$transactions['pendingCashDebit'] = 0;	
	if($this->Session->read('Company.business_type') != 'personal') {
?>
	
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th width='40'>Sl.No.</th>				
				<th width='80'>Date (d-m-y)</th>				
				<th width='80'>Acc. Book</th>				
				<th width='200'>Category</th>
				<th>Particular</th>	
				<?php echo ($inventory) ? "<th width='50'>Qty</th>" : null;?>				
				<th width='110'>Total Amount</th>
				<th width='220'>
					<div class="floatLeft" style="width:110px;">Paid (Dr.)</div>
					<div class="floatLeft">Received (Cr.)</div>
					<div class="clear"></div>			
				</th>			
				<th width='120'>Pending Payment</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			
			
			foreach($results as $row) {
				$i++;			
				
				if($row['Report']['transaction_type'] == 'debit') {					
					$transactions['totalExpenses'] = $transactions['totalExpenses']+$row['Report']['total_amount'];
					$transactions['subTotalExpenses'] = $transactions['subTotalExpenses']+$row['Report']['payment_amount'];
					$transactions['pendingExpenses'] = $transactions['pendingExpenses']+$row['Report']['pending_amount'];					
				}
				else {
					$transactions['totalIncome'] = $transactions['totalIncome']+$row['Report']['total_amount'];
					$transactions['subTotalIncome'] = $transactions['subTotalIncome']+$row['Report']['payment_amount'];
					$transactions['pendingIncome'] = $transactions['pendingIncome']+$row['Report']['pending_amount'];
				}
				
				// Calculate sales, purchases, cash transactions and pending payments
				if($row['Report']['business_type'] == 'cash') {
					if($row['Report']['transaction_type'] == 'credit') {
						$transactions['totalCashCredit'] = $transactions['totalCashCredit']+$row['Report']['total_amount'];
						$transactions['pendingCashCredit'] = $transactions['pendingCashCredit']+$row['Report']['pending_amount'];
					}
					if($row['Report']['transaction_type'] == 'debit') {
						$transactions['totalCashDebit'] = $transactions['totalCashDebit']+$row['Report']['total_amount'];
						$transactions['pendingCashDebit'] = $transactions['pendingCashDebit']+$row['Report']['pending_amount'];
					}
				}				
				if($row['Report']['business_type'] == 'sale') {
					$transactions['totalSales'] = $transactions['totalSales'] + $row['Report']['total_amount'];
					$transactions['pendingSales'] = $transactions['pendingSales']+$row['Report']['pending_amount'];
				}				
				if($row['Report']['business_type'] == 'purchase') {
					$transactions['totalPurchases'] = $transactions['totalPurchases'] + $row['Report']['total_amount'];
					$transactions['pendingPurchases'] = $transactions['pendingPurchases']+$row['Report']['pending_amount'];
				}				
				?>
				<tr>
					<td><?php echo $i;?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Report']['date']));?></td>
					<td>
						<?php 
							$options = Configure::read('BusinessTypes');
							echo $options[$row['Report']['business_type']];
						?>
					</td>
					
					<td><?php echo $row['Report']['category_name'];?></td>
					<td><?php echo $row['Report']['particular'];?></td>
					<?php echo ($inventory) ? "<td>{$row['Report']['quantity']}</td>" : null?>
					<td>
						<?php echo ($row['Report']['total_amount']) ? $this->Number->currency($row['Report']['total_amount'], $CUR) : '-';?>
					</td>				
					
					<td>
						<div class="floatLeft" style="width:110px;">
							<?php
							if($row['Report']['transaction_type'] == 'debit') {
							?>
							<span class="debit">
								<?php echo ($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-';?>
							</span>
							<?php
							}
							?>
							&nbsp;
						</div>
						<div class="floatLeft">
							<?php
							if($row['Report']['transaction_type'] == 'credit') {
							?>
							<span class="credit">
								<?php echo ($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-';?>
							</span>
							<?php
							}
							?>
						</div>
						<div class="clear"></div>						
					</td>								
					<td>
						<?php							
							if($row['Report']['transaction_type'] == 'credit') {
								echo "<span class='credit'>";
								echo ($row['Report']['pending_amount'] > 0) ? $this->Number->currency($row['Report']['pending_amount'], $CUR) : '-';
								echo "</span>";
							}
							else{
								echo "<span class='debit'>";
								echo ($row['Report']['pending_amount'] > 0) ? $this->Number->currency($row['Report']['pending_amount'], $CUR) : '-';
								echo "</span>";
							}
						?> 
					</td>
				</tr>			
				<?php
			}
			?>
			
		</tbody>
	</table>
	<br><br>
	
	<?php echo $this->element('Reports/simple_report', array('transactions'=>$transactions));?>
	<br><br>
	
<?php	
	}
	else {
	?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th width='40'>Sl.No.</th>				
				<th width='80'>Date</th>			
				<th width='150'>Category</th>
				<th>Transaction Details</th>
				<th>Income</th>
				<th>Expense</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			
			
			foreach($results as $row) {
				$i++;					
				
				
				if($row['Report']['business_type'] == 'cash') {
					$link = '/cash/edit/'.$row['Report']['id'];
					
					if($row['Report']['transaction_type'] == 'credit') {
						$transactions['totalCashCredit']+=$row['Report']['payment_amount'];
					}
					if($row['Report']['transaction_type'] == 'debit') {
						$transactions['totalCashDebit']+=$row['Report']['payment_amount'];
						
					}											
				?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo date('d M Y', strtotime($row['Report']['date']));?></td>					
						<td><?php echo $row['Report']['category_name'];?></td>
						<td><?php echo $this->Html->link($row['Report']['particular'], $link, array('style'=>'font-weight:normal; text-decoration:underline;'));?></td>
						<td>
							<span class="credit">
							<?php
							if($row['Report']['transaction_type'] == 'credit') {
								echo ($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-';
							}
							else {
								echo '-';
							}
							?>
							</span>
						</td>
						<td>
							<span class="debit">
							<?php
							if($row['Report']['transaction_type'] == 'debit') {
								echo ($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-';
							}
							else {
								echo '-';
							}
							?>
							</span>
						</td>				
					</tr>			
				<?php
				}
			}
			?>
			<tr>
				<td colspan='4' style="text-align:right;"><b>Total Amount</b></td>
				<td><span class="credit"><b><?php echo ($transactions['totalCashCredit'] > 0) ? $this->Number->currency($transactions['totalCashCredit'], $CUR) : '-';?><b></span></td>
				<td><span class="debit"><b><?php echo ($transactions['totalCashDebit'] > 0) ? $this->Number->currency($transactions['totalCashDebit'], $CUR) : '-';?><b></span></td>
			</tr>
		</tbody>
	</table>
	<br><br>
	<table style="width:800px;">
		<tr>
			<th><?php echo date('F, Y');?></th>
			<th>Income</th>
			<th>Expenses</th>
		</tr>
		<tr>
			<td><b>Total Amount</b></td>
			<td><span class="credit"><b><?php echo ($transactions['totalCashCredit'] > 0) ? $this->Number->currency($transactions['totalCashCredit'], $CUR) : '-';?><b></span></td>
			<td><span class="debit"><b><?php echo ($transactions['totalCashDebit'] > 0) ? $this->Number->currency($transactions['totalCashDebit'], $CUR) : '-';?><b></span></td>
		</tr>
	</table>	
	<?php
	}
}
else {
	
	echo '<div>No Records Found</div>';
	
}
?>
