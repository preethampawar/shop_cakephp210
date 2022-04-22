<?php
$inventory = false;
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
			break;			
		case 'finance':
			break;		
		case 'default':
			break;
	}	
}
?>

<?php 
echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom', false);
echo $this->Html->script('jquery-ui-1.8.18.custom.min', array('inline'=>false));
?>

<h1>Generate Report</h1>
<div class="clear"></div>

<div id="search" class="corner setBackground" style=" padding:10px;">
	<?php echo $this->Form->create();?>
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">
		<?php		
		echo $this->Form->input('pending_payment', array('label'=>'Show Pending Payments', 'escape'=>false, 'type'=>'checkbox'));
		?>
	</div>
	<div class="clear"></div>
	
	<?php
	if($this->Session->read('UserCompany.user_level') != '2') {
	?>
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">		
		<?php
		echo $this->Form->input('user_id', array('label'=>'Select User', 'div'=>true, 'options'=>$users, 'escape'=>false, 'empty'=>' - All - ', 'required'=>false));
		?>
	</div>
	<?php
	}
	?>		
	
	
	<div class="floatLeft" style="width:100px; padding:0px; margin-right:10px;">
		<?php
		$options = Configure::read('BusinessTypes');	
		echo $this->Form->input('business_type', array('label'=>'Select Book', 'options'=>$options, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
	
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">
		<?php
		// $options = array();
		// if(!empty($categories)) {		
			// foreach($categories as $row) {
				// $options[$row['Report']['category_name']] = $row['Report']['category_name'];
			// }
			// asort($options);
		// }
		// echo $this->Form->input('Report.category_name', array('label'=>'Select Category', 'options'=>$options, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		echo $this->Form->input('Report.category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
		
	<div class="floatLeft" style="width:160px; padding:0px; margin-right:10px;">		
		<?php
		echo $this->Form->input('group_id', array('label'=>'Select Group', 'div'=>true, 'options'=>$groups, 'escape'=>false, 'empty'=>' - All - ', 'required'=>false));
		?>
	</div>		
	<!--
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$options = Configure::read('PaymentMethods');	
		echo $this->Form->hidden('payment_method', array('label'=>'Payment Method', 'options'=>$options, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>
	-->
	
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#startdatepicker').focus()"));
		echo $this->Form->input('Report.startdate', array('label'=>'From Date', 'id'=>'startdatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select From Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:10px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#enddatepicker').focus()"));
		echo $this->Form->input('Report.enddate', array('label'=>'To Date', 'id'=>'enddatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate2" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select To Date', 'style'=>'width:100px;'));
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
	$( "#startdatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
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
	$( "#enddatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
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
		
		$transactions['totalIncome'] = 0;
		$transactions['totalExpenses'] = 0;
		$transactions['subTotalIncome'] = 0;
		$transactions['subTotalExpenses'] = 0;
		$transactions['totalSales'] = 0;
		$transactions['totalPurchases'] = 0;
		$transactions['totalCashCredit'] = 0;
		$transactions['totalCashDebit'] = 0;	
		$transactions['pendingIncome'] = 0;
		$transactions['pendingExpenses'] = 0;
		$transactions['pendingSales'] = 0;
		$transactions['pendingPurchases'] = 0;
		$transactions['pendingCashCredit'] = 0;
		$transactions['pendingCashDebit'] = 0;
		
		
	?>
		<h2>Results: </h2>
		<b><?php echo date('d M Y', strtotime($this->data['Report']['startdate'])).' &nbsp;&nbsp;-To-&nbsp;&nbsp; '.date('d M Y', strtotime($this->data['Report']['enddate']));?> </b><br><br>
		<table cellspacing='1' cellpadding='1'>
			<thead>
				<tr>
					<th width='40'>Sl.No.</th>				
					<th width='75'>Date</th>				
					<th width='70'>Acc. Book</th>
					
					<th width='100'>Group</th>
					<th width='200'>Category</th>
					<th>Particular/Purpose</th>							
					<?php echo ($inventory) ? "<th width='50'>Qty</th>" : null;?>
					<th width='110'>Total Amount</th>
					<th width='200'>
						<div class="floatLeft" style="width:110px;">Paid (Dr.)</div>
						<div class="floatLeft">Received (Cr.)</div>
						<div class="clear"></div>			
					</th>			
					<th width='120'>Pending Payment</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=0;
				
				
				foreach($results as $row) {
					$i++;			
					
					if($row['Report']['transaction_type'] == 'debit') {					
						$transactions['totalExpenses'] = $transactions['totalExpenses']+$row['Report']['total_amount'];
						$transactions['subTotalExpenses'] = $transactions['subTotalExpenses']+$row['Report']['payment_amount'];
						$transactions['pendingExpenses'] = $transactions['pendingExpenses']+$row['Report']['pending_amount'];					
					}
					else {
						$transactions['totalIncome'] = $transactions['totalIncome']+$row['Report']['total_amount'];
						$transactions['subTotalIncome'] = $transactions['subTotalIncome']+$row['Report']['payment_amount'];
						$transactions['pendingIncome'] = $transactions['pendingIncome']+$row['Report']['pending_amount'];
					}
					
					// Calculate sales, purchases, cash transactions and pending payments
					if($row['Report']['business_type'] == 'cash') {
						$link = '/cash/edit/'.$row['Report']['id'];
						if($row['Report']['transaction_type'] == 'credit') {
							$transactions['totalCashCredit'] = $transactions['totalCashCredit']+$row['Report']['total_amount'];
							$transactions['pendingCashCredit'] = $transactions['pendingCashCredit']+$row['Report']['pending_amount'];
						}
						if($row['Report']['transaction_type'] == 'debit') {
							$transactions['totalCashDebit'] = $transactions['totalCashDebit']+$row['Report']['total_amount'];
							$transactions['pendingCashDebit'] = $transactions['pendingCashDebit']+$row['Report']['pending_amount'];
						}
					}				
					if($row['Report']['business_type'] == 'sale') {
						$link = '/sales/edit/'.$row['Report']['id'];
						$transactions['totalSales'] = $transactions['totalSales'] + $row['Report']['total_amount'];
						$transactions['pendingSales'] = $transactions['pendingSales']+$row['Report']['pending_amount'];
					}				
					if($row['Report']['business_type'] == 'purchase') {
						$link = '/purchases/edit/'.$row['Report']['id'];
						$transactions['totalPurchases'] = $transactions['totalPurchases'] + $row['Report']['total_amount'];
						$transactions['pendingPurchases'] = $transactions['pendingPurchases']+$row['Report']['pending_amount'];
					}				
					?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo date('d M Y', strtotime($row['Report']['date']));?></td>
						<td>
							<?php 
								$options = Configure::read('BusinessTypes');
								echo $options[$row['Report']['business_type']];
							?>
						</td>
						
						<td>
							<?php
							$inGroups = array();
							if(!empty($row['DataGroup'])) {
								foreach($row['DataGroup'] as $groupData) {
									if(isset($groupData['Group']['name'])) {								
										$inGroups[] = $groupData['Group']['name'];
									} 
								}
							}
							echo implode(', ', $inGroups);
							?>
						</td>
						<td><?php echo $row['Report']['category_name'];?></td>
						<td><?php echo $this->Html->link($row['Report']['particular'], $link, array('style'=>'font-weight:normal; text-decoration:underline;'));?></td>
						<?php echo ($inventory) ? "<td>{$row['Report']['quantity']}</td>" : null?>
						<td>
							<?php echo ($row['Report']['total_amount']) ? $this->Number->currency($row['Report']['total_amount'], $CUR) : '-';?>
						</td>				
						
						<td>
							<div class="floatLeft" style="width:110px;">
								<?php
								if($row['Report']['transaction_type'] == 'debit') {
								?>
								<span class="debit">
									<?php echo ($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-';?>
								</span>
								<?php
								}
								?>
								&nbsp;
							</div>
							<div class="floatLeft">
								<?php
								if($row['Report']['transaction_type'] == 'credit') {
								?>
								<span class="credit">
									<?php echo ($row['Report']['payment_amount'] > 0) ? $this->Number->currency($row['Report']['payment_amount'], $CUR) : '-';?>
								</span>
								<?php
								}
								?>
							</div>
							<div class="clear"></div>						
						</td>								
						<td>							
							<?php							
								if($row['Report']['transaction_type'] == 'credit') {
									echo "<span class='credit'>";
									echo ($row['Report']['pending_amount'] > 0) ? $this->Number->currency($row['Report']['pending_amount'], $CUR) : '-';
									echo "</span>";
								}
								else{
									echo "<span class='debit'>";
									echo ($row['Report']['pending_amount'] > 0) ? $this->Number->currency($row['Report']['pending_amount'], $CUR) : '-';
									echo "</span>";
								}
							?> 
						</td>
					</tr>			
					<?php
				}
				?>
				
			</tbody>
		</table>
		<br><br>
		
		<?php echo $this->element('Reports/simple_report', array('transactions'=>$transactions));?>
		<br><br>
		
	<?php	
	}
	else {	
		echo '<h2>Results:  </h2><div>Data Not Available.</div>';	
	}
}
?>
