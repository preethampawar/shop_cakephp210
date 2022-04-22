<?php
echo $this->element('get_category_price');

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

<?php echo $this->element('message');?>

<?php
if(empty($categories)) {
	echo 'You need to create a category before you add Sale records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<div>
	<h2 class="floatLeft">Add New Sales Record</h2>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/sales/', array('class'=>'button small red floatRight', 'escape'=>false));	?>
	<?php echo $this->Form->create('Sale', array('controller'=>'sales', 'action'=>'add'));?>		
		
	<div>	
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;text-align:center;">
			<div class="corner categorySelectionDiv">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Category/Product</b></div>
				<?php 
				if(!isset($this->data['Sale']['category_id'])) {
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
					$default = $this->data['Sale']['category_id'];	
				}				
				
				echo $this->Form->input('category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'size'=>'23', 'style'=>'border:0px solid #efefef; padding:0px; background:transparent; font-size:95%;', 'id'=>'category', 'default'=>$default, 'onchange'=>'showCategoryPrice(this.id)')); 
				?>
				<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;'));?>
			</div>			
		</div>		
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;">
			<div class="corner contentDiv">
				<?php
				$label = 'Particular/Description *';
				$placeholder = 'Enter Particular/Description';
				$type = 'text';
				$value = null;
				if($this->Session->read('Company.business_type') == 'wineshop') {
					$label = 'Invoice No.*';
					$placeholder = 'Enter Invoice No.';
					$type = 'hidden';
					$value = '-';
				}	
				echo $this->Form->input('particular', array('label'=>$label, 'required'=>true, 'placeholder'=>$placeholder, 'type'=>$type, 'value'=>$value));
				if($inventory) {
					?>
					<div id="" style="padding:0px; margin:0px;" class="">						
						<div style="width:110px; margin-right:10px;" class="floatLeft">
							<?php echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false, 'placeholder'=>'Enter Qty.', 'onchange'=>'calculateTotal()', 'id'=>'Quantity')); ?>
						</div>						
						<div style="width:110px; margin-right:0px;" class="floatRight">							
							<?php echo $this->Form->input('unitrate', array('label'=>'Unit Price ('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'UnitRate',  'onchange'=>'calculateTotal()')); ?>
						</div>
						<div class="clear" style="padding:0px; margin:0px;"></div>
					</div>		
					<?php					
				}
				else {
					echo $this->Form->input('quantity', array('type'=>'hidden', 'value'=>'1', 'required'=>false, 'id'=>'Quantity'));
				}
				echo $this->Form->input('total_amount', array('label'=>'Total Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'placeholder'=>'Enter Total Amount', 'onchange'=>'$("#PaymentAmount").val(this.value)', 'id'=>'TotalAmount')); 
				echo $this->Form->input('payment_amount', array('label'=>'Received Amount('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'PaymentAmount', 'placeholder'=>'Enter Payment Amount')); 
				
				if($isWineStore) {
					echo $this->Form->hidden('payment_method', array('value'=>'cash'));
				}
				else {
					echo $this->Form->input('payment_method', array('label'=>'Payment Method', 'options'=>Configure::read('PaymentMethods'), 'escape'=>false, 'empty'=>false, 'required'=>true));
				}
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
		<?php echo $this->Form->end();?>
</div>

<script type="text/javascript">
showCategoryPrice('category');	

$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Sale']['date'])) {
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Sale']['date'];?>" );
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

<br>
<h3>Previous Records: </h3>
<?php
if(!empty($sales)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th width='250'>Name</th>
				<th>Particular</th>
				<?php echo ($inventory) ? "<th width='50'>Qty.</th>" : null;?>
				<?php echo ($inventory) ? "<th width='100'>Unit Rate</th>" : null;?>
				<th width='120'>Total Amount</th>
				<th width='150'>Received Amount (Dr.)</th>
				<th width='120'>Pending Amount</th>				
				<th width='100'>Date(d-m-Y)</th>
				<th width="100">Action</th>	
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($sales as $row) {
				$i++;
				$title = $row['Sale']['category_name'].': '.$row['Sale']['particular'];
				$qty = ($row['Sale']['quantity'] > 0) ? $row['Sale']['quantity'] : '-';
				$unitrate = ($row['Sale']['unitrate'] > 0) ? $this->Number->currency($row['Sale']['unitrate'], $CUR) : '-';
			?>
			<tr>
				<td><?php echo $row['Sale']['category_name'];?></td>
				<td>
					<?php 
						echo $row['Sale']['particular'];
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
				<td><?php echo ($row['Sale']['total_amount'] > 0) ? $this->Number->currency($row['Sale']['total_amount'], $CUR) : '-';?></td>
				<td><?php echo ($row['Sale']['payment_amount'] > 0) ? $this->Number->currency($row['Sale']['payment_amount'], $CUR) : '-'?> </td>
				<td><?php echo ($row['Sale']['pending_amount'] > 0) ? $this->Number->currency($row['Sale']['pending_amount'], $CUR) : '-';?></td>	
				
				<td><?php echo date('d-m-Y', strtotime($row['Sale']['date']));?></td>
				
				<td>
					<?php
					$hide = false;
					if(isset($row['AvailableStock']['id']) and !empty($row['AvailableStock']['id'])) {
						$hide = true;
					}
					if(!$hide) {
						echo $this->Html->link('Edit', array('controller'=>'sales', 'action'=>'edit/'.$row['Sale']['id']), array('class'=>'button small grey', 'title'=>$title));
						echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo $this->Html->link('Delete', array('controller'=>'sales', 'action'=>'delete/'.$row['Sale']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this record. "'.$title.'" ?');
					}
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
	// echo ($showAddButton) ?  $this->Html->link('Click here to add new sales record', array('controller'=>'sales', 'action'=>'add')) : '';
}
?>