<?php $this->start('invoices_report_menu');?>
<?php echo $this->element('invoices_menu');?>
<?php echo $this->element('sales_purchases_report_menu');?>
<?php $this->end();?>

<article>
	<header><h1>List of Invoices</h1></header>	
<?php 
if(!empty($invoices)) {
?>
	<table class='table' style="width:100%;">
		<thead>
			<tr>
				<th>#</th>
				<th>Invoice No.</th>
				<th>Invoice Value</th>
				<th>MRP Rounding Up</th>
				<th>Net Invoice Value</th>
				<th>DD Amount</th>
				<th>Invoice Date</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k=0;
			foreach($invoices as $row) {
				$k++;
				$invoiceTax = $row['Invoice']['tax'];
				$invoice_amt = 0;
				if(isset($invoiceAmount[$row['Invoice']['id']])) {
					$invoice_amt = number_format(($invoiceAmount[$row['Invoice']['id']] + $invoiceTax), '2', '.', '');
				}
			?>
			<tr>
				<td><?php echo $k;?></td>
				<td style="width:150px;">
					<?php 						
						echo $this->Html->link($row['Invoice']['name'], array('controller'=>'invoices', 'action'=>'selectInvoice', $row['Invoice']['id']), array('title'=>'Add/Remove products in this invoice - '.$row['Invoice']['name']));						
					?>
				</td>			
				<td><?php echo $row['Invoice']['invoice_value'];?></td>	
				<td><?php echo number_format($row['Invoice']['mrp_rounding_off'], '2', '.', '');;?></td>					
				<td><?php echo $row['Invoice']['invoice_value']+$row['Invoice']['mrp_rounding_off'];?></td>	
				<td><?php echo $row['Invoice']['dd_amount'];?></td>					
				
				<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date']));?></td>
				<td style="width:220px; text-align:center;">
					<form method="post" style="" name="invoice_remove_product_<?php echo $row['Invoice']['id'];?>" id="invoice_remove_product_<?php echo $row['Invoice']['id'];?>" action="<?php echo $this->Html->url("/invoices/Delete/".$row['Invoice']['id']);?>">
						<div class="btn-group btn-group-justified" role="group" aria-label="Justified button group"> 
							
							<?php 						
								echo $this->Html->link('Details', array('controller'=>'invoices', 'action'=>'details', $row['Invoice']['id']), array('title'=>'Invoice Details - '.$row['Invoice']['name'], 'class'=>'btn btn-default btn-xs', 'role'=>'button'));						
							?>
							<?php 
								echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit', array('controller'=>'invoices', 'action'=>'edit', $row['Invoice']['id']), array('title'=>'Edit '.$row['Invoice']['name'], 'class'=>'btn btn-default btn-xs', 'role'=>'button', 'escape'=>false));	
							?> 
							<a href="javascript:return false;" onclick="if (confirm('Deleting this invoice will remove all the products associated with it.\n\nAre you sure you want to delete this invoice <?php echo $row['Invoice']['name'];?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Invoice']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-default btn-xs" role="button">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete
							</a>
							
						</div>	
						
						<?php //echo $this->Form->postLink('Delete', array('controller'=>'invoices', 'action'=>'Delete', $row['Invoice']['id']), array('title'=>'Remove Invoice - '.$row['Invoice']['name']), 'Deleting this invoice will remove all the products associated with it.\nAre you sure you want to delete this Invoice - "'.$row['Invoice']['name'].'" ?');	?>
					</form>
				</td>	
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
<?php
}
else {
?>
	<p>No Invoices Found</p>
<?php
}
?>
	
</article>