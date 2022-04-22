<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th>Product Name</th>
				<th>Opening Stock</th>
				<th>Closing Stock</th>
				<th>Sale Stock</th>
				<th>Unitrate</th>
				<th>Total Amount</th>
				<th>Received Amount</th>
				<th width='80'>Date(d-m-Y)</th>
				<th width="60">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($availableStock as $row) {
				$i++;				
			?>
			<tr>
				<td><?php echo $row['Category']['name'];?></td>
				<td><?php echo $row['AvailableStock']['stockinhand'];?></td>
				<td><?php echo $row['AvailableStock']['available_quantity'];?></td>
				<td><?php echo $row['AvailableStock']['quantity'];?></td>
				<td><?php echo  $this->Number->currency($row['AvailableStock']['unitrate'], $CUR);?></td>
				<td><?php echo  $this->Number->currency($row['AvailableStock']['total_amount'], $CUR);?></td>
				<td><?php echo  $this->Number->currency($row['AvailableStock']['payment_amount'], $CUR);?></td>
				<td><?php echo date('d-m-Y', strtotime($row['AvailableStock']['date']));?></td>				
				<td>
					<?php					
						echo $this->Html->link('Delete', array('controller'=>'available_stock', 'action'=>'delete/'.$row['AvailableStock']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this entry?');					
					?>
				</td>				
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>