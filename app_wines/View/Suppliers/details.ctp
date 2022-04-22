<p><?php echo $this->Html->link('Show all Suppliers', array('controller'=>'suppliers', 'action'=>'index'), array('title'=>'Go back to Suppliers list'));?></p>
<h1>
	Supplier Name: <?php echo $supplierInfo['Supplier']['name'];?> 
	<span style="font-size:11px; font-style:italic;">[<?php echo $this->Html->link('Edit', array('controller'=>'suppliers', 'action'=>'edit', $supplierInfo['Supplier']['id']), array('title'=>'Edit '.$supplierInfo['Supplier']['name']));	?>]</span>
</h1>

<h2>Invoices List</h2>
<?php 
if($supplierInvoices) { 
?>
<table class='table'>
	<thead>
		<tr>
			<th>S.No</th>
			<th>Invoice No</th>
			<th>Invoice Date</th>
			<th>Created on</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		foreach($supplierInvoices as $row) {
			$i++;			
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td>
				<?php 						
				echo $this->Html->link($row['Invoice']['name'], array('controller'=>'invoices', 'action'=>'details', $row['Invoice']['id']), array('title'=>'Invoice Details - '.$row['Invoice']['name']));						
				?></td>
			<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date']));?></td>
			<td><?php echo date('d-m-Y', strtotime($row['Invoice']['created']));?></td>
			</td>
		</tr>
		<?php
		}
		?>
		
	</tbody>
</table>

<?php } else { ?>
<p>No Invoices found for this Supplier</p>
<?php } ?>
