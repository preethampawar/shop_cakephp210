<?php echo $this->element('message');?>

<div id="addInventoryForm" >
	<h2 class="floatLeft">Move Stock From Godown To Shop</h2>
	<?php
	if($categories) {		
	?>
		<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/inventory/showMovedStock', array('class'=>'button small red floatRight', 'escape'=>false));?>
		<?php echo $this->Form->create();?>
		<?php
			$default = null;
			$disabledOptions = array();
			
			foreach($allcategories as $row) {				
				if(!$row['Category']['is_product']) {
					$disabledOptions[$row['Category']['id']] = true;
					$nameExt[$row['Category']['id']] = '';
				}
				else {
					$disabledOptions[$row['Category']['id']] = false;		
					$nameExt[$row['Category']['id']] = $row['Category']['stock_in_godown'].'/'.$row['Category']['stock_in_shop'];
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
					else {
						$categories[$index]=array('name'=>$name.' ['.$nameExt[$index].']', 'value'=>$index);
					}
				}
			}		
		?>
		<div>
			<div class="floatLeft" style="width:300px; float:left; margin-right:20px;text-align:center;">
				<div class="corner categorySelectionDiv" style="height:350px;">
					<div style="padding:5px 0px; margin:0px; border-bottom:2px solid #aaa;"><b>Category [Godown Stock/Shop Stock]</b></div>		
						<?php echo $this->Form->input('Inventory.category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'html'=>true, 'empty'=>false, 'default'=>$default, 'size'=>'17', 'style'=>'border:0px; padding:0px; background:transparent;', 'required'=>true));?>
						<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;'));?>
				</div>
			</div>
					
			<div class="floatLeft" style="width:300px; float:left;">
				<div class="corner contentDiv" style="height:350px;">
					<?php 
					echo $this->Form->input('Inventory.quantity', array('label'=>'Enter Quantity', 'required'=>true, 'placeholder'=>'Enter Quantity', 'div'=>array('class'=>'required'))); 
					
					echo $this->Form->submit('Move Stock To Shop &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;'));
					?>
				</div>
			</div>
			<div class="clear"></div>
			<script type="text/javascript">
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
				?>
				$( "#datepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
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


