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
<script type="text/javascript">
var isPurchase = true;	
var isSale = false;	
var isCash = false;	
var isWineStore = <?php echo ($isWineStore) ? 'true' : 'false';?>; 
</script>

<?php echo $this->element('message');?>
<?php // echo $this->element('get_category_case_quantity');?>
<?php echo $this->element('getCategoryInfoInJavascript');?>

<?php
if(empty($categories)) {
	echo 'You need to create a category before you add Purchase records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<div>
	<h2 class="floatLeft">Add New Purchase Record</h2>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/purchases/', array('class'=>'button small red floatRight', 'escape'=>false));?>
	<?php echo $this->Form->create('Purchase', array('controller'=>'purchases', 'action'=>'add'));?>		
		
	<div>	
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;text-align:center;">
			<div class="corner categorySelectionDiv">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Category/Product</b></div>
			
				<?php 
				if(!isset($this->data['Purchase']['category_id'])) {
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
					$default = $this->data['Purchase']['category_id'];	
				}
				
				$onchange = null;
				if($inventory) {
					$onchange = 'setSelectedCategory(this.value); setPurchaseFormFields();';
				}
				
				echo $this->Form->input('category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'size'=>'23', 'style'=>'border:0px solid #efefef; padding:0px; background:transparent; font-size:95%;', 'id'=>'category', 'default'=>$default, 'onchange'=>$onchange)); 
				?>
				<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;'));?>
			</div>
		</div>		
		<div class="floatLeft" style="width:350px; float:left; margin-right:20px;">
			<div class="corner contentDiv">				
				<?php		
				$label = 'Particular/Description *';
				$placeholder = 'Enter Particular/Description';
				if($this->Session->read('Company.business_type') == 'wineshop') {
					// $label = 'Invoice No.*';
					// $placeholder = 'Enter Invoice No.';
					echo $this->Form->input('invoice_id', array('label'=>'Invoice no.', 'required'=>false, 'empty'=>'-'));
				}	
				echo $this->Form->input('particular', array('label'=>$label, 'required'=>true, 'placeholder'=>$placeholder));
				
				if($inventory) {
					if(!$isWineStore) {
					?>
					<div id="" style="padding:0px; margin:0px;" class="">						
						<div style="width:130px; margin-right:10px;" class="floatLeft">
							<?php echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false, 'placeholder'=>'Enter Qty.', 'onchange'=>'calculatePurchaseAmount()', 'id'=>'Quantity')); ?>
						</div>						
						<div style="width:130px; margin-right:0px;" class="floatRight">							
							<?php echo $this->Form->input('unitrate', array('label'=>'Unit Price ('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'UnitRate',  'onchange'=>'calculatePurchaseAmount()', 'placeholder'=>'')); ?>
						</div>
						<div class="clear" style="padding:0px; margin:0px;"></div>
					</div>		
					<?php
					}
					else {
					?>
					<div id="" style="padding:0px; margin:0px;" class="">	
						<div style="width:100px; margin-right:10px;" class="floatLeft">							
							<?php
							$options = array();	
							for($i=1; $i<=1000;$i++) {
								$options[$i] = $i;
							}
							echo $this->Form->input('no_of_cases', array('label'=>'No. of Cases', 'id'=>'CaseQty', 'options'=>$options, 'empty'=>false, 'onchange'=>'setPurchaseFormFields()'));
							?>
						</div>
						
						<div style="width:50px; margin-right:10px;" class="floatLeft">
							<?php echo $this->Form->input('quantity', array('label'=>'Qty.', 'required'=>false, 'class'=>'readonly', 'id'=>'Quantity', 'readonly'=>true)); ?>
						</div>						
						
						<div style="width:120px; margin-right:0px;" class="floatRight">							
							<?php echo $this->Form->input('unitrate', array('label'=>'Unit Price/Case', 'required'=>false, 'id'=>'UnitRate',  'onchange'=>'calculatePurchaseAmount()', 'placeholder'=>'')); ?>
						</div>
						
						<div class="clear" style="padding:0px; margin:0px;"></div>
					</div>		
					<?php
					}
				}
				
				echo $this->Form->input('total_amount', array('label'=>'Total Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'placeholder'=>'Enter Total Amount', 'onchange'=>'$("#PaymentAmount").val(this.value)', 'id'=>'TotalAmount')); 
				echo $this->Form->input('payment_amount', array('label'=>'Paid Amount('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'PaymentAmount', 'placeholder'=>'Enter Payment Amount', 'id'=>'PaymentAmount')); 
				
				if($isWineStore) {
					echo $this->Form->hidden('payment_method', array('value'=>'cash'));
				}
				else {
					echo $this->Form->input('payment_method', array('label'=>'Payment Method', 'options'=>Configure::read('PaymentMethods'), 'escape'=>false, 'empty'=>false, 'required'=>true));
				}
				
				$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
				echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent; font-weight:85%;padding:0; margin:0;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));		
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
	<?php	echo $this->Form->end();?>
