<h2>Invoices List</h2>
<?php
if(!empty($invoices)) {
?>
<script type="text/javascript">
function openInvoiceDetailsWindow(invoiceID) {
	window.open('<?php echo $this->Html->url('/invoices/details/', true);?>'+invoiceID, '_blank', 'width=800,height=400,menubar=yes,location=no,resizable=yes,scrollbars=yes');
	return false;
}
</script>

<table style="width:700px;">
	<thead>
		<tr>
			<th style="width:50px;">No.</th>
			<th>Invoice No.</th>
			<th style="width:100px;">Date</th>
			<th style="width:250px;">Actions</th>
		</tr>
	</thead>	
	<tbody>
		<?php
		$k=0;	
		foreach($invoices as $row) {
			$k++;
		?>
		<tr>
			<td><?php echo $k;?></td>
			<td><?php echo $row['Invoice']['name'];?></td>
			<td><?php echo date('d-m-Y', strtotime($row['Invoice']['invoice_date']));?></td>
			<td>
				<?php
				echo $this->Html->link('Details', '/invoices/details/'.$row['Invoice']['id'], array('onclick'=>"return openInvoiceDetailsWindow('".$row['Invoice']['id']."')"));
				
				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
				echo $this->Html->link('Edit', '/invoices/edit/'.$row['Invoice']['id']);
				
				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
				echo $this->Html->link('Delete', '/invoices/delete/'.$row['Invoice']['id'], array(), 'Are you sure you want to delete this Invoice?');
				?>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<?php
}
?>