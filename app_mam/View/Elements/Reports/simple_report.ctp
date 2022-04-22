<?php
$expected = $transactions['totalIncome']-$transactions['totalExpenses'];
$actual = $transactions['subTotalIncome']-$transactions['subTotalExpenses'];
?>
<div class="corner setBackground" style=" padding:10px;">
<h2>Balance</h2>
<table style="width:600px; font-weight:bold;" cellpadding='1' cellspacing='1'>
	<tr>
		<th width="100">Balance</th>
		<th width="250">Excluding Pending Payments</th>
		<th width="250">Including Pending Payments</th>
	</tr>
	<tr>
		<td>At Profit</td>
		<td class="credit"><?php echo ($actual>0) ? $this->Number->currency($actual, $CUR) : '-';?></td>			
		<td class="credit"><?php echo ($expected>0) ? $this->Number->currency($expected, $CUR) : '-';?></td>			
	</tr>
	<tr>
		<td>At Loss</td>
		<td class="debit"><?php echo ($actual<=0) ? $this->Number->currency(abs($actual), $CUR) : '-';?></td>
		<td class="debit"><?php echo ($expected<=0) ? $this->Number->currency(abs($expected), $CUR) : '-';?></td>
	</tr>
</table>
<br><br>

<h2>Income & Expenses</h2>
<table style="width:600px;" cellpadding='1' cellspacing='1'>
	<tr>
		<th width="100">&nbsp;</th>
		<th width="100">Total</th>
		<th width="100">Credit</th>
		<th width="100">Debit</th>
		<th>Pending Payments</th>
	</tr>
	<tr>
		<td><b>Income</b></td>
		<td class="credit"><b><?php echo ($transactions['totalIncome'] > 0) ? $this->Number->currency($transactions['totalIncome'], $CUR) : '-';?></b></td>
		<td class="credit"><?php echo ($transactions['subTotalIncome'] > 0) ? $this->Number->currency($transactions['subTotalIncome'], $CUR) : '-';?></td>
		<td>-</td>
		<td class="credit"><?php echo ($transactions['pendingIncome'] > 0) ? $this->Number->currency($transactions['pendingIncome'], $CUR) : '-';?></td>
	</tr>
	<tr>
		<td><b>Expenses</b></td>
		<td class="debit"><b><?php echo ($transactions['totalExpenses'] > 0) ? $this->Number->currency($transactions['totalExpenses'], $CUR) : '-';?></b></td>
		<td>-</td>
		<td class="debit"><?php echo ($transactions['subTotalExpenses'] > 0) ? $this->Number->currency($transactions['subTotalExpenses'], $CUR) : '-';?></td>
		<td class="debit"><?php echo ($transactions['pendingExpenses'] > 0) ? $this->Number->currency($transactions['pendingExpenses'], $CUR) : '-';?></td>
	</tr>
</table>
<br><br>
<?php
/*
<h2>Sales, Purchases & Cash Transactions</h2>
<table style="width:600px;" cellpadding='1' cellspacing='1'>
	<tr>
		<th width="150">Sales</th>
		<th width="150">Purchases</th>
		<th width="150">Cash Credit</th>
		<th width="150">Cash Debit</th>
	</tr>
	<tr>
		<td class="credit"><?php echo ($transactions['totalSales'] > 0) ? $this->Number->currency($transactions['totalSales'], $CUR) : '-';?></td>
		<td class="debit">-</td>
		<td class="credit"><?php echo ($transactions['totalCashCredit'] > 0) ? $this->Number->currency($transactions['totalCashCredit'], $CUR) : '-';?></td>
		<td class="debit">-</td>		
	</tr>
	<tr>			
		<td class="credit">-</td>
		<td class="debit"><?php echo ($transactions['totalPurchases'] > 0) ? $this->Number->currency($transactions['totalPurchases'], $CUR) : '-';?></td>
		<td class="credit">-</td>
		<td class="debit"><?php echo ($transactions['totalCashDebit'] > 0) ? $this->Number->currency($transactions['totalCashDebit'], $CUR) : '-';?></td>
	</tr>
</table>
<br><br>	

<h2>Pending Payments</h2>
<table style="width:600px;" cellpadding='1' cellspacing='1'>
	<tr>
		<th width="150">Sales</th>
		<th width="150">Purchases</th>
		<th width="150">Cash Credit</th>
		<th width="150">Cash Debit</th>
	</tr>
	<tr>
		<td class="credit"><?php echo ($transactions['pendingSales'] > 0) ? $this->Number->currency($transactions['pendingSales'], $CUR) : '-';?></td>
		<td class="debit">-</td>
		<td class="credit"><?php echo ($transactions['pendingCashCredit'] > 0) ? $this->Number->currency($transactions['pendingCashCredit'], $CUR) : '-';?></td>
		<td class="debit">-</td>		
	</tr>
	<tr>			
		<td class="credit">-</td>
		<td class="debit"><?php echo ($transactions['pendingPurchases'] > 0) ? $this->Number->currency($transactions['pendingPurchases'], $CUR) : '-';?></td>
		<td class="credit">-</td>
		<td class="debit"><?php echo ($transactions['pendingCashDebit'] > 0) ? $this->Number->currency($transactions['pendingCashDebit'], $CUR) : '-';?></td>
	</tr>
</table>
<br><br>	
*/
?>
</div>
	