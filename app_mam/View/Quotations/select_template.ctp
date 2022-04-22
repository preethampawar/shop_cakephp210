<h2 class="floatLeft">Select Quotation Template</h2><br/>
<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/quotations/', array('class'=>'button small red floatRight', 'escape'=>false));	?>
<div class="clear"></div>
<br/>
<table style="width:600px;" cellpadding='1' cellspacing='1'>
	<tr>
		<th width='100'>Sl.No.</th>
		<th>Template</th>
		<th width='220'></th>
	</tr>
	<?php
	$templates = Configure::read('QuotationTemplates');
	$i=0;
	foreach($templates as $value=>$name) {
		$i++;
	?>
	<tr>
		<td><?php echo $i;?></td>
		<td><?php echo $name;?></td>
		<td>
			<?php echo $this->Html->link($this->Html->image('preview.gif', array('alt'=>'', 'width'=>'12', 'height'=>'12', 'style'=>'float:left;')).' Preview', '/img/quotations/'.$value.'_template.jpg', array('target'=>'_blank', 'class'=>'button small grey', 'title'=>'Preview template: '.$name, 'escape'=>false));?>
			&nbsp;|&nbsp;
			<?php echo $this->Html->link('&#10004; Select Template', array('controller'=>'quotations', 'action'=>'selectTemplate', $value), array('class'=>'button small grey', 'title'=>'Select template: '.$name, 'escape'=>false));?>
		</td>
	</tr>	
	<?php	
	}
	?>
</table>