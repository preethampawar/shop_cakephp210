<?php $this->start('sales_report_menu');?>
<?php echo $this->element('sales_menu');?>
<?php echo $this->element('sales_purchases_report_menu');?>
<?php echo $this->element('stock_report_menu');?>
<?php $this->end();?>

<h1>Recent Sales List</h1>
	<?php 
	if($sales) { 
	?>
	<?php
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page '.$this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		// Shows the next and previous links
		echo '&laquo;'.$this->Paginator->prev('Prev', null, null, array('class' => 'disabled'));
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();
		
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, array('class' => 'disabled')).'&raquo;';
	?>
	<table class='table' style="width:100%;">
		<thead>
			<tr>
				<th>S.No</th>
				<th>Date</th>
				<th>Category</th>
				<th>Product</th>
				<th>No. of Units</th>
				<th>Unit Price</th>
				<th>Total Amount</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($sales as $row) {
				$i++;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Sale']['sale_date']));?></td>
				<td><?php echo $row['Sale']['category_name'];?></td>
				<td><?php echo $row['Sale']['product_name'];?></td>
				<td><?php echo $row['Sale']['total_units'];?></td>
				<td><?php echo $row['Sale']['unit_price'];?></td>
				<td><?php echo $row['Sale']['total_amount'];?></td>				
				<td>
					<form method="post" style="" name="sales_<?php echo $row['Sale']['id'];?>" id="sales_<?php echo $row['Sale']['id'];?>" action="<?php echo $this->Html->url("/sales/removeProduct/".$row['Sale']['id']);?>">						
						<a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Sale']['product_name'];?> from the list?')) { $('#sales_<?php echo $row['Sale']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</a> 
					</form>
					<?php //echo $this->Form->postLink('Remove', array('controller'=>'sales', 'action'=>'removeProduct', $row['Sale']['id']), array('title'=>'Remove product from invoice - '.$row['Sale']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Sale']['product_name'].'"?');?>				
				</td>
			</tr>
			<?php
			}
			?>			
		</tbody>
	</table>
	<?php
	if(count($sales) > 10) {
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page '.$this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';
		
		// Shows the next and previous links
		echo '&laquo;'.$this->Paginator->prev('Prev', null, null, array('class' => 'disabled'));
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();
		
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, array('class' => 'disabled')).'&raquo;';
	}
	?>
	<?php } else { ?>
	<p>- No records found.</p>
	<?php } ?>