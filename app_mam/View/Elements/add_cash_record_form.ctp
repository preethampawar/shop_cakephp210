
<?php

App::uses('Category', 'Model');
$this->Category = new Category;
$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;');
		
if(empty($categories)) {
	echo 'You need to create a category before you add records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
	<?php echo $this->Form->create(null, array('controller'=>'cash', 'action'=>'add'));	?>
	
			<?php
			echo $this->Form->input('Cash.category_id', array('label'=>'Select Category', 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'required'=>true));
			echo $this->Form->input('Cash.particular', array('label'=>'Particular*', 'required'=>true));
			echo $this->Form->input('Cash.amount', array('label'=>'Amount Paid*', 'required'=>true)); 
			echo $this->Form->input('Cash.pending_payment', array('label'=>'Pending Amount', 'required'=>false, 'div'=>false, 'type'=>'checkbox', 'before'=>''));
			echo $this->Form->input('Cash.pending_amount', array('label'=>false, 'div'=>true, ));
			echo $this->Form->input('Cash.transaction_type', array('label'=>'Credit/Debit', 'options'=>Configure::read('TransactionTypes'), 'escape'=>false, 'empty'=>false, 'required'=>true));
			
			$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
			echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:blue;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:90%'));
			
			
			// echo $this->Form->input('Cash.reference', array('label'=>'Reference / Invoice / Receipt No.', 'required'=>false));
			// echo $this->Form->input('Cash.quantity', array('label'=>'Quantity', 'required'=>false));
			// echo $this->Form->input('Cash.unitrate', array('label'=>'Unit Rate', 'required'=>false));
			
			
			?>
			<?php 
				//echo $this->Form->submit('Add New Record &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;'));
				echo $this->Js->submit('Add New Record &nbsp;&raquo;', array(
					'update' => '#cashForm', 
					'before' => '$("#message").html("<span class=\'notice\'>Your request is in process...</span>")',
					'escape'=>false
					));	
				
			?>
		
	<?php echo $this->Form->end();?>
	<?php echo $this->Js->writeBuffer(array('inline' => 'true'));?>


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
	?>
	$( "#datepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>
});
</script>
<?php
}
?>