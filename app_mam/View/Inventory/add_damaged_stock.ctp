<?php 
	echo $this->element('message');
	echo $this->element('get_category_price');
?>

<?php
if(empty($categories)) {
	echo 'You need to create a category/product before you add records<br><br>';
	echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));
}
else {
?>
<div>
	<h2 class="floatLeft">Add Damaged Stock Details</h2>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/inventory/showDamagedStock/', array('class'=>'button small red floatRight', 'escape'=>false));?>
	
	<?php echo $this->Form->create('Cash', array('url'=>'/inventory/addDamagedStock'));?>		
		
	<div>	
		<div class="floatLeft" style="width:300px; float:left; margin-right:20px;text-align:center;">
			<div class="corner categorySelectionDiv" style="height:350px;">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Category</b></div>			
				<?php
					$disabledOptions = array();
					
					foreach($allcategories as $row) {				
						if(!$row['Category']['is_product']) {
							$disabledOptions[$row['Category']['id']] = true;
						}
						else {
							$disabledOptions[$row['Category']['id']] = false;		
						}
					}						
					$k=0;
					
					foreach($categories as $index=>$name) {				
						if($disabledOptions[$index]) {
							$categories[$index]=array('name'=>$name, 'value'=>'', 'disabled'=>true, 'class'=>'disabledOption');
						}
						else {
							if($k==0) {
								$categories[$index]=array('name'=>$name, 'value'=>$index, 'disabled'=>false, 'selected'=>true);
								$k++;
							}
						}
					}			
				?>				
				<?php echo $this->Form->input('category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'size'=>'17', 'style'=>'border:0px; padding:0px; background:transparent; font-size:95%;', 'required'=>true, 'id'=>'category', 'onchange'=>'showCategoryPrice(this.id);'));?>
				<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;'));?>
			</div>
		</div>		
		<div class="floatLeft" style="width:350px; float:left; margin-right:20px;">
			<div class="corner contentDiv"  style="height:350px;">
			
				<div id="" style="padding:0px; margin:0px;" class="">						
					<div style="width:110px; margin-right:10px;" class="floatLeft">
						<?php echo $this->Form->input('quantity', array('label'=>'Quantity', 'required'=>false, 'placeholder'=>'Enter Qty.', 'onchange'=>'setAmount()', 'id'=>'Quantity')); ?>
					</div>						
					<div style="width:110px; margin-right:0px;" class="floatRight">							
						<?php echo $this->Form->input('unitrate', array('label'=>'Unit Price ('.$this->Session->read('Company.currency').')', 'required'=>false, 'id'=>'UnitRate',  'onchange'=>'setAmount()', 'placeholder'=>'')); ?>
					</div>
					<div class="clear" style="padding:0px; margin:0px;"></div>
				</div>		
					<?php			
				
				echo $this->Form->input('total_amount', array('label'=>'Total Amount / Loss Amount ('.$this->Session->read('Company.currency').')*', 'required'=>true, 'placeholder'=>'Enter Total Amount', 'id'=>'TotalAmount')); 
				
				$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
				//echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));		
				echo $this->Form->submit('Submit &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;', 'div'=>array('style'=>'text-align:center;')));
				?>
			</div>
		</div>		
		<div class='clear'></div>
	</div>	
	<?php echo $this->Form->end(); ?>
</div>

<script type="text/javascript">
showCategoryPrice('category');
function setAmount() {
	var amount = (parseInt($('#Quantity').val()))*(parseFloat($('#UnitRate').val()));
	$('#TotalAmount').val(amount);
}
//setTotalAmount();
</script>
<?php
}
?>
