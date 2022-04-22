<?php
$inventory = false;
$isWineStore = false;
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
			$isWineStore = true;
			break;			
		case 'finance':
			break;		
		case 'default':
			break;
	}	
}
?>

<?php
if(!empty($datalogs)) {
?>
<br>
<h2>History</h2>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Modified On</th>
				<th>Category</th>
				<th>Particular</th>
				<?php echo ($inventory) ? "<th width='60'>Quantity</th>" : null;?>
				<th>Total Amount</th>
				<th>Dr.</th>
				<th>Cr.</th>
				<th>Pending Amount</th>				
				<th>Dated</th>
				<th>Message</th>				
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;			
			foreach($datalogs as $row) {
			$i++;			
			?>
			<tr>
				<td width='50'><?php echo $i;?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Datalog']['created']));?></td>
				<td><?php echo $row['Datalog']['category_name'];?></td>
				<td><?php echo $row['Datalog']['particular'];?></td>
				<?php echo ($inventory) ? "<td>{$row['Datalog']['quantity']}</td>" : null?>
				<td><?php echo $row['Datalog']['total_amount'];?></td>
				
				<td class="debit"><?php echo ($row['Datalog']['transaction_type'] == 'debit') ? $this->Number->currency($row['Datalog']['payment_amount'], $CUR) : '-';?></td>
				<td class="credit"><?php echo ($row['Datalog']['transaction_type'] == 'credit') ? $this->Number->currency($row['Datalog']['payment_amount'], $CUR) : '-';?></td>				
				<td><?php echo $this->Number->currency($row['Datalog']['pending_amount'], $CUR);?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Datalog']['date']));?></td>
				<td><?php echo $row['Datalog']['message'];?></td>				
			</tr>
			<?php
			}			
			?>			
		</tbody>
	</table>
<?php	
}
?>
<br><br>