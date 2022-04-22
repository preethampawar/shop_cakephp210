<?php $this->start('reports_menu');?>
<?php echo $this->element('reports_menu');?>
<?php $this->end();?>

<h1>Bank Report</h1>
<?php echo $this->Form->create('Report', array('url'=>'/reports/generateBankReport/', 'target'=>'_blank', 'id'=>'IncomeExpensesReport')); ?>
	<div id="paramsDiv">	
		<div style="float:left; clear:none;">
			<?php 
			$options = array('credit'=>'Deposit', 'debit'=>'Withdrawal');
			echo $this->Form->input('payment_type', array('empty'=>'Deposit & Withdrawal', 'label'=>'Payment Type', 'type'=>'select', 'options'=>$options, 'escape'=>false));
			?>
		</div>
		<div style="float:left; clear:none;">
			<?php 
			$options = array('print'=>'Print View');
			echo $this->Form->input('view_type', array('empty'=>'Normal View', 'label'=>'Select View', 'type'=>'select', 'options'=>$options, 'escape'=>false));
			?>
		</div>
		<div style="float:left; clear:both;">
			<?php echo $this->Form->input('from_date', array('label'=>'From Date', 'required'=>true, 'type'=>'date'));?>
		</div>
		<div style="float:left; clear:none;">
			<?php echo $this->Form->input('to_date', array('label'=>'To Date', 'required'=>true, 'type'=>'date'));?>
		</div>
		  
		 <div style="float:left; clear:both;">
			<?php echo $this->Form->input('show_prev_balance', array('label'=>'Show Previous Balance', 'type'=>'checkbox', 'value'=>1, 'default'=>1));?>
		</div>
		  
		 
		<div>
			<?php echo $this->Form->submit('Generate Report', array('id'=>'SubmitForm', 'title'=>'', 'type'=>'submit'));?>
		</div>
	</div>
<?php		
echo $this->Form->end();
?>		
