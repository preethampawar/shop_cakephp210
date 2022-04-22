<?php echo $this->element('message');?>

<div id="addInventoryForm" >
	<h2 class="floatLeft">Edit Stock</h2>
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/inventory/', array('class'=>'button small red floatRight', 'escape'=>false));?>
	<?php echo $this->Form->create();?>	
	<div>
		<div class="floatLeft" style="width:250px; float:left; margin-right:20px;text-align:center;">
			<div class="corner categorySelectionDiv" style="height:350px;">
				<div style="padding:5px; margin:0px; border-bottom:2px solid #aaa;"><b>Category: <?php echo $inventory['Category']['name'];?></b></div>		
					<?php echo $this->Form->input('Inventory.category_id', array('label'=>false, 'options'=>$categories, 'escape'=>false, 'empty'=>false, 'default'=>$inventory['Inventory']['category_id'], 'size'=>'17', 'style'=>'border:0px; padding:0px; background:transparent;', 'required'=>true));?>
					<?php echo $this->Html->link('+ Add New Category', '/categories/add', array('style'=>'text-decoration:none;'));?>
			</div>
		</div>		
		<div class="floatLeft" style="width:300px; float:left;">
			<div class="corner contentDiv" style="height:350px;">
				<?php 
				echo $this->Form->input('Inventory.quantity', array('label'=>'Enter Quantity', 'required'=>true, 'placeholder'=>'Enter Quantity', 'div'=>array('class'=>'required'))); 
				$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
				echo $this->Form->input('date', array('label'=>'Date*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%'));	
				echo $this->Form->submit('Save Changes &nbsp;&raquo;', array('escape'=>false, 'style'=>'margin-left:0px;'));
				?>
			</div>			
		</div>
		<div class="clear"></div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
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

