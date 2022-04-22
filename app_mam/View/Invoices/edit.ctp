<?php echo $this->element('message');?>

<!-- Invoice Info -->
<h2>Edit Invoice: <?php echo $invoiceInfo['Invoice']['name'];?></h2>		

<div style="float:left; width:950px; border:1px solid #eee; padding:5px 5px 0 5px; background-color:#f6f6f6; margin-bottom:10px;">
	<?php 
	echo $this->Form->create(null, array('id'=>'InvoiceEditAddProductsForm'));  
	echo $this->Form->input('Form.type', array('type'=>'hidden', 'value'=>'EditInvoice'));
	?>	
	<div class="floatLeft" style="width:200px; float:left; margin-right:25px;">
		<?php			
		echo $this->Form->input('name', array('label'=>'Invoice No.', 'required'=>true, 'placeholder'=>'Enter Invoice No.'));			
		?>
	</div>				
	<div class="floatLeft" style="width:100px; float:left; margin-right:25px;">
		<?php		
		echo $this->Form->submit('Save Changes', array('style'=>'margin-top:7px;'));
		?>
	</div>		
	<div class='clear'></div>
	<?php echo $this->Form->end();?>
</div>	

<!--Add Products form -->
<div style="float:left; width:950px; border:1px solid #eee; padding:5px 5px 0 5px; background-color:#f6f6f6;">

	<script type="text/javascript">
	var categoryQtyPerCase = new Array();
	var categoryCP = new Array();
	var categorySP = new Array();
	var categoryName = new Array();
	// Set product price, quantity, etc in javascript
	<?php
	foreach($categoryProducts as $row) {	
	?>
		categoryQtyPerCase['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['qty_per_case'];?>';
		categoryCP['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['cost_price'];?>';		
		categoryName['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['name'];?>';
	<?php	
	}
	?>	
	
	var total_value=0;
	var qty_per_case=0;
	var price_per_case=0;
	var no_of_cases=0;
	
	function setCategoryInfo() {		
		var categoryID = $('#ProductCategoryId').val();
		
		$('#ProductName').val(categoryName[categoryID]);
		$('#ProductQtyPerCase').val(categoryQtyPerCase[categoryID]);
		$('#ProductPricePerCase').val(categoryCP[categoryID]);
		
		no_of_cases = $('#ProductNoOfCases').val();
		
		qty_per_case = (categoryQtyPerCase[categoryID] > 0) ? categoryQtyPerCase[categoryID] : 0;
		
		price_per_case = (categoryCP[categoryID] > 0) ? categoryCP[categoryID] : 0;
		
		total_value = no_of_cases*price_per_case;
		total_value = total_value.toFixed(2);
		
		$('#Cases').text($('#ProductNoOfCases').val());
		$('#CaseValue').text(price_per_case);
		$('#TotalValue').text(total_value);
		$('#UnitsPerCase').text(qty_per_case);
		
	}
	
	function submitAddProductForm() {
		setCategoryInfo();
		if(price_per_case <= 0) { alert('Price per case is not defined for the selected product'); return false; }
		if(qty_per_case <= 0) { alert('Quantity per case not defined'); return false; }
		if(total_value <= 0) { alert('Total ammount should be greater than "0"'); return false; }
		
		return true;
	}
	</script>

	<?php 
	echo $this->Form->create(null, array('id'=>'InvoiceEditAddProductsForm')); 
	echo $this->Form->input('Form.type', array('type'=>'hidden', 'value'=>'AddProduct'));
	echo $this->Form->input('Product.name', array('type'=>'hidden'));
	// echo $this->Form->input('Product.qty_per_case', array('type'=>'hidden'));
	echo $this->Form->input('Product.price_per_case', array('type'=>'hidden'));


	?>
	<div style="float:left; width:250px; margin:0; padding:0;">
		<?php	
		$selectedCategoryID = null;	
		if(isset($this->data['Product']['category_id'])) {
			$selectedCategoryID = $this->data['Product']['category_id'];
		}
		if($this->Session->check('PrevCategory')) {
			$selectedCategoryID = $this->Session->read('PrevCategory');
		}
		
		echo $this->Form->input('Product.category_id', array('options'=>$categoryProductsList, 'empty'=>false, 'onchange'=>'setCategoryInfo()', 'label'=>'Select Product', 'style'=>'font-size:90%; width:250px;', 'default'=>$selectedCategoryID));		
		?>
	</div>
	
	<div style="float:left; margin:0 0 0 10px;">
		<?php
		echo $this->Form->input('Product.no_of_cases', array('type'=>'text', 'label'=>'Qty (no. of boxes)', 'onchange'=>'setCategoryInfo()', 'div'=>false, 'style'=>'width:100px; font-size:98%; '));
		?>
	</div>
	<div style="float:left; width:100px; margin-left:0px;">
		<?php
		echo $this->Form->input('Product.qty_per_case', array('label'=>'qty/box', 'type'=>'hidden'));
		?>
		<div style="margin:0;">&nbsp;</div><div><span id="UnitsPerCase" style="font-weight:bold;"></span> units/box</div> 
		
	</div>
	<div style="float:left; width:100px; margin-left:10px;">
		<div style="margin:0;">Price/case</div><div id="CaseValue" style="font-weight:bold; padding-left:5px;"></div>
	</div>
	<div style="float:left; width:120px; margin-left:10px;">
		<div style="margin:0;">Total Amount</div><div id="TotalValue" style="font-weight:bold; padding-left:5px;"></div>
	</div>
	<div style="float:left; width:100px; margin-left:10px;">
		
		<?php echo $this->Form->submit('+ Add Product', array('type'=>'submit', 'onclick'=>'return submitAddProductForm()'));?>	
	</div>
	<div style="clear:both;"></div>

	<?php echo $this->Form->end();?>
	<script type="text/javascript"> setCategoryInfo(); $('#ProductCategoryId').focus(); </script>
</div>

<div style="float:left; width:450px;">
	<?php 
	echo $this->Form->create(null, array('id'=>'InvoiceEditAddExtraInfoForm')); 
	echo $this->Form->input('Form.type', array('type'=>'hidden', 'value'=>'AddExtraInfo'));
	?>

	<?php echo $this->Form->end();?>
</div>
<div style="clear:both;"></div>
<br><br>
<h2>Invoice Details</h2>
<?php
if(!empty($invoiceInfo['Data'])) {
?>
	<table style="width:800px;" cellpadding='0' cellspacing='0'>
		<thead>
			<tr>
				<th style="width:50px;">Sl.No.</th>
				<th>Product</th>
				<th style="width:100px;">Quantity</th>
				<th style="width:100px;">Price/case</th>
				<th style="width:120px;">Amount</th>
				<th style="width:50px;">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k=0;
		$total_amount = 0;
		$total_qty = 0;
		foreach($invoiceInfo['Data'] as $row) {
			$k++;
		?>
			<tr>
				<td><?php echo $k;?></td>
				<td><?php echo $row['category_name'];?></td>
				<td>
					<?php 
						$total_qty+=$row['no_of_cases'];
						echo ($row['no_of_cases']) ? $row['no_of_cases'].' ('.$row['quantity'].' units)' : '-';
					?>
				</td>
				<td><?php echo ($row['price_per_case']) ? $row['price_per_case'] : '-';?></td>
				<td>
					<?php 
					if($row['transaction_type'] == 'debit') {
						$total_amount+=$row['total_amount'];
					?>
						<span><?php echo $row['total_amount']; ?></span> <span class="debit" style="font-size:11px;">(Dr.) </span>
					<?php						
					}
					if($row['transaction_type'] == 'credit') {
						$total_amount-=$row['total_amount'];
					?>
						<span><?php echo $row['total_amount']; ?></span> <span class="credit" style="font-size:11px;">(Cr.) </span>
					<?php						
					}
					?>
				</td>
				<td><?php echo $this->Html->link('Delete', '/invoices/deleteProduct/'.$invoiceInfo['Invoice']['id'].'/'.$row['id'], array(), 'Are you sure you want to delete this item - '.$row['category_name'].'?');?></td>
			</tr>	
		<?php		
		}
		?>
			<tr>
				<td></td>
				<td></td>
				<td style="border-top:2px solid #aaa;border-bottom:2px solid #aaa;"><strong><?php echo $total_qty;?></strong></td>
				<td style="text-align:right;border-top:2px solid #aaa;border-bottom:2px solid #aaa; color:#888;"><strong>Total Amount: </strong></td>
				<td style="border-top:2px solid #888; border-bottom:2px solid #888; ">								
					<div>
						<strong><?php echo number_format($total_amount, 2, '.', '');?></strong>
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
	</table>	
	<?php
}
?>

