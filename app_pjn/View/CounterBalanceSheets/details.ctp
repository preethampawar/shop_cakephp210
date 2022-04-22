<?php $this->start('employees_report_menu'); ?>
<?php echo $this->element('counter_balance_sheet_menu'); ?>
<?php echo $this->element('counter_balance_sheet_report_menu'); ?>
<?php echo $this->element('income_expense_report_menu'); ?>
<?php $this->end(); ?>

<?php
if (!empty($sheet)) {
	$opening_balance = $sheet['CounterBalanceSheet']['opening_balance'];
	$total_sales = $sheet['CounterBalanceSheet']['total_sales'];
	$counter_cash = $sheet['CounterBalanceSheet']['counter_cash'];
	$counter_cash_by_card = $sheet['CounterBalanceSheet']['counter_cash_by_card'];
	$expenses = $sheet['CounterBalanceSheet']['expenses'];
	$closing_balance = $sheet['CounterBalanceSheet']['closing_balance'];

	$from_date = date('d-m-Y', strtotime($sheet['CounterBalanceSheet']['from_date']));
	$from_y = date('Y', strtotime($sheet['CounterBalanceSheet']['from_date']));
	$from_m = date('m', strtotime($sheet['CounterBalanceSheet']['from_date']));
	$from_d = date('d', strtotime($sheet['CounterBalanceSheet']['from_date']));

	$to_date = date('d-m-Y', strtotime($sheet['CounterBalanceSheet']['to_date']));
	$to_y = date('Y', strtotime($sheet['CounterBalanceSheet']['to_date']));
	$to_m = date('m', strtotime($sheet['CounterBalanceSheet']['to_date']));
	$to_d = date('d', strtotime($sheet['CounterBalanceSheet']['to_date']));

	$short_value = $sheet['CounterBalanceSheet']['short_value'];
	$created = date('d-m-Y', strtotime($sheet['CounterBalanceSheet']['created']));
	$total_ob_sale_amount = ($total_sales + $opening_balance);
	$total_spent_amount = ($counter_cash + $counter_cash_by_card + $expenses + $closing_balance);
	$total_balance_amount = $total_ob_sale_amount - $total_spent_amount;
	$short = $sheet['CounterBalanceSheet']['short_value'];
	?>
	<style type="text/css">
		.table tr td {
			padding: 5px 10px;
			text-align: right;
		}

		.bold {
			font-weight: bold;
			color: black;
		}

		.note::before {
			content: '* ';
		}

		.note {
			text-align: left;
			font-style: italic;
			font-weight: normal;
			font-size: 80%;
			padding-left: 10px;
		}

		.alignLeft {
			text-align: left;
		}
	</style>
	<h1>Counter Balance Sheet - No. <?php echo $sheet['CounterBalanceSheet']['id']; ?></h1>

	<div>
		<table class="table">
			<tr>
				<td>Date</td>
				<td colspan='2'
					style="text-align:left;"><?php echo '<b>' . $from_date . '</b> &nbsp;&nbsp; To  &nbsp;&nbsp;  <b>' . $to_date . '</b>'; ?></td>
			</tr>
			<tr>
				<td>Opening Balance</td>
				<td><span class="openingBalance"><?php echo $opening_balance; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td>Sales</td>
				<td><span class="totalSales"><?php echo $total_sales; ?></span></td>
				<td>
					<div class="alignLeft">
						<form action="/reports/generateSalesReport" method="post" target="_blank">
							<input type="hidden" name="data[Report][from_date][year]" value="<?php echo $from_y; ?>">
							<input type="hidden" name="data[Report][from_date][month]" value="<?php echo $from_m; ?>">
							<input type="hidden" name="data[Report][from_date][day]" value="<?php echo $from_d; ?>">

							<input type="hidden" name="data[Report][to_date][year]" value="<?php echo $to_y; ?>">
							<input type="hidden" name="data[Report][to_date][month]" value="<?php echo $to_m; ?>">
							<input type="hidden" name="data[Report][to_date][day]" value="<?php echo $to_d; ?>">

							<input type="hidden" name="data[Report][view_type]" value="print">
							<input type="hidden" name="data[Report][category_id]" value="">
							<input type="hidden" name="data[Report][product_id]" value="">
							<input type="hidden" name="data[Report][show_all_records]" value="1">

							<button type="submit">Show Sale Details</button>
						</form>
					</div>
				</td>
			</tr>
			<tr class="bold" style="color: #016c2466;">
				<td>Sub Total</td>
				<td><span class="totalOBAndSale"><?php echo($total_ob_sale_amount); ?></span></td>
				<td>
					<div class="note">Opening Balance + Sale Value</div>
				</td>
			</tr>
			<tr>
				<td>Counter Cash</td>
				<td><span class="counterCash"><?php echo $counter_cash; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td>Counter Cash By Card</td>
				<td><span class="counterCashByCard"><?php echo $counter_cash_by_card; ?></span></td>
				<td></td>
			</tr>
			<tr>
				<td>Expenses</td>
				<td><span class="expenses"><?php echo $expenses; ?></span></td>
				<td>
					<div class="alignLeft">
						<form action="/reports/generateIncomeAndExpenseReport" method="post" target="_blank">
							<input type="hidden" name="data[Report][from_date][year]" value="<?php echo $from_y; ?>">
							<input type="hidden" name="data[Report][from_date][month]" value="<?php echo $from_m; ?>">
							<input type="hidden" name="data[Report][from_date][day]" value="<?php echo $from_d; ?>">

							<input type="hidden" name="data[Report][to_date][year]" value="<?php echo $to_y; ?>">
							<input type="hidden" name="data[Report][to_date][month]" value="<?php echo $to_m; ?>">
							<input type="hidden" name="data[Report][to_date][day]" value="<?php echo $to_d; ?>">

							<input type="hidden" name="data[Report][view_type]" value="print">
							<input type="hidden" name="data[Report][category_id]" value="">
							<input type="hidden" name="data[Report][payment_type]" value="">
							<input type="hidden" name="data[Report][salary]" value="0">
							<input type="hidden" name="data[Report][sales_purchases]" value="0">
							<input type="hidden" name="data[Report][show_all_records]" value="1">

							<button type="submit">Show Expense Details</button>
						</form>
					</div>
				</td>
			</tr>
			<tr>
				<td>Closing Balance</td>
				<td><span class="closingBalance"><?php echo $closing_balance; ?></span></td>
				<td></td>
			</tr>
			<tr class="bold" style="color: #ff00004d;">
				<td>Sub Total</td>
				<td><span class="totalSpentBalance"><?php echo $total_spent_amount; ?></span></td>
				<td>
					<div class="note">Counter Cash + Card + Expenses + Closing Balance</div>
				</td>
			</tr>
			<tr class="bold" style="border-top:2px solid #666; border-bottom:2px solid #666;">
				<td>Balance Amount</td>
				<td><span class="balanceAmount"><?php echo $total_balance_amount; ?></span></td>
				<td>
					<div class="note">(Opening Balance + Sale Value) - (Counter Cash + Card + Expenses + Closing
						Balance)
					</div>
				</td>
			</tr>
			<?php
			if (!empty($transactions)) {
				?>
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
				<?php
				$total_log_amount = 0;
				foreach ($transactions as $row) {
					$name = $row['name'];
					$expense = (isset($row['expense'])) ? $row['expense'] : 0;
					$income = (isset($row['income'])) ? $row['income'] : 0;
					$amount = $expense - $income;
					$total_log_amount += $amount;
					?>
					<tr>
						<td>
							<?php echo $name; ?>
						</td>
						<td>
							<?php echo($amount); ?>
						</td>
						<td>
							<div class="note">Transaction log</div>
						</td>
					</tr>
					<?php
				}
				$short = $total_balance_amount - $total_log_amount;
				?>
				<tr class="bold">
					<td>Sub Total:</td>
					<td><?php echo($total_log_amount); ?></td>
					<td>
						<div class="alignLeft">
							<form action="/reports/transactionLogReport" method="post" target="_blank">
								<input type="hidden" name="data[Report][from_date][year]"
									   value="<?php echo $from_y; ?>">
								<input type="hidden" name="data[Report][from_date][month]"
									   value="<?php echo $from_m; ?>">
								<input type="hidden" name="data[Report][from_date][day]" value="<?php echo $from_d; ?>">

								<input type="hidden" name="data[Report][to_date][year]" value="<?php echo $to_y; ?>">
								<input type="hidden" name="data[Report][to_date][month]" value="<?php echo $to_m; ?>">
								<input type="hidden" name="data[Report][to_date][day]" value="<?php echo $to_d; ?>">

								<input type="hidden" name="data[Report][view_type]" value="print">
								<input type="hidden" name="data[Report][payment_type]" value="">
								<input type="hidden" name="data[Report][tag_id]" value="">
								<input type="hidden" name="data[Report][show_all_records]" value="1">

								<button type="submit">Show All Transactions</button>
							</form>
						</div>

					</td>
				</tr>
				<tr class="bold" style="border-top:2px solid #666; border-bottom:2px solid #666;">
					<td>Short Value:</td>
					<td><?php echo $short; ?></td>
					<td>
						<div class="note">(Balance Amount - Sub Total)</div>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
	</div>

	<?php
} else {
	echo 'Record not found';
}
?>
