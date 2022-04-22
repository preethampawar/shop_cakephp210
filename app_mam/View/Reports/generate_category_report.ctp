<?php 
echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom', false);
echo $this->Html->script('jquery-ui-1.8.18.custom.min', array('inline'=>false));
?>
<style type="text/css">
form div {
    margin-bottom: 10px;
    padding: 0px;
}
</style>

<h1>Category :: <?php echo $categoryInfo['Category']['name'];?></h1>
<div class="clear"></div>
<br>
<div id="search">
<?php echo $this->Form->create();?>
	<div class="floatLeft" style="width:200px;">
		
		<?php
		echo $this->Form->input('pending_payment', array('label'=>'Pending Payment', 'required'=>false, 'div'=>false, 'type'=>'checkbox', 'before'=>''));
		?>
		
	</div>
	<div class="clear"></div>
	
	<div class="floatLeft" style="width:200px;">
		<?php
		$options = Configure::read('BusinessTypes');	
		echo $this->Form->input('business_type', array('label'=>'Select Account', 'div'=>false, 'options'=>$options, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
	
	<div class="floatLeft" style="width:10px;">&nbsp;</div>
	<div class="floatLeft" style="width:200px;">
		<?php
		$options = array();
		if(!empty($categories)) {		
			foreach($categories as $row) {
				$options[$row['Report']['category_name']] = $row['Report']['category_name'];
			}
			asort($options);
		}
		echo $this->Form->input('Report.category_name', array('label'=>'Select Child Category', 'div'=>false, 'options'=>$options, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
		
	<div class="floatLeft" style="width:10px;">&nbsp;</div>
	<div class="floatLeft" style="width:200px;">
		<?php
		$options = Configure::read('PaymentMethods');	
		echo $this->Form->input('payment_method', array('label'=>'Select Payment Method', 'div'=>false, 'options'=>$options, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
	
	<div class="floatLeft" style="width:10px;">&nbsp;</div>	
	<div class="floatLeft" style="width:200px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#startdatepicker').focus()"));
		echo $this->Form->input('Report.startdate', array('label'=>'Start Date', 'div'=>false, 'id'=>'startdatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:blue; background-color:#fff;" disabled="disabled">', 'readonly'=>true, 'placeholder'=>'Select Start Date', 'style'=>'width:150px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:10px;">&nbsp;</div>	
	<div class="floatLeft" style="width:200px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#enddatepicker').focus()"));
		echo $this->Form->input('Report.enddate', array('label'=>'End Date', 'div'=>false, 'id'=>'enddatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate2" style="border:0px solid #fff; color:blue; background-color:#fff;" disabled="disabled">', 'readonly'=>true, 'placeholder'=>'Select End Date', 'style'=>'width:150px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:200px;">
		<?php echo $this->Form->submit('Generate Report &nbsp;&raquo;', array('escape'=>false, 'div'=>true));?>
	</div>
	
	<div class="clear"></div>
<?php echo $this->Form->end();?>
</div>


<script type="text/javascript">
$(function() {
	// start date picker
	$( "#startdatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#startdatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#startdatepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#startdatepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
	$( "#startdatepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Report']['startdate'])) {
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo $this->data['Report']['startdate'];?>" );
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
	$( "#enddatepicker" ).datepicker( "option", "altFormat", "DD, d M, yy");	
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


<?php echo (isset($thisMonth) and !empty($thisMonth)) ? '<h2>'.$categoryInfo['Category']['name'].' report, '.$thisMonth.'</h2>' : ''; ?>
<?php
if(!empty($results)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th width='40'>Sl.No.</th>				
				<th width='100'>Date</th>				
				<th width='100'>Account</th>
				<th width='100'>Payment Method</th>
				<th width='150'>Category</th>
				<th>Particular</th>			
				
				<th width='150'>Dr.</th>
				<th width='150'>Cr.</th>
					
				<th width='150'>Pending Payment</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			
			
			$amountDebit = 0;
			$amountCredit = 0;
			$amountPendingDebit = 0;
			$amountPendingCredit = 0;
			
			
			
			foreach($results as $row) {
				$i++;			
				
				$amountDebit+=($row['Report']['transaction_type'] == 'debit') ? $row['Report']['amount'] : 0;
				$amountCredit+=($row['Report']['transaction_type'] == 'credit') ? $row['Report']['amount'] : 0;
				
				$amountPendingDebit+=(($row['Report']['pending_payment'] == '1') and ($row['Report']['transaction_type'] == 'debit')) ? $row['Report']['pending_amount'] : 0;
				$amountPendingCredit+=(($row['Report']['pending_payment'] == '1') and ($row['Report']['transaction_type'] == 'credit')) ? $row['Report']['pending_amount'] : 0;
				
				?>
				<tr>
					<td><?php echo $i;?></td>
					<td><?php echo date('d M Y', strtotime($row['Report']['date']));?></td>
					<td><?php echo $row['Report']['business_type'];?></td>
					<td><?php echo $row['Report']['payment_method'];?></td>
					<td><?php echo $row['Report']['category_name'];?></td>
					<td><?php echo $row['Report']['particular'];?></td>
					<td>
						<span class='debit'><?php echo ($row['Report']['transaction_type'] == 'debit') ? $this->Number->currency($row['Report']['amount'], $CUR) : '';	?></span>
					</td>
					<td>	
						<span class='credit'><?php echo ($row['Report']['transaction_type'] == 'credit') ? $this->Number->currency($row['Report']['amount'], $CUR) : '';	?></span> 					
					</td>					
					<td>
						<?php
							if(($row['Report']['pending_payment'] == '1') and ($row['Report']['transaction_type'] == 'credit')) {
								echo "<span class='credit'>".$this->Number->currency($row['Report']['pending_amount'], $CUR)."</span>";
							}
							elseif(($row['Report']['pending_payment'] == '1') and ($row['Report']['transaction_type'] == 'debit')) {
								echo "<span class='debit'>".$this->Number->currency($row['Report']['pending_amount'], $CUR)."</span>";
							}
						?> 
					</td>
				</tr>			
				<?php
			}
			?>
			<tr>
				<td colspan='6' style="text-align:right; font-weight:bold;">Total</td>
				<td class="debit"><?php echo $this->Number->currency($amountDebit, $CUR);?></td>
				<td class="credit"><?php echo $this->Number->currency($amountCredit, $CUR);?></td>
				<td>
					<h4 class="debit">Payable: <?php echo $this->Number->currency($amountPendingDebit, $CUR);?></h4>
					<h4 class="credit">Recievable: <?php echo $this->Number->currency($amountPendingCredit, $CUR);?></h4>
				</td>
			</tr>
		</tbody>
	</table>
	<br><br>
	<?php
	$amt = $amountCredit-$amountDebit;
	$class = ($amt > 0) ? 'credit' : 'debit';
	?>
	<h3 class="">Account Balance: <span class="<?php echo $class;?>"><b><?php echo $this->Number->currency($amt, $CUR, array('negative'=>'(-) '));?></b></span></h3>
	
<?php	
}
else {
	if(isset($this->data['Report']['business_type'])) {
		echo 'No Records Found<br>';
	}
}
?>
