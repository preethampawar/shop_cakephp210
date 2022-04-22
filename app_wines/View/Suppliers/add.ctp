<?php $this->start('invoices_report_menu');?>
<?php echo $this->element('invoices_menu');?>
<?php echo $this->element('sales_purchases_report_menu');?>
<?php $this->end();?>

<div>
	<?php 
	echo $this->Form->create();
	echo $this->Form->input('Supplier.name', array('label'=>'Supplier Name', 'required'=>true, 'title'=>'Enter Supplier Name', 'placeholder'=>'Enter Supplier Name'));
	echo $this->Form->input('Supplier.phone', array('label'=>'Phone No.', 'type'=>'text', 'title'=>'Phone No', 'placeholder'=>'Enter Phone No.'));
	echo $this->Form->input('Supplier.email', array('label'=>'Email Address', 'type'=>'email', 'title'=>'Email Address', 'placeholder'=>'Enter Email Address'));
	echo $this->Form->input('Supplier.address', array('label'=>'Contact Address', 'type'=>'textarea', 'rows'=>'2', 'title'=>'Contact Address', 'placeholder'=>'Enter Contact Address'));
	echo $this->Form->submit('Create Supplier');
	echo $this->Form->end();
	?>
</div>