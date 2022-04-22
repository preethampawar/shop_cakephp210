<?php
$totalInvoiceValue = (float) $invoicesInfo['total_invoice_value'] ?? 0;
$totalMrpRoundingOff = (float) $invoicesInfo['total_mrp_rounding_off'] ?? 0;
$totalExciseCess = (float) $invoicesInfo['total_special_excise_cess'] ?? 0;
$totalTcsValue = (float) $invoicesInfo['total_tcs_value'] ?? 0;
$totalNewRetailerPrefTax = (float) $invoicesInfo['total_new_retailer_prof_tax'] ?? 0;
$totalDdAmount = (float) $invoicesInfo['total_dd_amount'] ?? 0;


$totalPurchaseValue = $totalInvoiceValue + $totalMrpRoundingOff + $totalExciseCess + $totalTcsValue + $totalNewRetailerPrefTax;
$retailerCreditBalance = $totalDdAmount - $totalPurchaseValue;

$totalPurchases = (float) ($purchases['total_purchase_amount'] ?? 0);
$totalSales = (float) ($sales['total_sale_amount'] ?? 0);
$totalBreakages = (float) ($breakages['total_breakage_amount'] ?? 0);
$totalCashbookIncome = (float) ($cashbookIncome['total_income_amount'] ?? 0);
$totalCashbookExpenses = (float) ($cashbookExpenses['total_expense_amount'] ?? 0);

$totalBalance = $totalSales - $totalPurchaseValue - $totalBreakages + $totalCashbookIncome - $totalCashbookExpenses;
?>

<?php
$title_for_layout = 'P & L A/C - ';
echo $this->set('title_for_layout', $title_for_layout);
?>
<h1 class="">
	<?php echo $title_for_layout; ?>
	From <?php echo date('d M Y', strtotime($fromDate)); ?> to <?php echo date('d M Y', strtotime($toDate)); ?>
</h1><br>
<br>
<table class="table">
	<thead>
	<tr>
		<th>Name of the Account</th>
		<th>Income</th>
		<th>Expense</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>Liquor Sales</td>
		<td><?= $totalSales ?></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Stock Purchase</td>
		<td>&nbsp;</td>
		<td><?= $totalPurchases ?></td>
	</tr>
	<tr>
		<td>MRP Rounding Off</td>
		<td>&nbsp;</td>
		<td><?= $totalMrpRoundingOff ?></td>
	</tr>
	<tr>
		<td>Special Excise Cess</td>
		<td>&nbsp;</td>
		<td><?= $totalExciseCess ?></td>
	</tr>
	<tr>
		<td>TCS</td>
		<td>&nbsp;</td>
		<td><?= $totalTcsValue ?></td>
	</tr>
	<tr>
		<td>New Retailer Tax</td>
		<td>&nbsp;</td>
		<td><?= $totalNewRetailerPrefTax ?></td>
	</tr>
	<tr>
		<td>Opening Stock</td>
		<td>&nbsp;</td>
		<td><?= number_format($openingStockInfo['openingStockValueAsPerInvoice'], 2, '.', '') ?></td>
	</tr>
	<tr>
		<td>Closing Stock</td>
		<td><?= number_format($closingStockInfo['closingStockValueAsPerInvoice'], 2, '.', '') ?></td>
		<td>&nbsp;</td>
	</tr>
	<tr class="text-muted">
		<td>&nbsp;</td>
		<td>
			<?php
			$grossIncome = $totalSales + $closingStockInfo['closingStockValueAsPerInvoice'];
			echo number_format($grossIncome, 2, '.', '');
			?>
		</td>
		<td>
			<?php
			$grossExpenses = $totalPurchases
				+ $totalMrpRoundingOff
				+ $totalExciseCess
				+ $totalTcsValue
				+ $totalNewRetailerPrefTax
				+ $openingStockInfo['openingStockValueAsPerInvoice'];


			echo number_format($grossExpenses, 2, '.', '');
			?>

		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" class="text-center"><div class="text-center"><b>Gross Profit = <?= number_format($grossIncome - $grossExpenses, 2, '.', '') ?></b></div></td>
	</tr>
	<?php
	$hasCategories = false;

	if(!empty($cashbookInfo)) {
		$cashbookIncome = 0;
		$cashbookExpenses = 0;
		foreach($cashbookInfo as $row) {
			$categoryName = $row['c']['name'];
			$income = (float)$row[0]['total_income_amount'];
			$expenses = (float)$row[0]['total_expense_amount'];

			$cashbookIncome += $income;
			$cashbookExpenses += $expenses;

			if ($income > 0 || $expenses > 0) {
				$hasCategories = true;
			?>
			<tr>
				<td><?= $categoryName ?></td>
				<td><?= $income > 0 ? $income : '' ?></td>
				<td><?= $expenses > 0 ? $expenses : '' ?></td>
			</tr>
			<?php
			}
		}
	}

	if (!$hasCategories) {
		?>
		<tr>
			<th colspan="3" class="text-center"><div class="text-center">&nbsp;</div></th>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<th>&nbsp;</th>
		<th><?= number_format($netIncome = $grossIncome + $cashbookIncome, 2, '.', '') ?></th>
		<th><?= number_format($netExpenses = $grossExpenses + $cashbookExpenses, 2, '.', '') ?></th>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<th colspan="2" class="text-center"><div class="text-center">Net Profit = <?= number_format($netIncome - $netExpenses, 2, '.', '') ?> </div></th>
	</tr>
	</tfoot>
</table>
<br><br>
