<?php
$inventory = false;
$wineStore = false;
$finance = false;
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
			$wineStore = true;
			break;			
		case 'finance':
			$finance = true;
			break;		
		case 'default':
			break;
	}	
}
?>

<?php echo $this->element('message');?>

<?php
if(empty($categories)) {
	echo 'You need to create a category before you add Cash records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<div>
	<h2 class="floatLeft">Add New Cash Record</h2>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/cash/', array('class'=>'button small red floatRight', 'escape'=>false));?>
	
	<?php echo $this->Form->create('Cash', array('url'=>'/cash/add'));?>		
		
	<div>	
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;text-align:center;">
			<div class="corner categorySelectionDiv">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Category</b></div>			
				<?php
				if(!isset($this->data['Cash']['category_id'])) {
					if($this->Session->check('PrevCategory')) {
						$default = $this->Session->read('PrevCategory');
					}
					else {					
						foreach($categories as $index=>$name) {
							$default = $index;
							break;
						}
					}
				}
				else {
					$default = $this->data['Cash']['category_id'];	
				}

				
				// if(!isset($this->data['Cash']['category_id'])) {
					// foreach($categories as $index=>$name) {
						// $default = $index;
						// break;
					// }
				// }
				// else {
					// $default = $this->data['Cash']['category_id'];	
				// }
				
				echo $this->Form->input('category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'size'=>'23', 'style'=>'border:0px solid #efefef; padding:0px; background:transparent; font-size:95%;', 'id'=>'category', 'default'=>$default)); 
				?>
				<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;'));?>
			</div>
		</div>		
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;">
			<div class="corner contentDiv">
				<?php		
				if($wineStore) {
					echo $this->Form->input('invoice_id', array('label'=>'Invoice no.', 'required'=>false, 'empty'=>'-'));
				}

				echo $this->Form->input('particular', array('label'=>'Particular*', 'required'=>true, 'placeholder'=>'Enter Particular/Purpose'));
				
				if(false) {
					?>
					<div id="" style="padding:0px; margin:0px;" class="">						
						<div style="width:110px; margin-right:10px;" class="floatLeft">
							<?php echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false, 'placeholder'=>'Enter Qty.', 'onchange'=>'setPurchaseTotalAmount()', 'id'=>'Quantity')); ?>
						</div>						
						<div style="width:110px; margin-right:0px;" class="floatRight">							
							<?php echo $this->Form->input('unitrate', array('label'=>'Unit Price ('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'UnitRate',  'onchange'=>'setPurchaseTotalAmount()', 'placeholder'=>'')); ?>
						</div>
						<div class="clear" style="padding:0px; margin:0px;"></div>
					</div>		
					<?php					
				}
				
				echo $this->Form->input('total_amount', array('label'=>'Total Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'placeholder'=>'Enter Total Amount', 'onchange'=>'$("#PaymentAmount").val(this.value)', 'id'=>'TotalAmount')); 
				echo $this->Form->input('payment_amount', array('label'=>'Paid/Received Amount('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'PaymentAmount', 'placeholder'=>'Enter Payment Amount')); 
				
				
				echo $this->Form->input('transaction_type', array('label'=>'Credit/Debit', 'options'=>Configure::read('TransactionTypes'), 'escape'=>false, 'empty'=>false, 'required'=>true, 'default'=>'debit'));
				$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
				echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));		
				echo $this->Form->submit('Add New Record &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;', 'div'=>array('style'=>'text-align:center;')));
				?>
			</div>
		</div>
		<div class="floatLeft" style="width:200px; float:left; text-align:center; margin-right:0px;">
			<div class="corner groupSelectionDiv">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Group</b></div>
				<div style="padding:0px; height:365px; overflow:auto; background:transparent; padding:8px; text-align:left;">
				<?php echo $this->element('select_groups', array('dataID'=>$dataID));?>
				</div>
				<?php echo $this->Html->link('+ Add New Group', '/groups/add', array('style'=>'text-decoration:none;'));?>
			</div>
		</div>	
		<div class='clear'></div>
	</div>	
	<?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Cash']['date'])) {
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Cash']['date'];?>" );
	<?php
	}
	else{
		if($this->Session->check('PrevDate')) {
			$date = $this->Session->read('PrevDate');
		}
		else {
			$date = date('Y-m-d');
		}
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $date;?>" );		
	<?php
	}	
	?>
});

