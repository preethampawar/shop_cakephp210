<?php $this->start('stock_reports_menu');?>
<?php echo $this->element('breakage_stock_menu');?>
<?php echo $this->element('stock_report_menu');?>
<?php $this->end();?>

<h1>Breakage Stock List</h1><br>
	<?php 
	if($breakages) { 
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
	<table class='table' style="width:100%">
		<thead>
			<tr>
				<th>S.No</th>
				<th><?php echo $this->Paginator->sort('breakage_date', 'Date'); ?></th>
				<th><?php echo $this->Paginator->sort('category_name', 'Category'); ?></th>
				<th><?php echo $this->Paginator->sort('product_name', 'Product'); ?></th>
				<th>Breakage Qty</th>
				<th>Unit Price</th>
				<th>Total Amount</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($breakages as $row) {
				$i++;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Breakage']['breakage_date']));?></td>
				<td><?php echo $row['Breakage']['category_name'];?></td>
				<td><?php echo $row['Breakage']['product_name'];?></td>
				<td><?php echo $row['Breakage']['total_units'];?></td>
				<td><?php echo $row['Breakage']['unit_price'];?></td>
				<td><?php echo $row['Breakage']['total_amount'];?></td>				
				<td>
					<form method="post" style="" name="invoice_remove_product_<?php echo $row['Breakage']['id'];?>" id="invoice_remove_product_<?php echo $row['Breakage']['id'];?>" action="<?php echo $this->Html->url("/breakages/removeProduct/".$row['Breakage']['id']);?>">
						<input type="submit" value="Remove" name="Remove" onclick="if (confirm('Are you sure you want to delete this product <?php echo $row['Breakage']['product_name'];?> from the list?')) { $('#invoice_remove_product_<?php echo $row['Breakage']['id'];?>').submit(); } event.returnValue = false; return false;"> 
					</form>
				
					<?php 
					//echo $this->Form->postLink('Remove', array('controller'=>'breakages', 'action'=>'removeProduct', $row['Breakage']['id']), array('title'=>'Remove product from sale - '.$row['Breakage']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Breakage']['product_name'].'"?');
					?>				
				</td>
			</tr>
			<?php
			}
			?>			
		</tbody>
	</table>
	<?php
	if(count($breakages) > 10) {
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
	<p>No products found.</p>
	<?php } ?>