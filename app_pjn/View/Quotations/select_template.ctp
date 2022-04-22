<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>

<h1 class="floatLeft">Select Quotation Template</h1><br/>
<?php echo '&nbsp;' . $this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/quotations/', ['class' => 'button small red floatRight', 'escape' => false]); ?>
<div class="clear"></div>
<br/>
<table style="width:600px;" class="table ">
	<tr>
		<th width='100'>Sl.No.</th>
		<th>Template</th>
	</tr>
	<?php
	$templates = [1 => 'Default'];
	$i = 0;
	foreach ($templates as $value => $name) {
		$i++;
		?>
		<tr>
			<td><?php echo $i; ?></td>

			<td>
				<?php echo $this->Html->link('&#10004; ' . $name . ' template', ['controller' => 'quotations', 'action' => 'selectTemplate', $value], ['class' => '', 'title' => 'Select template: ' . $name, 'escape' => false]); ?>
			</td>
		</tr>
		<?php
	}
	?>
</table>
