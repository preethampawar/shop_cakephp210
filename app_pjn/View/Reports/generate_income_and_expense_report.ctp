<?php
$title_for_layout = ($paymentType == 'income') ? 'Income Report' : (($paymentType == 'expense') ? 'Expense Report' : 'Income & Expense Report');
echo $this->set('title_for_layout', $title_for_layout);
?>
	<h1><?php echo $title_for_layout; ?></h1>
	<h4>From <?php echo date('d M Y', strtotime($fromDate)); ?> to <?php echo date('d M Y', strtotime($toDate)); ?></h4>

<?php
if ($result or $salaries or $purchases or $sales) {
	?>
	<table class='table'>
		<thead>
		<tr>
			<th>Sl.No</th>
			<th>Date</th>
			<th>Category<?php echo ($purchases or $sales) ? '/Product' : null; ?></th>
			<?php echo ($purchases or $sales) ? '<th>Units</th>' : null; ?>
			<th>Expenses</th>
			<th>Income</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		$totalIncome = 0;
		$totalExpenses = 0;

		if ($result) {
			?>
			<tr>
				<td colspan='<?php echo ($purchases or $sales) ? '6' : '5'; ?>' style="font-weight:bold;">Income &
					Expenses
				</td>
			</tr>
			<?php
			foreach ($result as $row) {
				$i++;
				$totalIncome += ($row['Cashbook']['payment_type'] == 'income') ? $row['Cashbook']['payment_amount'] : 0;
				$totalExpenses += ($row['Cashbook']['payment_type'] == 'expense') ? $row['Cashbook']['payment_amount'] : 0;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Cashbook']['payment_date'])); ?></td>
					<td><?php echo $row['Cashbook']['category_name']; ?></td>
					<?php echo ($purchases or $sales) ? '<td>&nbsp;</td>' : null; ?>
					<td><?php echo ($row['Cashbook']['payment_type'] == 'expense') ? $row['Cashbook']['payment_amount'] : ' '; ?></td>
					<td><?php echo ($row['Cashbook']['payment_type'] == 'income') ? $row['Cashbook']['payment_amount'] : ' '; ?></td>
				</tr>
				<?php
			}
		}

		if ($sales) {
			?>
			<tr>
				<td colspan='<?php echo ($purchases or $sales) ? '6' : '5'; ?>' style="font-weight:bold;">Sales</td>
			</tr>
			<?php
			foreach ($sales as $row) {
				$i++;
				$totalIncome += $row['Sale']['total_amount'];
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Sale']['sale_date'])); ?></td>
					<td><?php echo $row['Sale']['product_name']; ?></td>
					<td><?php echo $row['Sale']['total_units']; ?></td>
					<td></td>
					<td><?php echo $row['Sale']['total_amount']; ?></td>
				</tr>
				<?php
			}
		}

		if ($purchases) {
			?>
			<tr>
				<td colspan='<?php echo ($purchases or $sales) ? '6' : '5'; ?>' style="font-weight:bold;">Purchases</td>
			</tr>
			<?php
			foreach ($purchases as $row) {
				$i++;
				$totalExpenses += $row['Purchase']['total_amount'];
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Purchase']['purchase_date'])); ?></td>
					<td><?php echo $row['Purchase']['product_name']; ?></td>
					<td><?php echo $row['Purchase']['total_units']; ?></td>
					<td><?php echo $row['Purchase']['total_amount']; ?></td>
					<td></td>
				</tr>
				<?php
			}
		}

		if ($salaries) {
			?>
			<tr>
				<td colspan='<?php echo ($purchases or $sales) ? '6' : '5'; ?>' style="font-weight:bold;">Salaries</td>
			</tr>
			<?php
			foreach ($salaries as $row) {
				$i++;
				$totalExpenses += $row['Salary']['payment_amount'];
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Salary']['payment_date'])); ?></td>
					<td><?php echo $row['Salary']['employee_name']; ?></td>
					<?php echo ($purchases or $sales) ? '<td>&nbsp;</td>' : null; ?>
					<td><?php echo $row['Salary']['payment_amount']; ?></td>
					<td></td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<th colspan='<?php echo ($purchases or $sales) ? '4' : '3'; ?>' style="text-align:right">Total:</th>
			<th><?php echo (($paymentType == 'expense') or ($paymentType == '')) ? number_format($totalExpenses, 2, '.', '') : null; ?></th>
			<th><?php echo (($paymentType == 'income') or ($paymentType == '')) ? number_format($totalIncome, 2, '.', '') : null; ?></th>
		</tr>
		<tr>
			<th colspan='<?php echo ($purchases or $sales) ? '4' : '3'; ?>' style="text-align:right"></th>
			<th>Expenses</th>
			<th>Income</th>
		</tr>
		</tfoot>
	</table>
	<?php
} else {
	echo 'No records found';
}
?>
