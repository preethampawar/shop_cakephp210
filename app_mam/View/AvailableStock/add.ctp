<?php 
// echo $this->element('get_category_price');
echo $this->element('get_category_stock');
echo $this->element('message');
?>

<div id="addInventoryForm" >
	<h2 class="floatLeft">Add Closing Stock</h2>
	<?php
	if($categories) {		
	?>
		<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/available_stock/', array('class'=>'button small red floatRight', 'escape'=>false));?>
		<?php echo $this->Form->create();?>
		<?php
			$default = null;
			// $disabledOptions = array();
			
			// foreach($allcategories as $row) {				
				// if(!$row['Category']['is_product']) {
					// $disabledOptions[$row['Category']['id']] = true;
				// }
				// else {
					// $disabledOptions[$row['Category']['id']] = false;		
				// }
			// }						
			// $k=0;
			
			// foreach($categories as $index=>$name) {				
				// if($disabledOptions[$index]) {
					// $categories[$index]=array('name'=>$name, 'value'=>'', 'disabled'=>true, 'class'=>'disabledOption');
				// }
				// else {
					// if($k==0) {
						// $categories[$index]=array('name'=>$name, 'value'=>$index, 'disabled'=>false, 'selected'=>true);
						// $k++;
					// }
				// }
			// }			
			$defaultCategory = null;
			if(!isset($this->data['AvailableStock']['category_id'])) {
				foreach($categories as $index=>$row) {
					$defaultCategory = $index;
					break;					
				}
			}
			
			if($this->Session->check('PrevCategory')) {
				$defaultCategory = $this->Session->read('PrevCategory');
			}
			
			
			
		?>
		<div>
			<div class="floatLeft" style="width:270px; float:left; margin-right:20px;text-align:center;">
				<div class="corner categorySelectionDiv" style="height:350px;">
					<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Product</b></div>		
						<?php echo $this->Form->input('category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'size'=>'18', 'style'=>'border:0px; padding:0px; font-size:95%;', 'required'=>true, 'id'=>'category', 'onchange'=>'showCategoryStock(this.id); showCategoryPrice(this.id);', 'default'=>$defaultCategory));?>
						<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;', 'tabindex'=>'101'));?>
				</div>
			</div>
					
			<div class="floatLeft" style="width:260px; float:left; margin-right:20px;">
				<div class="corner contentDiv" style="height:350px;">
					<?php 
					echo $this->Form->input('available_quantity', array('label'=>'Closing Quantity', 'required'=>true, 'placeholder'=>'Enter Closing Quantity', 'div'=>array('class'=>'required'), 'onchange'=>'setSaleQuantity(); setTotalAmount();', 'id'=>'Quantity')); 
					echo $this->Form->input('payment_amount', array('label'=>'Received Amount('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'PaymentAmount', 'placeholder'=>'Enter Received Amount')); 
					$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
					echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));	
					echo $this->Form->submit('Submit &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;'));
					?>
				</div>
			</div>
			
			<div class="floatLeft" style="width:280px; float:left;">
				<div class="corner contentDiv" style="height:350px;">
					<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Product Information</b></div>	
					
					<table cellpadding='0' cellspacing='0' style="font-size:90%;">
						<tr>
							<td><span>Opening Stock</span></td>
							<td>:</td>
							<td>
								<?php echo $this->Form->input('stockinhand', array('label'=>false, 'required'=>false, 'id'=>'StockInHand', 'style'=>'padding:0px; margin:0px; width:100px; background:transparent; border:0px;', 'div'=>false, 'readonly'=>true)); ?>
							</td>
						</tr>
						<tr>
							<td style='width:110px;'>Unit Price (<?php echo $this->Session->read('Company.currency');?>)</td>
							<td style='width:10px;'>:</td>
							<td>
								<?php echo $this->Form->input('unitrate', array('label'=>false, 'required'=>false, 'id'=>'UnitRate', 'style'=>'padding:0px; margin:0px; width:100px; background:transparent; border:0px;', 'div'=>false, 'readonly'=>true)); ?>
							</td>
						</tr>
						<tr>
							<td><span>Sale Qty.</span></td>
							<td>:</td>
							<td>
								<?php echo $this->Form->input('quantity', array('label'=>false, 'required'=>false, 'id'=>'SaleStock', 'style'=>'padding:0px; margin:0px; width:100px; background:transparent; border:0px;', 'div'=>false, 'readonly'=>true)); ?>
							</td>
						</tr>
						<tr>
							<td><span>Total Amount (<?php echo $this->Session->read('Company.currency');?>)</span></td>
							<td>:</td>
							<td>
								<?php echo $this->Form->input('total_amount', array('label'=>false, 'required'=>false, 'style'=>'padding:0px; margin:0px; width:100px; background:transparent; border:0px;', 'div'=>false, 'id'=>'TotalAmount', 'readonly'=>true)); ?>
							</td>
						</tr>
					</table>
				</div>
			</div>	
			<div class="clear"></div>
			<script type="text/javascript">
			showCategoryStock('category'); 
			showCategoryPrice('category');
			$(function() {
				$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
				$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
				$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
				$( "#datepicker" ).datepicker( "option", "altFormat", "d M, yy");	
				$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
				<?php
				if(isset($this->data['Inventory']['date'])) {
				?>
				$( "#datepicker" ).attr( "value", "<?php echo $this->data['Inventory']['date'];?>" );
				<?php
				}
				else{
					if($this->Session->check('PrevDate')) {
						$date = $this->Session->read('PrevDate');
					}
					else {
						$date = date('Y-m-d');
					}
				?>
				$( "#datepicker" ).attr( "value", "<?php echo $date;?>" );	
				<?php
				}	
				?>
			});
			</script>
		</div>
	<?php 
		echo $this->Form->end();
	}	
	else {
		echo '<div class="clear"></div><br>';
		echo "You need to create/modify a category with 'Manage Inventory' setting enabled before you add stock<br><br>";
		echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
	}
	?>
</div>
<br/>
<h3>Recently added records:</h3>
<?php
if(!empty($availableStock)) {
	echo $this->element('available_stock_list', array('availableStock'=>$availableStock));
}
?>


