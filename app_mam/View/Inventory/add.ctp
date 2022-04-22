<?php echo $this->element('message');?>

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
	
	function setCategoryInfo() {
		var categoryID = $('#InventoryCategoryId').val();
		var cp = categoryCP[categoryID];
		var qtyPerCase = categoryQtyPerCase[categoryID];
		
		if(cp <= 0) {
			$('#InventoryUnitrate').val('0');			
		}
		else {
			if(qtyPerCase == 0) {
				$('#InventoryUnitrate').val('0');
			}
			else {				
				var unitrate = cp/qtyPerCase;
				$('#InventoryUnitrate').val(unitrate.toFixed(2));				
			}
		}
		$('#InventoryCategoryId').focus();	
	}	
</script>

<div id="addInventoryForm" >
	<h2 class="floatLeft">Add Stock</h2>
	<?php
	if($categoryProducts) {		
	?>
		<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/inventory/', array('class'=>'button small red floatRight', 'escape'=>false));?>
		<?php echo $this->Form->create();?>
		<?php
			$default = null;
			$disabledOptions = array();
			
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
			if($this->Session->check('PrevCategory')) {
				$default = $this->Session->read('PrevCategory');
			}
			
			if(!isset($this->data['Inventory']['category_id'])) {
				foreach($categoryProductsList as $index=>$row) {
					$default = $index;
					break;					
				}
			}
			
		?>
		<div>
			<div class="floatLeft" style="width:300px; float:left; margin-right:20px;text-align:center;">
				<div class="corner categorySelectionDiv" style="height:350px;">
					<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Select Category</b></div>		
						<?php echo $this->Form->input('Inventory.category_id', array('label'=>false, 'options'=>$categoryProductsList, 'escape'=>false, 'empty'=>false, 'default'=>$default, 'size'=>'17', 'style'=>'border:0px; padding:0px; font-size:95%', 'required'=>true, 'onchange'=>'setCategoryInfo()'));?>
						<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;', 'tabindex'=>'101'));?>
				</div>
			</div>
					
			<div class="floatLeft" style="width:300px; float:left;">
				<div class="corner contentDiv" style="height:350px;">
					<?php 
					echo $this->Form->input('Inventory.quantity', array('label'=>'Enter Quantity', 'required'=>true, 'placeholder'=>'Enter Quantity', 'div'=>array('class'=>'required'))); 
					echo $this->Form->input('Inventory.unitrate', array('label'=>'Unit rate', 'required'=>true, 'placeholder'=>'Enter Price', 'div'=>array('class'=>'required'))); 
					$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
					echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));	
					echo $this->Form->submit('Add Stock &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;'));
					?>
				</div>
			</div>
			<div class="clear"></div>
			<script type="text/javascript">
			$(function() {
				setCategoryInfo();
				
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


