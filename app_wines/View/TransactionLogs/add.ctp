<?php $this->start('employees_report_menu');?>
<?php echo $this->element('counter_balance_sheet_menu');?>
<?php echo $this->element('counter_balance_sheet_report_menu');?>
<?php echo $this->element('income_expense_report_menu');?>
<?php $this->end();?>

<h1>Add New Transaction Log</h1>

	<div id="AddTransactionLogDiv">
		<div style="float:left; width:500px;">
			<?php 
			echo $this->Form->create();
			?>
			<div id="paramsDiv">
				<?php echo $this->Form->input('payment_type', array('label'=>'Payment Type', 'required'=>true, 'type'=>'select', 'options'=> array('expense'=>'Payment Made','income'=>'Payment Received'), 'default'=>'expense'));?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('payment_date', array('label'=>'Payment Date', 'required'=>true, 'type'=>'date', 'value'=>$payment_date, 'default'=>$payment_date));?>
			</div>
			<div style="float:left; clear:both;">
				<?php echo $this->Form->input('amount', array('empty'=>false, 'label'=>'Payment Amount', 'required'=>true, 'type'=>'number', 'escape'=>false, 'default'=>0, 'style'=>'width:80px;'));?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('title', array('label'=>'Description', 'required'=>true, 'type'=>'text', 'style'=>'width:200px;'));?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('tag_id', array('label'=>'Tag', 'required'=>true, 'type'=>'select', 'options'=>$tags, 'class'=>'autoSuggest', 'default'=>$tag_id));?>
			</div>
			<div style="float:left; clear:both; padding-top:5px;">				
				&nbsp;<?php echo $this->Form->submit('Submit', array('id'=>'submit', 'type'=>'submit', 'div'=>false));?>
			</div>
			
			<br>
			
			<?php		
			echo $this->Form->end();
			?>
		</div>
		<div style="float:left; padding-left:10px; border-left:1px solid #666;"> 
			<?php 
			echo $this->Form->create('Tag', array('url'=>'/TransactionLogs/addTag'));
			echo $this->Form->input('name', array('label'=>'Add New Tag', 'required'=>true, 'type'=>'text', 'style'=>'width:150px;'));
			echo '&nbsp;'.$this->Form->submit('Create Tag', array('id'=>'submit', 'type'=>'submit', 'div'=>false));
			echo $this->Form->end();
			?>
		</div>
		<div style="clear:both;"></div>
	</div>
	<br><hr><br>
		
	<h2>Recent records</h2>
	<?php 
	if($logs) { 
	?>
	<table class='table' style="width:100%">
		<thead>
			<tr>
				<th>#</th>
				<th>Payment Date</th>
				<th>Payment Type</th>
				<th>Payment Amount</th>
				<th>Tag</th>
				<th>Description</th>
				<th>Created Date</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($logs as $row) {
				$i++;
			?>
				
			<tr>
				<td><?php echo $i;?></td>				
				<td><?php echo date('d-m-Y', strtotime($row['TransactionLog']['payment_date']));?></td>
				<td><?php echo ($row['TransactionLog']['payment_type'] == 'expense') ? 'Payment Made' : 'Payment Received';?></td>
				<td><?php echo $row['TransactionLog']['amount'];?></td>
				<td><?php echo $tags[$row['TransactionLog']['tag_id']];?></td>
				<td><?php echo $row['TransactionLog']['title'];?></td>
				<td><?php echo date('d-m-Y', strtotime($row['TransactionLog']['created']));?></td>
				
				<td>
					<form method="post" style="" name="TransactionLog_<?php echo $row['TransactionLog']['id'];?>" id="TransactionLog_<?php echo $row['TransactionLog']['id'];?>" action="<?php echo $this->Html->url("/TransactionLogs/remove/".$row['TransactionLog']['id']);?>">
						<input type="submit" value="Remove" name="Remove" onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#TransactionLog_<?php echo $row['TransactionLog']['id'];?>').submit(); } event.returnValue = false; return false;"> 
					</form>
				</td>
			</tr>
			<?php
			}
			?>			
		</tbody>
	</table>
	
	<?php } else { ?>
	<p>No logs found</p>
	<?php } ?>
	
