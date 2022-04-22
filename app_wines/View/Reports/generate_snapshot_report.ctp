<?php
$totalInvoiceValue = (float) $invoicesInfo['total_invoice_value'] ?? 0;
$totalMrpRoundingOff = (float) $invoicesInfo['total_mrp_rounding_off'] ?? 0;
$totalExciseCess = (float) $invoicesInfo['total_special_excise_cess'] ?? 0;
$totalTcsValue = (float) $invoicesInfo['total_tcs_value'] ?? 0;
$totalNewRetailerPrefTax = (float) $invoicesInfo['total_new_retailer_prof_tax'] ?? 0;
$totalDdAmount = (float) $invoicesInfo['total_dd_amount'] ?? 0;


$totalPurchaseValue = $totalInvoiceValue + $totalMrpRoundingOff + $totalExciseCess + $totalTcsValue + $totalNewRetailerPrefTax;
$retailerCreditBalance = $totalDdAmount - $totalPurchaseValue;

$totalSales = (float) ($sales['total_sale_amount'] ?? 0);
$totalBreakages = (float) ($breakages['total_breakage_amount'] ?? 0);
$totalCashbookIncome = (float) ($cashbookIncome['total_income_amount'] ?? 0);
$totalCashbookExpenses = (float) ($cashbookExpenses['total_expense_amount'] ?? 0);

$totalBalance = $totalSales - $totalPurchaseValue - $totalBreakages + $totalCashbookIncome - $totalCashbookExpenses;
?>

<?php
$title_for_layout = 'Business Snapshot Report';
echo $this->set('title_for_layout', $title_for_layout);
?>
<h1 class=""><?php echo $title_for_layout; ?></h1><br>

<div class="text-muted mt-3">
	From <?php echo date('d M Y', strtotime($fromDate)); ?> to <?php echo date('d M Y', strtotime($toDate)); ?>
</div>
<br>

<table class="table" style="width: 600px;">
	<tr>
		<th>
			Net Purchase Value:<br>
			<span class="small text-muted">
				(Total Invoice Value + Total MRP Rounding Off + Total Special Excise Cess + Total TCS + Total New Retailer Proffesional Tax)
			</span>
		</th>
		<td><?= $totalPurchaseValue ?></td>
	</tr>
	<tr>
		<th>Total Sales:</th>
		<td><?= $totalSales ?></td>
	</tr>
	<tr>
		<th>Total Breakages:</th>
		<td><?= $totalBreakages ?></td>
	</tr>
	<tr>
		<th>Total Cashbook Income:</th>
		<td><?= $totalCashbookIncome ?></td>
	</tr>
	<tr>
		<th>Total Cashbook Expenses:</th>
		<td><?= $totalCashbookExpenses ?></td>
	</tr>
	<tr>
		<th>
			Balance:<br>
			<span class="small text-muted">
				(Total Sales + Total Cashbook Income - Net Purchase Value - Total Breakages - Total Cashbook Expenses)
			</span>
		</th>
		<td><?= $totalBalance ?></td>
	</tr>

</table>
<br><br>


<h3>Net Purchase Value</h3>
<table class="table" style="width: 600px;">
	<tr>
		<th>Total Invoice Value:</th>
		<td><?= $totalInvoiceValue ?></td>
	</tr>
	<tr>
		<th>Total MRP Rounding Off:</th>
		<td><?= $totalMrpRoundingOff ?></td>
	</tr>
	<tr>
		<th>Total Special Excise Cess:</th>
		<td><?= $totalExciseCess ?></td>
	</tr>
	<tr>
		<th>Total TCS:</th>
		<td><?= $totalTcsValue ?></td>
	</tr>
	<tr>
		<th>Total New Retailer Professional Tax:</th>
		<td><?= $totalNewRetailerPrefTax ?></td>
	</tr>
	<tr>
		<th>
			Net Purchase Value:<br>
			<span class="small text-muted">
				(Total Invoice Value + Total MRP Rounding Off + Total Special Excise Cess + Total TCS + Total New Retailer Professional Tax)
			</span>
		</th>
		<td><?= $totalPurchaseValue ?></td>
	</tr>

</table>
<br><br>
