<?php $this->start('sales_report_menu');?>
<?php echo $this->element('sales_menu');?>
<?php echo $this->element('sales_purchases_report_menu');?>
<?php $this->end();?>


<h1>Add New Sale</h1><br>

<?php
if($productsInfo) {
?>
	<script type="text/javascript">
		var unitSellingPrice = new Array();	
		var availableQty = new Array();	
		<?php	
		foreach($productsInfo as $row) {
		?>
			unitSellingPrice['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['Product']['unit_selling_price'];?>';			
			availableQty['<?php echo $row['Product']['id'];?>'] = '<?php echo $row['ProductStockReport']['balance_qty'];?>';
		<?php
		}
		?>
		
		function setDefaultProductPrice() {
			var productID = $('#SaleProductId').val();
			var unitPrice = parseFloat((unitSellingPrice[productID]>0)?unitSellingPrice[productID]:0);
			$('#SaleUnitPrice').val(unitPrice);
		}
		
		function setTotalPrice() {
			var productID = $('#SaleProductId').val();
			var iTotalUnits = parseInt(($('#SaleTotalUnits').val()>0)?$('#SaleTotalUnits').val():0);			
			var unitPrice = parseInt(($('#SaleUnitPrice').val()>0)?$('#SaleUnitPrice').val():0);	
			var iAvailableQty = parseInt((availableQty[productID] > 0) ? availableQty[productID] : 0);					
			
			var oTotalPrice = ((iTotalUnits*unitPrice)>0) ? (iTotalUnits*unitPrice).toFixed(2) : 0;
			var oTotalUnits = iTotalUnits;			
			
			if(iAvailableQty <= 0) {
				$('#SubmitForm').attr('title', 'Product is out of stock');
			}
			else {
				if(iAvailableQty<iTotalUnits) {
					$('#SubmitForm').attr('title', 'No. of Units cannot be greater than '+iAvailableQty);
				}
				else {
					if(iTotalUnits <= 0) {
						$('#SubmitForm').attr('title', 'No. of Units should be greater than 0');
					}
					else {
						if(oTotalPrice <= 0) {
							$('#SubmitForm').attr('title', 'Total amount should be greater than 0');					
						}
						else {
							$('#SubmitForm').attr('title', '');
						}
					}
				}
			}
			
			// set hidden variables			
			$('#SaleTotalAmount').val(oTotalPrice);
			
			// set output
			$('#oTotalPrice').text(oTotalPrice);			
		}
		
		function submitButtonMsg() {
			setTotalPrice();						
			if(parseInt($('#SubmitForm').attr('title').length) > 0) {
				alert($('#SubmitForm').attr('title'));
				return false;
			}
			return true;
		}
	</script>
	
	
	<div id="AddSaleProductDiv" class="well">
		<?php 
		echo $this->Form->create();
		?>
		<div id="paramsDiv">			
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('product_id', array('empty'=>false, 'label'=>'Product [available stock]', 'required'=>true, 'type'=>'select', 'options'=>$productsList, 'onchange'=>'setDefaultProductPrice(); setTotalPrice();', 'autofocus'=>true, 'escape'=>false, 'class'=>'autoSuggest'));?>
			</div>
			<div style="float:left; clear:none;">
				<?php 
				echo $this->Form->input('total_units', array('type'=>'number', 'min'=>'1', 'max'=>'99999', 'label'=>'No. of Units', 'required'=>true, 'oninput'=>'setTotalPrice()', 'title'=>'Values should be between 1 to 99999'));				
				?>
			</div>
			<div style="float:left; clear:none;">
				<?php 				
				echo $this->Form->input('unit_price', array('type'=>'text', 'label'=>'Unit Price', 'required'=>true, 'oninput'=>'setTotalPrice()', 'title'=>'Unit Price'));				
				echo $this->Form->input('total_amount', array('type'=>'hidden'));
				?>
			</div>
			<div style="float:left; clear:both;">
				<?php echo $this->Form->input('sale_date', array('label'=>'Sale Date', 'required'=>true, 'type'=>'date'));?>
			</div>
			<div style="float:left; clear:none;">			
				<br><br> &nbsp;&nbsp;
				<strong>Total Amount: <span id="oTotalPrice" style="">0</span></strong>	
			</div>		
			<div style="float:left; clear:both; padding-top:10px;">
				
				<?php echo $this->Form->submit('Add Product', array('div'=>false, 'id'=>'SubmitForm', 'title'=>'', 'type'=>'submit', 'onclick'=>'return submitButtonMsg()'));?>
			</div>		
		</div>
		<div style="clear:both;"></div>
		<?php		
		echo $this->Form->end();
		?>		
		<script type="text/javascript">
			$(document).ready(function() { 
				<?php
				$setDefaultPrice = (!(isset($this->data)) or !($this->data)) ? true : (($this->Session->check('selectedProductID')) ? true : false);
				echo ($setDefaultPrice) ? 'setDefaultProductPrice();' : null;			
				echo 'setTotalPrice();';
				?>
			});
		</script>
		
	</div>
	
	<h2>Recent Sale Products</h2>
	<?php 
	if($saleProducts) { 
	?>
	<table class='table' style="width:100%">
		<thead>
			<tr>
				<th>S.No</th>
				<th>Date</th>
				<th>Category</th>
				<th>Product</th>
				<th>No. of Units</th>
				<th>Unit Price</th>
				<th>Total Amount</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($saleProducts as $row) {
				$i++;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Sale']['sale_date']));?></td>
				<td><?php echo $row['Sale']['category_name'];?></td>
				<td><?php echo $row['Sale']['product_name'];?></td>
				<td><?php echo $row['Sale']['total_units'];?></td>
				<td><?php echo $row['Sale']['unit_price'];?></td>
				<td><?php echo $row['Sale']['total_amount'];?></td>				
				<td>
					<form method="post" style="" name="sales_<?php echo $row['Sale']['id'];?>" id="sales_<?php echo $row['Sale']['id'];?>" action="<?php echo $this->Html->url("/sales/removeProduct/".$row['Sale']['id']);?>">						
						<a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Sale']['product_name'];?> from the list?')) { $('#sales_<?php echo $row['Sale']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</a> 
					</form>
					<?php //echo $this->Form->postLink('Remove', array('controller'=>'sales', 'action'=>'removeProduct', $row['Sale']['id']), array('title'=>'Remove product from invoice - '.$row['Sale']['product_name'], 'class'=>'small button link red'), 'Are you sure you want to delete this product "'.$row['Sale']['product_name'].'"?');?>				
				</td>
			</tr>
			<?php
			}
			?>			
		</tbody>
	</table>
	
	<?php } else { ?>
	<p>No sales found</p>
	<?php } ?>
	
<?php
}
else {
	echo 'No products found. You need to add products to continue.';
}
?>