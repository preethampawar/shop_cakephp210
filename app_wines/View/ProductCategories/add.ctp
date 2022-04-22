<?php
$inventory = false;
$visibility = false;
$isWineStore = false;
if($this->Session->check('UserCompany')) {	
	switch($this->Session->read('Company.business_type')) {
		case 'personal':			
			break;			
		case 'general':
			$visibility = true;
			break;			
		case 'inventory':
			$inventory = true;
			$visibility = true;
			break;	
		case 'wineshop':
			$inventory = true;
			$visibility = true;
			$isWineStore = true;
			break;			
		case 'finance':
			break;		
		case 'default':
			break;
	}	
}
?>

<?php echo $this->element('message');?>

<div id="addCategoryForm" >
	<h1 class="floatLeft">Add New Category</h1>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/categories/', array('class'=>'button small red floatRight', 'escape'=>false));	?>
	<?php echo $this->Form->create();?>
	<div>
		<div class="floatLeft" style="width:250px; float:left; margin-right:20px;text-align:center;">
			<div class="corner categorySelectionDiv" style="height:320px;">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Parent Category</b></div>
				<?php echo $this->Form->input('Category.parent_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>'-- None --', 'default'=>$parent_id, 'size'=>'17', 'style'=>'border:0px; padding:0px; background:transparent; font-size:95%;'));?>
			</div>
		</div>	
		
		<div class="floatLeft" style="width:500px; float:left; margin-right:20px;">
			<div class="corner contentDiv" style="height:320px;">
				<?php echo $this->Form->input('Category.name', array('label'=>'Category (or) Item Name', 'required'=>true, 'placeholder'=>'Enter Category or Item Name')); ?>
				<div>
					<?php if($visibility) { ?>
					<?php echo $this->Form->input('Category.show_in_sales', array('label'=>'Show in Sales', 'default'=>'1', 'div'=>array('class'=>'floatLeft', 'style'=>'width:130px;')));?>
					<?php echo $this->Form->input('Category.show_in_purchases', array('label'=>'Show in Purchases', 'default'=>'1', 'div'=>array('class'=>'floatLeft', 'style'=>'width:160px;')));?>
					<?php echo $this->Form->input('Category.show_in_cash', array('label'=>'Show in Cash', 'default'=>'1', 'div'=>array('class'=>'floatLeft', 'style'=>'width:110px;')));?>
					<div class="clear"></div>
					<?php } ?>
					<?php 
						echo ($inventory) ? $this->Form->input('Category.is_product', array('label'=>'Manage Inventory', 'div'=>array('style'=>'width:200px;'), 'onclick'=>'showHideProductPrice(this.id)')) : null;
					?>
					<div id="productPrice" style="padding:0px; margin:0px;" class="">						
						<div style="width:200px; margin-right:10px; margin-bottom:0px;" class="floatLeft">
							<?php echo $this->Form->input('Category.cost_price', array('label'=>'Cost Price (CP) in '.$this->Session->read('Company.currency'), 'placeholder'=>'Enter CP. per unit')); ?>
						</div>						
						<div style="width:200px; margin-right:0px; margin-bottom:0px;" class="floatLeft">							
							<?php echo $this->Form->input('Category.selling_price', array('label'=>'Selling Price (SP) in '.$this->Session->read('Company.currency'), 'placeholder'=>'Enter SP. per unit')); ?>
						</div>
						<?php
						if($isWineStore) {
						?>
						<div style="width:100px; margin-right:0px; margin-bottom:0px;">
							<?php
							$options = array();	
							for($i=1; $i<=100;$i++) {
								$options[$i] = $i;
							}
							echo $this->Form->input('Category.qty_per_case', array('label'=>'Qty Per Case', 'options'=>$options)); 
							?>
						</div>
						<?php
						}
						?>
						<div class="clear"></div>
					</div>
					<br/>
					<?php	echo $this->Form->submit('Create &nbsp;&raquo;', array('escape'=>false)); ?>
				</div>
			</div>			
		</div>
		<div class="clear"></div>
	</div>
	<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
	showHideProductPrice('CategoryIsProduct');
</script>
<?php //echo $this->element('admin_categories_menu');?>
