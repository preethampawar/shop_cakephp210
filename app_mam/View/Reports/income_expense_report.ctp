<h1>Generate Report</h1>
<div class="clear"></div>

<div id="search" class="corner setBackground" style=" padding:10px 10px 10px 10px;">
	<?php echo $this->Form->create();?>
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">
		<?php
		$transaction_types = array('debit'=>'Expense', 'credit'=>'Income');		
		echo $this->Form->input('transaction_type', array('label'=>'Select Income/Expense', 'options'=>$transaction_types, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">
		<?php
		echo $this->Form->input('Report.category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
	
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#startdatepicker').focus()"));
		echo $this->Form->input('Report.startdate', array('label'=>'From Date (Y-m-d)', 'id'=>'startdatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select From Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#enddatepicker').focus()"));
		echo $this->Form->input('Report.enddate', array('label'=>'To Date (Y-m-d)', 'id'=>'enddatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate2" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select To Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px; margin-top:8px;">
		<?php echo $this->Form->submit('Generate Report &nbsp;&raquo;', array('escape'=>false));?>
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
	$( "#startdatepicker" ).datepicker( "option", "altFormat", "d MM, yy");	
	$( "#startdatepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Report']['startdate'])) {
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo $this->data['Report']['startdate'];?>" );
	<?php
	}
	else{
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo date('Y-m-01');?>" );	
	<?php
	}	
	?>
	
	// end date picker
	$( "#enddatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#enddatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#enddatepicker" ).datepicker( "option", "altField", "#alternate2");
	$( "#enddatepicker" ).datepicker( "option", "altFormat", "d MM, yy");	
	$( "#enddatepicker" ).datepicker( "option", "defaultDate", '');
	<?php
	if(isset($this->data['Report']['enddate'])) {
	?>
	$( "#enddatepicker" ).attr( "value", "<?php echo $this->data['Report']['enddate'];?>" );
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

<br>

<?php
if(isset($results)) {
	if(!empty($results)) {	
		$transactions['subTotalIncome'] = 0;
		$transactions['subTotalExpenses'] = 0;		
	?>
		<h2>Results: </h2>
		<b><?php echo date('d M Y', strtotime($this->data['Report']['startdate'])).' &nbsp;&nbsp;-To-&nbsp;&nbsp; '.date('d M Y', strtotime($this->data['Report']['enddate']));?> </b><br><br>
		<table cellspacing='1' cellpadding='1'>
			<thead>
				<tr>
					<th width='40'>Sl.No.</th>				
					<th width='100'>Date</th>				
					<th width='200'>Category</th>
					<th>Transaction Details</th>												
					<th width='150'>Income</th>
					<th width='150'>Expense</th>												
				</tr>
			</thead>
			<tbody>
				<?php
				$i=0;
				foreach($results as $row) {
					$i++;
					// Calculate cash transactions
					if($row['Report']['business_type'] == 'cash') {
						$link = '/cash/edit/'.$row['Report']['id'];
						
						if($row['Report']['transaction_type'] == 'debit') {											
							$transactions['subTotalExpenses'] = $transactions['subTotalExpenses']+$row['Report']['payment_amount'];									
						}
						else {							
							$transactions['subTotalIncome'] = $transactions['subTotalIncome']+$row['Report']['payment_amount'];							
						}						
					}			
					?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo date('d M Y', strtotime($row['Report']['date']));?></td>
						
						
						<td><?php echo $row['Report']['category_name'];?></td>
						<td><?php echo $this->Html->link($row['Report']['particular'], $link, array('style'=>'font-weight:normal; text-decoration:underline;'));?></td>
						<td>
							<span class="credit">
								<?php echo ($row['Report']['transaction_type'] == 'credit') ? (($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-') : '-';?>
							</span>							
						</td>
						<td>
							<span class="debit">
								<?php echo ($row['Report']['transaction_type'] == 'debit') ? (($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-') : '-';?>
							</span>							
						</td>	
					</tr>			
					<?php
				}
				?>
				<tr>
					<td colspan='4' style="text-align:right;"><b>Total Amount</b></td>
					<td><span class="credit"><b><?php echo ($transactions['subTotalIncome'] > 0) ? $this->Number->currency($transactions['subTotalIncome'], $CUR) : '-';?><b></span></td>
					<td><span class="debit"><b><?php echo ($transactions['subTotalExpenses'] > 0) ? $this->Number->currency($transactions['subTotalExpenses'], $CUR) : '-';?><b></span></td>
				</tr>
			</tbody>
		</table>
		<br><br>
		<table style="width:800px;">
			<tr>
				<th></th>
				<th>Income</th>
				<th>Expenses</th>
			</tr>
			<tr>
				<td><b>Total Amount</b></td>
				<td><span class="credit"><b><?php echo ($transactions['subTotalIncome'] > 0) ? $this->Number->currency($transactions['subTotalIncome'], $CUR) : '-';?><b></span></td>
				<td><span class="debit"><b><?php echo ($transactions['subTotalExpenses'] > 0) ? $this->Number->currency($transactions['subTotalExpenses'], $CUR) : '-';?><b></span></td>
			</tr>
		</table>
		<?php // echo $this->element('Reports/simple_report', array('transactions'=>$transactions));?>
		<br><br>
		
	<?php	
	}
	else {	
		echo '<h2>Results:  </h2><div>Data Not Available.</div>';	
	}
}
?>
