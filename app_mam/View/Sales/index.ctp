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

<?php echo $this->element('message');?>

<?php echo $this->Html->link('+ Add New Sales Record', array('controller'=>'sales', 'action'=>'add'), array('class'=>'button medium grey floatRight'));?>
<h1>Sales Book</h1>
<div class="clear"></div>
<br>

<div id="search" class="corner setBackground" style=" padding:10px;">
	<?php echo $this->Form->create('Sale', array('url'=>$this->params['named']));?>
	<div class="floatLeft" style="width:300px; padding:0px; margin-right:10px;">
		<?php		
		echo $this->Form->input('Sale.category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>		
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#startdatepicker').focus()"));
		echo $this->Form->input('Sale.startdate', array('label'=>'From Date', 'id'=>'startdatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate" style="border:0px solid #fff; color:#FF0000; background-color:transparent; font-size:11px;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select From Date', 'style'=>'width:100px;'));
		?>
	</div>	
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#enddatepicker').focus()"));
		echo $this->Form->input('Sale.enddate', array('label'=>'To Date', 'id'=>'enddatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate2" style="border:0px solid #fff; color:#FF0000; background-color:transparent; font-size:11px;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select To Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px; margin-top:8px;">
		<?php echo $this->Form->submit('Search &nbsp;&raquo;', array('escape'=>false));?>
	</div>
	
	<?php echo $this->Form->end();?>
	<div class="clear"></div>
</div>
<script type="text/javascript">
$(function() {
	// start date picker
	$( "#startdatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#startdatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#startdatepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#startdatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
	$( "#startdatepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Sale']['startdate'])) {
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo $this->data['Sale']['startdate'];?>" );
	<?php
	}
	else{
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>
	
	// end date picker
	$( "#enddatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#enddatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#enddatepicker" ).datepicker( "option", "altField", "#alternate2");
	$( "#enddatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
	$( "#enddatepicker" ).datepicker( "option", "defaultDate", '');
	<?php
	if(isset($this->data['Sale']['enddate'])) {
	?>
	$( "#enddatepicker" ).attr( "value", "<?php echo $this->data['Sale']['enddate'];?>" );
	<?php
	}
	else{
	?>
	$( "#enddatepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>
});

</script>




<?php
if(!empty($sales)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<!-- <th>Sl.No.</th> -->
				<th width='200'>Category</th>
				<th>Particular</th>
				<?php 
				echo ($inventory) ? "<th width='50'>Qty.</th>" : null;				
				echo ($inventory) ? "<th width='100'>Unit Rate</th>" : null;				
				?>				
				<th width='120'>Total Amount.</th>
				<th width='120'>Paid Amount (Dr.)</th>
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
				<!-- <td width='50'><?php echo $i;?></td> -->
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
							//echo ($tmp) ? '<br>('.$tmp.')' : '';
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
	<br>
	<?php if($pagination) { ?>
	<?php echo $this->Paginator->prev(' << ' . __('previous'), array(), null, array('class' => 'prev disabled'));?>
	&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->numbers();?>&nbsp;&nbsp;&nbsp;
	<?php echo $this->Paginator->next(__('next').' >>' , array(), null, array('class' => 'next disabled'));?>
	<?php } ?>
<?php	
}
else {
	echo '&nbsp;Data Not Available.<br> <br>';
	// echo ($showAddButton) ?  $this->Html->link('Click here to add new sales record', array('controller'=>'sales', 'action'=>'add')) : '';
}
?>