</script>
<?php
}
?>


<h3>Recent entries:</h3>
<?php
if(!empty($cash)) {
?>	
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th>Category</th>
				<th>Particular</th>
				<?php echo ($inventory) ? "<th width='60'>Quantity</th>" : null;?>
				<?php echo ($inventory) ? "<th width='80'>Unit Rate</th>" : null;?>
				<th width='130'>Total Amount</th>
				<th width='130'>Amount Paid (Dr.)</th>
				<th width='150'>Amount Received (Cr.)</th>
				<th width='130'>Pending Amount</th>
				<th width='100'>Date</th>
				<th width="100">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;			
			foreach($cash as $row) {
				$i++;
				$title = $row['Cash']['category_name'].': '.$row['Cash']['particular'];		
				$qty = 	($row['Cash']['quantity']) ? $row['Cash']['quantity'] : '-';
				$unitrate = ($row['Cash']['unitrate'] > 0) ? $this->Number->currency($row['Cash']['unitrate'], $CUR) : '-';
			?>
			<tr>
				<td><?php echo $row['Cash']['category_name'];?></td>
				<td>
					<?php 
						echo $row['Cash']['particular'];
						$tmp = array();
						if(!empty($row['Group'])) {
							foreach($row['Group'] as $group) {
								$tmp[] = $group['name'];
							}
							$tmp = implode(', ', $tmp);
						}
						if($tmp) {
							echo '<br><span style="color:#ff0000; font-size:12px;">';
							echo $tmp;
							echo '</span>';
						}
					?>
				</td>				
				<?php echo ($inventory) ? "<td>$qty</td>" : null?>
				<?php echo ($inventory) ? "<td>$unitrate</td>" : null?>
				<td><?php echo ($row['Cash']['total_amount'] > 0) ? $this->Number->currency($row['Cash']['total_amount'], $CUR) : '-'; ?></td>		
				<td class="debit"><?php echo ($row['Cash']['transaction_type'] == 'debit') ? $this->Number->currency($row['Cash']['payment_amount'], $CUR) : '-';?></td>
				<td class="credit"><?php echo ($row['Cash']['transaction_type'] == 'credit') ? $this->Number->currency($row['Cash']['payment_amount'], $CUR) : '-';?></td>
				<td><?php echo ($row['Cash']['pending_amount'] > 0) ? $this->Number->currency($row['Cash']['pending_amount'], $CUR) : '-';?></td>					
				<td><?php echo date('d-m-Y', strtotime($row['Cash']['date']));?></td>
				<td>
					<?php
					$damagedStock = false;
					if(isset($row['Inventory']['id']) and !empty($row['Inventory']['id'])) {
						if($row['Inventory']['type'] == 'damaged') {
							$damagedStock = true;
						}
					}
					if(!$damagedStock) {
						echo $this->Html->link('Edit', array('controller'=>'cash', 'action'=>'edit/'.$row['Cash']['id']), array('class'=>'button small grey', 'title'=>$title));
						echo '&nbsp;&nbsp;&nbsp;';						
					}
					else {
						echo $this->Html->link('Edit', '/inventory/editDamagedStock/'.$row['Inventory']['id'].'/'.$row['Inventory']['data_id'], array('class'=>'button small grey'));
						echo '&nbsp;&nbsp;&nbsp;'; 
					}
					echo $this->Html->link('Delete', array('controller'=>'cash', 'action'=>'delete/'.$row['Cash']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this record. "'.$title.'" ?');
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
else {
	echo '&nbsp;Data Not Available.<br> <br>';	
}
?>
