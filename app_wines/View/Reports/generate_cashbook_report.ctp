<?php
$title_for_layout = ($paymentType=='income')? 'Income Report': (($paymentType=='expense') ? 'Expense Report' : 'Income & Expense Report');
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
				<th>Category</th>
				<th>Description</th>
				<th>Expenses</th>
				<th>Income</th> 
			</tr>
		</thead>
		<tbody>
		<?php
		$i=0;
		$totalIncome=0;
		$totalExpenses=0;
		if($result) {		
			foreach($result as $row) {
				$i++;
				$totalIncome+=($row['Cashbook']['payment_type'] == 'income') ? $row['Cashbook']['payment_amount'] : 0;
				$totalExpenses+=($row['Cashbook']['payment_type'] == 'expense') ? $row['Cashbook']['payment_amount'] : 0;
			?>
				<tr>
					<td><?php echo $i;?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Cashbook']['payment_date']));?></td>
					<td><?php echo $row['Cashbook']['category_name'];?></td>					
					<td><?php echo $row['Cashbook']['description'];?></td>					
					<td><?php echo ($row['Cashbook']['payment_type'] == 'expense') ? $row['Cashbook']['payment_amount'] : ' ';?></td>
					<td><?php echo ($row['Cashbook']['payment_type'] == 'income') ? $row['Cashbook']['payment_amount'] : ' ';?></td>
				</tr>			
			<?php
			}
		}
		?>	
		</tbody>
		<tfoot>
			<tr>
				<th colspan='4' style="text-align:right">Total: </th>
				<th><?php echo (($paymentType == 'expense') or ($paymentType == '')) ? number_format($totalExpenses, 2, '.', '') : null;?></th>
				<th><?php echo (($paymentType == 'income') or ($paymentType == '')) ? number_format($totalIncome, 2, '.', '') : null;?></th>
			</tr>
			<tr>
				<th colspan='4' style="text-align:right"></th>
				<th>Expenses</th>
				<th>Income</th>
			</tr>
		</tfoot>
	</table>
<?php
}
else {
	echo 'No records found';
}
?>