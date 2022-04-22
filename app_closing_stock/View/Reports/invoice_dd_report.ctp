<?php
// consider previous credit balance
$previous_credit_value = 0;
if ($prevInvoices) {
	$k = 0;

	$prev_total_dd_amount = 0;
	$prev_total_purchase_value = 0;

	foreach ($prevInvoices as $row) {
		$prev_total_dd_amount += $row['Invoice']['dd_amount'];
		$prev_total_purchase_value += $row['Invoice']['dd_purchase'];
	}
	$previous_credit_value = number_format(($prev_total_dd_amount - $prev_total_purchase_value), '2', '.', '');
}
?>

<?php
if ($viewType != 'download') {
	?>
	<?php $this->start('reports_menu'); ?>
	<?php echo $this->element('reports_menu'); ?>
	<?php $this->end(); ?>

	<?php
	$title_for_layout = 'Invoice - DD Report';
	$this->set('title_for_layout', $title_for_layout);
	?>
	<h1><?php echo $title_for_layout; ?></h1>
	<?php
	if ($showForm) {
		?>
		<?php
		echo $this->Form->create('Report');
		?>
		<div id="paramsDiv">
			<div style="clear:none;">
				<?php
				$options = ['print' => 'Print View', 'download' => 'Download'];
				echo $this->Form->input('view_type', ['empty' => 'Normal View', 'label' => 'Download/Select View', 'type' => 'select', 'options' => $options, 'escape' => false]);
				?>
			</div>
			<div style="clear:none;">
				<?php echo $this->Form->input('show_prev_balance', ['label' => 'Consider Previous DD Balance', 'type' => 'checkbox', 'value' => '1', 'default' => '1']); ?>
			</div>
			<div style="float:left; clear:none;">
				<?php
				echo $this->Form->input('from_date', ['label' => 'From Date', 'required' => true, 'type' => 'date', 'default' => date('Y-m-d', strtotime('-1 months'))]); ?>
			</div>
			<div style="float:left; clear:none; margin-left:10px; ">
				<?php echo $this->Form->input('to_date', ['label' => 'To Date', 'required' => true, 'type' => 'date']); ?>
			</div>
			<div style="float:left; clear:none; padding-top:15px;margin-left:10px; ">
				<?php echo $this->Form->submit('Search', ['id' => 'SubmitForm', 'title' => '', 'type' => 'submit', 'onclick' => 'return submitButtonMsg()']); ?>
			</div>
			<div style="clear:both;"></div>
		</div>
		<?php
		echo $this->Form->end();
		?>
		<?php
	}
	?>

	<?php
	if (isset($invoices) and !empty($invoices)) {
		?>
		<table class='table' style="width:100%;">
			<thead>
			<tr>
				<th style="width:10px;">#</th>
				<th style="width:200px;">Invoice No.</th>
				<th style="width:150px;">Invoice Date</th>
				<th style="width:150px;">Invoice Value</th>
				<th style="width:150px;">MRP Rounding Up</th>
				<th style="width:150px;">Net Invoice Value</th>
				<th style="width:150px;">Retail Shop Excise Turnover Tax</th>
				<th style="width:150px;">Special Excise Cess</th>
				<th style="width:150px;">TCS</th>
				<th style="width:150px;">DD Amount</th>
				<th style="width:150px;">Prev Credit</th>
				<th style="width:150px;">Credit Balance</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;
			$total_invoice_value = 0;
			$total_special_margin = 0;
			$total_tcs_value = 0;
			$total_net_invoice_value = 0;
			$total_dd_amount = 0;
			$total_prev_credit = 0;
			$total_purchase_value = 0;
			$total_mrp_rounding_off = 0;
			$total_retail_shop_excise_turnover_tax = 0;
			$total_special_excise_cess = 0;
			$total_credit_balance = 0;

			foreach ($invoices as $row) {
				$k++;
				$total_invoice_value += $row['Invoice']['invoice_value'];
				$total_special_margin += $row['Invoice']['special_margin'];
				$total_net_invoice_value += ($row['Invoice']['invoice_value'] + $row['Invoice']['special_margin'] + $row['Invoice']['mrp_rounding_off']);
				$total_tcs_value += $row['Invoice']['tcs_value'];
				$total_dd_amount += $row['Invoice']['dd_amount'];
				$total_prev_credit += $row['Invoice']['prev_credit'];
				$total_purchase_value += $row['Invoice']['dd_purchase'];
				$total_mrp_rounding_off += $row['Invoice']['mrp_rounding_off'];
				$total_retail_shop_excise_turnover_tax += $row['Invoice']['retail_shop_excise_turnover_tax'];
				$total_special_excise_cess += $row['Invoice']['special_excise_cess'];
				$total_credit_balance += $row['Invoice']['credit_balance'];
				?>
				<tr>
					<td><?php echo $k; ?></td>
					<td>
						<?php
						echo $this->Html->link($row['Invoice']['name'], ['controller' => 'invoices', 'action' => 'details', $row['Invoice']['id']], ['title' => 'Invoice Details - ' . $row['Invoice']['name']]);
						?>
					</td>
					<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date'])); ?></td>
					<td><?php echo $row['Invoice']['invoice_value']; ?></td>
					<td><?php echo $row['Invoice']['mrp_rounding_off']; ?></td>
					<td><?php echo $row['Invoice']['invoice_value'] + $row['Invoice']['special_margin'] + $row['Invoice']['mrp_rounding_off']; ?></td>
					<td><?php echo $row['Invoice']['retail_shop_excise_turnover_tax']; ?></td>
					<td><?php echo $row['Invoice']['special_excise_cess']; ?></td>
					<td><?php echo $row['Invoice']['tcs_value']; ?></td>
					<td><?php echo $row['Invoice']['dd_amount']; ?></td>
					<td><?php echo $row['Invoice']['prev_credit']; ?></td>
					<td><?php echo $row['Invoice']['credit_balance']; ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
			<tfoot style="font-weight:bold;">
			<tr>
				<td colspan='3' style="text-align:right;">Total:</td>
				<td title="Total Invoice Value"><?php echo $total_invoice_value; ?></td>
				<td title="Total MRP Rounding Up"><?php echo number_format($total_mrp_rounding_off, '2', '.', ''); ?></td>
				<td title="Total Net Invoice Value"><?php echo $total_net_invoice_value; ?></td>
				<td title="Total Retail Shop Excise Turnover Tax"><?php echo $total_retail_shop_excise_turnover_tax; ?></td>
				<td title="Special Excise Cess"><?php echo $total_special_excise_cess; ?></td>
				<td title="Total TCS Value"><?php echo number_format($total_tcs_value, '2', '.', ''); ?></td>
				<td title="Total DD Amount"><?php echo number_format($total_dd_amount, '2', '.', ''); ?></td>
				<td><?php //echo $total_prev_credit;?></td>
				<td><?php //echo $total_credit_balance;?></td>
			</tr>
			</tfoot>
		</table>
		<br><br>

		<style type="text/css">
			.table-values tr td {
				text-align: right;
			}
		</style>
		<table class="table table-values" style="width:400px;">
			<tr>
				<th colspan='2' style="text-align:center;">Invoice DD Report - Summary</th>
			</tr>
			<!--			<tr>-->
			<!--				<td style="width:70%">Previous Credit Balance</td>-->
			<!--				<td>--><?php //echo $previous_credit_value;?><!--</td>-->
			<!--			</tr>-->
			<tr>
				<td>Total Invoice Value</td>
				<td><?php echo $total_invoice_value; ?></td>
			</tr>
			<tr>
				<td>Total MRP Rounding Up</td>
				<td><?php echo number_format($total_mrp_rounding_off, '2', '.', ''); ?></td>
			</tr>
			<tr>
				<td>Total Net Invoice Value</td>
				<td><?php echo $total_net_invoice_value; ?></td>
			</tr>


			<tr>
				<td>Total Retail Shop Excise Turnover Tax</td>
				<td><?php echo number_format($total_retail_shop_excise_turnover_tax, '2', '.', ''); ?></td>
			</tr>
			<tr>
				<td>Total Special Excise Cess</td>
				<td><?php echo number_format($total_special_excise_cess, '2', '.', ''); ?></td>
			</tr>


			<tr>
				<td>Total TCS Value</td>
				<td><?php echo number_format($total_tcs_value, '2', '.', ''); ?></td>
			</tr>
			<tr style="font-weight:bold;">
				<td>Total DD Amount</td>
				<td><?php echo number_format($total_dd_amount, '2', '.', ''); ?></td>
			</tr>
			<tr style="font-weight:bold;">
				<td>Total Purchase Value</td>
				<td><?php echo number_format($total_purchase_value, '2', '.', ''); ?></td>
			</tr>
			<!--			<tr style="font-weight:bold;">-->
			<!--				<td>Retailer Credit Balance</td>-->
			<!--				<td>-->
			<?php //echo number_format(($total_dd_amount-$total_purchase_value+$previous_credit_value), '2', '.', '');?><!--</td>-->
			<!--			</tr>-->
		</table>
		<?php
	} else {
		echo '<p> - No Invoices Found</p>';
	}
} else {
	// generate report in csv format
	$csv = 'Invoice DD Report: From ' . date('d M Y', strtotime($fromDate)) . ' to ' . date('d M Y', strtotime($toDate)) . "\r\n";
	$csv .= "\r\n";
	if (!empty($invoices)) {

		$csv .= implode(['Sl.No.', 'Invoice No.', 'Invoice Date', 'Invoice Value', 'MRP Rounding Up', 'Net Invoice Value', 'Total Retail Shop Excise Turnover Tax', 'Total Special Excise Cess', 'TCS', 'DD Amount', 'Prev Credit', 'Credit Balance'], ",") . "\r\n";
		$k = 0;

		$total_invoice_value = 0;
		$total_special_margin = 0;
		$total_tcs_value = 0;
		$total_net_invoice_value = 0;
		$total_dd_amount = 0;
		$total_prev_credit = 0;
		$total_purchase_value = 0;
		$total_retail_shop_excise_turnover_tax = 0;
		$total_special_excise_cess = 0;

		foreach ($invoices as $row) {
			$k++;
			$total_invoice_value += $row['Invoice']['invoice_value'];
			$total_special_margin += $row['Invoice']['special_margin'];
			$total_net_invoice_value += ($row['Invoice']['invoice_value'] + $row['Invoice']['special_margin']);
			$total_tcs_value += $row['Invoice']['tcs_value'];
			$total_dd_amount += $row['Invoice']['dd_amount'];
			$total_prev_credit += $row['Invoice']['prev_credit'];
			$total_purchase_value += $row['Invoice']['dd_purchase'];
			$total_retail_shop_excise_turnover_tax += $row['Invoice']['retail_shop_excise_turnover_tax'];
			$total_special_excise_cess += $row['Invoice']['special_excise_cess'];

			$invoice_date = date('d-m-Y', strtotime($row['Invoice']['invoice_date']));
			$tmp = [];
			$tmp[] = $k;
			$tmp[] = $row['Invoice']['name'];
			$tmp[] = " " . $invoice_date . " ";
			$tmp[] = $row['Invoice']['invoice_value'];
			$tmp[] = $row['Invoice']['special_margin'];
			$tmp[] = $row['Invoice']['invoice_value'] + $row['Invoice']['special_margin'];
			$tmp[] = $row['Invoice']['retail_shop_excise_turnover_tax'];
			$tmp[] = $row['Invoice']['special_excise_cess'];
			$tmp[] = $row['Invoice']['tcs_value'];
			$tmp[] = $row['Invoice']['dd_amount'];
			$tmp[] = $row['Invoice']['prev_credit'];
			$tmp[] = $row['Invoice']['credit_balance'];
			$csv .= implode($tmp, ',') . "\r\n";
		}
		$csv .= " , , Total: ,$total_invoice_value, $total_special_margin, $total_net_invoice_value, $total_retail_shop_excise_turnover_tax, $total_special_excise_cess, $total_tcs_value, $total_dd_amount, , ";

		$csv .= "\n\r\n\r";
		$csv .= "Invoice DD Report - Summary \n\r";

		$csv .= "\n\r";
		//$csv.= 'Previous Credit Balance,'.$previous_credit_value."\r\n";
		$csv .= 'Total Invoice Value,' . $total_invoice_value . "\r\n";
		$csv .= 'Total MRP Rounding Up,' . $total_special_margin . "\r\n";
		$csv .= 'Total Net Invoice Value,' . $total_net_invoice_value . "\r\n";
		$csv .= 'Total Retail Shop Excise Turnover Tax,' . $total_retail_shop_excise_turnover_tax . "\r\n";
		$csv .= 'Total Special Excise Cess,' . $total_special_excise_cess . "\r\n";
		$csv .= 'Total TCS Value,' . $total_tcs_value . "\r\n";
		$csv .= 'Total DD Amount,' . $total_dd_amount . "\r\n";
		$csv .= 'Total Purchase Value,' . $total_purchase_value . "\r\n";
		//$csv.= 'Retailer Credit Balance,'.($total_dd_amount-$total_purchase_value+$previous_credit_value)."\r\n";
	} else {
		$csv .= 'No records found';
	}

	echo $csv;
}
?>
