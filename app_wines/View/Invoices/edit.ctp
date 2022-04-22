<?php $this->start('invoices_report_menu');?>
<?php echo $this->element('invoices_menu');?>
<?php echo $this->element('sales_purchases_report_menu');?>
<?php $this->end();?>

<h1>Edit Invoice</h1><br>
<div class="well">
	<?php
	echo $this->Form->create();
	echo $this->Form->input('Invoice.invoice_date', array('label'=>'Invoice Date', 'required'=>true, 'type'=>'date', 'title'=>'Select date'));
	echo $this->Form->input('Invoice.name', array('label'=>'Invoice No.', 'required'=>true, 'type'=>'text', 'title'=>'Enter Invoice Name'));
	//echo $this->Form->input('Invoice.dd_no', array('label'=>'DD No.', 'title'=>'Enter DD No.'));
	echo $this->Form->input('Invoice.dd_amount', array('label'=>'DD Amount', 'title'=>'Enter DD Amount', 'required'=>true));
    echo $this->Form->input('Invoice.retail_shop_excise_turnover_tax', array('label'=>'Retail Shop Excise Turnover Tax', 'title'=>'Retail Shop Excise Turnover Tax'));
    echo $this->Form->input('Invoice.special_excise_cess', array('label'=>'Special Excise Cess', 'title'=>'Special Excise Cess'));
	echo $this->Form->input('Invoice.tcs_value', array('label'=>'TCS Value', 'title'=>'Enter TCS Value', 'default'=>0));
	echo $this->Form->input('Invoice.mrp_rounding_off', array('label'=>'MRP Rounding Off', 'title'=>'Enter MRP Rounding Off Value', 'default'=>0));
	echo $this->Form->input('Invoice.prev_credit', array('label'=>'Previous Credit', 'title'=>'Enter Previous Credit', 'default'=>0));
	//echo $this->Form->input('Invoice.dd_purchase', array('label'=>'DD Purchase Amount', 'title'=>'Enter DD Purchase Amount'));
	//echo $this->Form->input('Invoice.tax', array('label'=>'Tax', 'title'=>'Enter Tax Amount'));
	echo $this->Form->input('Invoice.supplier_id', array('label'=>'Supplier', 'empty'=>'-', 'type'=>'text', 'title'=>'Select Supplier', 'options'=>$suppliersList, 'type'=>'select'));
	echo $this->Form->submit('Update Invoice');
	echo $this->Form->end();
	
	echo '<br>';
	echo $this->Html->link('Cancel', array('controller'=>'invoices', 'action'=>'index'), array('class'=>'btn btn-danger btn-xs'));
	?>
</div>