</div>

<script type="text/javascript">
<?php
if(!isset($this->data['Purchase']) and ($inventory)) {
?>
	categoryid = $('#category').val();
	setSelectedCategory(categoryid);
	setPurchaseFormFields();	
<?php	
}
?>


$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Purchase']['date'])) {		
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Purchase']['date'];?>" );
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
<h3>Previous Records:</h3>
<?php
if(!empty($purchases)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th width='225'>Category</th>
				<th>Particular</th>
				<?php 
				echo ($inventory) ? "<th width='50'>Qty.</th>" : null;				
				if($isWineStore) {
					echo ($inventory) ? "<th width='50'>No.of.Cases</th>" : null;				
					echo ($inventory) ? "<th width='100'>Price/Case</th>" : null;						
				}
				else {
					echo ($inventory) ? "<th width='100'>Unit Rate</th>" : null;
				}
				?>
				<th width='120'>Total Amount</th>
				<th width='120'>Paid Amount (Dr.)</th>
				<th width='120'>Pending Amount</th>
				
				<th width='100'>Date(d-m-Y)</th>
				<th width="100">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($purchases as $row) {
				$i++;
				$title = $row['Purchase']['category_name'].': '.$row['Purchase']['particular'];
				$qty = ($row['Purchase']['quantity'] > 0) ? $row['Purchase']['quantity'] : '-';
				$no_of_cases = ($row['Purchase']['no_of_cases'] > 0) ? $row['Purchase']['no_of_cases'] : '0';				
				$unitrate = ($row['Purchase']['unitrate'] > 0) ? $this->Number->currency($row['Purchase']['unitrate'], $CUR, array('after'=>false)) : '-';
				$price_per_case = ($row['Purchase']['price_per_case'] > 0) ? $this->Number->currency($row['Purchase']['price_per_case'], $CUR, array('after'=>false)) : '-';
			?>
			<tr>
				<td><?php echo $row['Purchase']['category_name'];?></td>
				<td><?php 
						echo $row['Purchase']['particular'];						
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
				<?php 
					echo ($inventory) ? "<td>$qty</td>" : null;
					if($isWineStore) {
						echo ($inventory) ? "<td>$no_of_cases</td>" : null;
						echo ($inventory) ? "<td>$price_per_case</td>" : null;
					
					}
					else {
						echo ($inventory) ? "<td>$unitrate</td>" : null;
					}
				?>
				<td><?php echo ($row['Purchase']['total_amount'] > 0) ? $this->Number->currency($row['Purchase']['total_amount'], $CUR) : '-'; ?></td>
				<td><?php echo ($row['Purchase']['payment_amount'] > 0) ? $this->Number->currency($row['Purchase']['payment_amount'], $CUR) : '-'?> </td>
				<td><?php echo ($row['Purchase']['pending_amount'] > 0) ? $this->Number->currency($row['Purchase']['pending_amount'], $CUR) : '-';?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Purchase']['date']));?></td>
								
				<td>
					<?php
					echo $this->Html->link('Edit', array('controller'=>'purchases', 'action'=>'edit/'.$row['Purchase']['id']), array('class'=>'button small grey', 'title'=>$title));
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
					echo $this->Html->link('Delete', array('controller'=>'purchases', 'action'=>'delete/'.$row['Purchase']['id']), array('class'=>'button small grey'), 'Are you sure you want to delete this record. "'.$title.'" ?');
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