<?php $this->start('employees_report_menu');?>
<?php echo $this->element('counter_balance_sheet_menu');?>
<?php echo $this->element('counter_balance_sheet_report_menu');?>
<?php echo $this->element('income_expense_report_menu');?>
<?php $this->end();?>

<style type="text/css">
	.table tr td {
		padding: 5px 10px;
		text-align:right;
	}
	.bold {
		font-weight:bold;
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



	<div id="AddSalaryDiv">
		<?php
		$from_date = date('d-m-Y', strtotime($fromDate));
		$from_y = date('Y', strtotime($fromDate));
		$from_m = date('m', strtotime($fromDate));
		$from_d = date('d', strtotime($fromDate));

		$to_date = date('d-m-Y', strtotime($toDate));
		$to_y = date('Y', strtotime($toDate));
		$to_m = date('m', strtotime($toDate));
		$to_d = date('d', strtotime($toDate));

		echo $this->Form->create();
		echo $this->Form->input('save_data', array('type'=>'hidden', 'value'=>0));
		echo $this->Form->input('selected_from_date', array('type'=>'hidden', 'value'=>$fromDate));
		echo $this->Form->input('selected_to_date', array('type'=>'hidden', 'value'=>$toDate));
		echo $this->Form->input('transaction_balance', array('type'=>'hidden', 'value'=>$transaction_balance));
		echo $this->Form->input('short_value', array('type'=>'hidden', 'value'=>0));
		?>
		<div id="paramsDiv">
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('from_date', array('label'=>'From Date', 'required'=>true, 'type'=>'date', 'value'=>$fromDate, 'onchange'=>'hideForm()'));?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('to_date', array('label'=>'To Date', 'required'=>true, 'type'=>'date', 'value'=>$toDate, 'onchange'=>'hideForm()'));?>
			</div>
			<div style="float:left; clear:none; padding-top:5px;">
				<br>
				&nbsp;&nbsp;<?php echo $this->Form->submit('Get details', array('id'=>'submit', 'type'=>'submit', 'div'=>false));?>
			</div>
			<hr style="clear:both;">
			<div class="partialForm well">
				<h1>Calculate Counter Balance</h1><br>
				<div>
					<b>From: <?php echo date('d-m-Y', strtotime($fromDate));?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					To: <?php echo date('d-m-Y', strtotime($toDate));?></b>
				</div>
				<div style="float:left; clear:none;">
					<?php echo $this->Form->input('opening_balance', array('empty'=>false, 'label'=>'Opening Balance', 'required'=>true, 'type'=>'number', 'escape'=>false, 'onchange'=>'updateCounterSheetInfo()', 'default'=>0));?>
				</div>
				<div style="float:left; clear:none;">
					<?php
					echo $this->Form->input('total_sales', array('type'=>'hidden', 'value'=>$sale_amount));
					echo $this->Form->input('tmp_sales', array('type'=>'text', 'label'=>'Total Sales', 'disabled'=>true, 'title'=>'Total Sales', 'value'=>$sale_amount));
					?>
				</div>
				<div style="float:left; clear:both;">
					<?php echo $this->Form->input('counter_cash', array('label'=>'Counter Cash', 'required'=>true, 'type'=>'number', 'default'=>0, 'onchange'=>'updateCounterSheetInfo()'));?>
				</div>
				<div style="float:left; clear:none;">
					<?php echo $this->Form->input('counter_cash_by_card', array('label'=>'Counter Cash By Card', 'required'=>true, 'type'=>'number', 'default'=>0, 'onchange'=>'updateCounterSheetInfo()'));?>
				</div>
				<div style="float:left; clear:none;">
					<?php
					echo $this->Form->input('expenses', array('type'=>'hidden', 'value'=>$expense_amount));
					echo $this->Form->input('tmp_expenses', array('label'=>'Expenses', 'disabled'=>true, 'value'=>$expense_amount, 'style'=>'width:100px;'));
					?>
				</div>
				<div style="float:left; clear:none;">
					<?php echo $this->Form->input('closing_balance', array('label'=>'Closing Balance', 'required'=>true, 'type'=>'number', 'default'=>0, 'style'=>'width:100px;', 'onchange'=>'updateCounterSheetInfo()'));?>
				</div>

				<div style="clear:both; margin:0; padding:0;"></div>
				<div style="padding-bottom:5px;">
					<?php echo $this->Form->submit('Save Counter Balance Sheet', array('id'=>'SubmitForm', 'type'=>'submit', 'div'=>false, 'onclick'=>'return submitCounterSheetForm()'));?>
					<br>
				</div>
			</div>
		</div>
		<?php
		echo $this->Form->end();
		?>
		<div class="partialForm">
			<h2>Preview</h2>
			<div>
				<table class="table">
					<tr>
						<td>Date</td>
						<td colspan='2' style="text-align:left;"><?php echo '<b>'.$from_date.'</b> &nbsp;&nbsp; To  &nbsp;&nbsp;  <b>'.$to_date.'</b>';?></td>
					</tr>
					<tr>
						<td>Opening Balance</td>
						<td><span class="openingBalance">0</span></td>
						<td></td>
					</tr>
					<tr>
						<td>Sales</td>
						<td><span class="totalSales"><?php echo $sale_amount;?></span></td>
						<td>
							<div class="alignLeft">
								<form action="/reports/generateSalesReport" method="post" target="_blank">
									<input type="hidden" name="data[Report][from_date][year]" value="<?php echo $from_y;?>">
									<input type="hidden" name="data[Report][from_date][month]" value="<?php echo $from_m;?>">
									<input type="hidden" name="data[Report][from_date][day]" value="<?php echo $from_d;?>">

									<input type="hidden" name="data[Report][to_date][year]" value="<?php echo $to_y;?>">
									<input type="hidden" name="data[Report][to_date][month]" value="<?php echo $to_m;?>">
									<input type="hidden" name="data[Report][to_date][day]" value="<?php echo $to_d;?>">

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
						<td><span class="totalOBAndSale"><?php echo $sale_amount;?></span></td>
						<td><div class="note">Opening Balance + Sale Value</div></td>
					</tr>
					<tr>
						<td>Counter Cash</td>
						<td><span class="counterCash">0</span></td>
						<td></td>
					</tr>
					<tr>
						<td>Counter Cash By Card</td>
						<td><span class="counterCashByCard">0</span></td>
						<td></td>
					</tr>
					<tr>
						<td>Expenses</td>
						<td><span class="expenses"><?php echo $expense_amount;?></span></td>
						<td>
							<div class="alignLeft">
								<form action="/reports/generateIncomeAndExpenseReport" method="post" target="_blank">
									<input type="hidden" name="data[Report][from_date][year]" value="<?php echo $from_y;?>">
									<input type="hidden" name="data[Report][from_date][month]" value="<?php echo $from_m;?>">
									<input type="hidden" name="data[Report][from_date][day]" value="<?php echo $from_d;?>">

									<input type="hidden" name="data[Report][to_date][year]" value="<?php echo $to_y;?>">
									<input type="hidden" name="data[Report][to_date][month]" value="<?php echo $to_m;?>">
									<input type="hidden" name="data[Report][to_date][day]" value="<?php echo $to_d;?>">

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
						<td><span class="closingBalance">0</span></td>
						<td></td>
					</tr>
					<tr class="bold" style="color: #ff00004d;">
						<td>Sub Total</td>
						<td><span class="totalSpentBalance"><?php echo $expense_amount;?></span></td>
						<td><div class="note">Counter Cash + Card + Expenses + Closing Balance</div></td>
					</tr>
					<tr class="bold" style="border-top:2px solid #666; border-bottom:2px solid #666;">
						<td>Balance Amount</td>
						<td><span class="balanceAmount"><?php echo $balance_amount = ($sale_amount-$expense_amount);?></span></td>
						<td><div class="note">(Opening Balance + Sale Value) - (Counter Cash + Card + Expenses + Closing Balance + Transaction Balance)</div></td>
					</tr>
					<?php
					if(!empty($transactions)) {
					?>
						<tr>
							<td>-</td>
							<td>-</td>
							<td>-</td>
						</tr>
					<?php
						$total_log_amount = 0;
						foreach($transactions as $row) {
							$name = $row['name'];
							$expense = (isset($row['expense'])) ? $row['expense'] : 0;
							$income = (isset($row['income'])) ? $row['income'] : 0;
							$amount = $expense - $income;
							$total_log_amount += $amount;
						?>
						<tr>
							<td>
								<?php echo $name;?>
							</td>
							<td>
								<?php echo ($amount);?>
							</td>
							<td><div class="note">Transaction log</div></td>
						</tr>
						<?php
						}
						$short = $balance_amount - $total_log_amount;
						?>
						<tr class="bold">
							<td>Transaction Balance:</td>
							<td><?php echo ($total_log_amount);?></td>
							<td>
								<div class="alignLeft">
									<form action="/reports/transactionLogReport" method="post" target="_blank">
										<input type="hidden" name="data[Report][from_date][year]" value="<?php echo $from_y;?>">
										<input type="hidden" name="data[Report][from_date][month]" value="<?php echo $from_m;?>">
										<input type="hidden" name="data[Report][from_date][day]" value="<?php echo $from_d;?>">

										<input type="hidden" name="data[Report][to_date][year]" value="<?php echo $to_y;?>">
										<input type="hidden" name="data[Report][to_date][month]" value="<?php echo $to_m;?>">
										<input type="hidden" name="data[Report][to_date][day]" value="<?php echo $to_d;?>">

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
							<td>Short:</td>
							<td class="shortValue"><?php echo $short;?></td>
							<td><div class="note">(Balance Amount - Transaction Balance)</div></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>

		</div>

	</div>
	<script type="text/javascript">
	function hideForm() {
		$('.partialForm').hide();
	}

	function submitCounterSheetForm() {
		updateCounterSheetInfo();
		$("#CounterBalanceSheetSaveData").val(1);
		return true;
	}

	function updateCounterSheetInfo() {
		var openingBal = parseFloat(($('#CounterBalanceSheetOpeningBalance').val()) ? $('#CounterBalanceSheetOpeningBalance').val() : 0);
		var salesAmount = parseFloat(($('#CounterBalanceSheetTotalSales').val()) ? $('#CounterBalanceSheetTotalSales').val() : 0);
		var counterCash = parseFloat(($('#CounterBalanceSheetCounterCash').val()) ? $('#CounterBalanceSheetCounterCash').val() : 0);
		var card = parseFloat(($('#CounterBalanceSheetCounterCashByCard').val()) ? $('#CounterBalanceSheetCounterCashByCard').val() : 0);
		var expenses = parseFloat(($('#CounterBalanceSheetExpenses').val()) ? $('#CounterBalanceSheetExpenses').val() : 0);
		var transactionBalance = parseFloat(($('#CounterBalanceSheetTransactionBalance').val()) ? $('#CounterBalanceSheetTransactionBalance').val() : 0);
		var closingBal = parseFloat(($('#CounterBalanceSheetClosingBalance').val()) ? $('#CounterBalanceSheetClosingBalance').val() : 0);

		var total_avl_bal = (openingBal+salesAmount);
		var total_spent_bal = (counterCash+card+expenses+closingBal);
		var short_value = (total_avl_bal - total_spent_bal - transactionBalance);
		var balanceAmount = (total_avl_bal - total_spent_bal);
		$('#CounterBalanceSheetShortValue').val(short_value.toFixed(2));

		$('.openingBalance').text(openingBal.toFixed(2));
		$('.totalSales').text(salesAmount.toFixed(2));
		$('.totalOBAndSale').text(total_avl_bal.toFixed(2));
		$('.counterCash').text(counterCash.toFixed(2));
		$('.counterCashByCard').text(card.toFixed(2));
		$('.expenses').text(expenses.toFixed(2));
		$('.closingBalance').text(closingBal.toFixed(2));
		$('.totalSpentBalance').text(total_spent_bal.toFixed(2));
		$('.balanceAmount').text(balanceAmount.toFixed(2));
		$('.shortValue').text(short_value.toFixed(2));


	}

	updateCounterSheetInfo();
	</script>

	<br><br>

	<h2>Recent records</h2>
	<?php
	if($sheets) {
	?>
	<table class='table' style="width:100%">
		<thead>
			<tr>
				<th>#</th>
				<th>From Date</th>
				<th>To Date</th>
				<th>Opening Balance</th>
				<th>Total Sales</th>
				<th>Total Value</th>
				<th>Counter Cash</th>
				<th>By Card</th>
				<th>Expenses</th>
				<th>Closing Balance</th>
				<th>Total Value</th>
				<th>Short</th>
				<th></th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($sheets as $row) {
				$i++;

				$total_avl_value = $row['CounterBalanceSheet']['opening_balance']+$row['CounterBalanceSheet']['total_sales'];
				$total_exp_value = $row['CounterBalanceSheet']['counter_cash']+$row['CounterBalanceSheet']['counter_cash_by_card']+$row['CounterBalanceSheet']['expenses']+$row['CounterBalanceSheet']['closing_balance'];
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo date('d-m-Y', strtotime($row['CounterBalanceSheet']['from_date']));?></td>
				<td><?php echo date('d-m-Y', strtotime($row['CounterBalanceSheet']['to_date']));?></td>
				<td><?php echo $row['CounterBalanceSheet']['opening_balance'];?></td>
				<td><?php echo $row['CounterBalanceSheet']['total_sales'];?></td>
				<td><b><?php echo $total_avl_value;?></b></td>
				<td><?php echo $row['CounterBalanceSheet']['counter_cash'];?></td>
				<td><?php echo $row['CounterBalanceSheet']['counter_cash_by_card'];?></td>
				<td><?php echo $row['CounterBalanceSheet']['expenses'];?></td>
				<td><?php echo $row['CounterBalanceSheet']['closing_balance'];?></td>
				<td><b><?php echo $total_exp_value;?></b></td>
				<td><b><?php echo $row['CounterBalanceSheet']['short_value'];?><b></td>
				<td><?php echo $this->Html->link('Details', array('controller'=>'CounterBalanceSheets', 'action'=>'details', $row['CounterBalanceSheet']['id']));?></td>

				<td>
					<form method="post" style="" name="CounterBalanceSheet_<?php echo $row['CounterBalanceSheet']['id'];?>" id="CounterBalanceSheet_<?php echo $row['CounterBalanceSheet']['id'];?>" action="<?php echo $this->Html->url("/CounterBalanceSheets/remove/".$row['CounterBalanceSheet']['id']);?>">
						<input type="submit" value="Remove" name="Remove" onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#CounterBalanceSheet_<?php echo $row['CounterBalanceSheet']['id'];?>').submit(); } event.returnValue = false; return false;">
					</form>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>

	<?php } else { ?>
	<p>No salaries found</p>
	<?php } ?>

