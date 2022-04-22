<?php echo $this->element('message');?>

<div style="width:400px; height:300px; border:1px solid #efefef; margin:auto; background-color:#f6f6f6; margin-top:5%; padding:10px 10px 10px 10px;" class="corner contentDiv">
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/groups/', array('class'=>'button small red floatRight', 'escape'=>false));	?>
	<div class="clear" style="margin:0px; padding:0px;"></div>
	<h1>Add New Group</h1> <hr><br><br>
	<?php 
		echo $this->Form->create();			
		echo $this->Form->input('Group.name', array('label'=>'Group Name', 'required'=>true, 'style'=>'width:250px;'));
		/*
		?>
		<div>
			<?php echo $this->Form->input('Group.show_in_sales', array('label'=>'Show in Sales', 'default'=>'1', 'div'=>array('class'=>'floatLeft', 'style'=>'width:120px;')));?>
			<?php echo $this->Form->input('Group.show_in_purchases', array('label'=>'Show in Purchases', 'default'=>'1', 'div'=>array('class'=>'floatLeft', 'style'=>'width:150px;')));?>
			<?php echo $this->Form->input('Group.show_in_cash', array('label'=>'Show in Cash', 'default'=>'1', 'div'=>array('class'=>'floatLeft', 'style'=>'width:150px;')));?>
			<div class="clear"></div>
		</div>
		<?php
		*/
		echo $this->Form->input('Group.active', array('label'=>'Active', 'default'=>'1')).'<br>';
		echo $this->Form->submit('Create Group &nbsp;&raquo;', array('escape'=>false));
		echo $this->Form->end();
		// echo '<br>&nbsp;&laquo;&nbsp;'.$this->Html->link('Cancel', '/groups/');
	?>
</div>
