<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>

	<h1>Quotations / Sale Invoices</h1>
	<br>
<?php
if (!empty($quotations)) {
	?>
	<table cellspacing='1' cellpadding='1' class="table">
		<thead>
		<tr>
			<th width='90'><?php echo $this->Paginator->sort('id', 'Quotation No.'); ?></th>
			<th width='250'><?php echo $this->Paginator->sort('to_name', 'Quotation For:'); ?></th>
			<th><?php echo $this->Paginator->sort('comments', 'Comments'); ?></th>
			<th width='100'><?php echo $this->Paginator->sort('template', 'Template'); ?></th>
			<th width='120'><?php echo $this->Paginator->sort('created', 'Date'); ?></th>
			<th width=''></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($quotations as $row) {
			$i++;
			?>
			<tr>
				<td style="text-align:center;"><?php echo $this->Html->link($row['Quotation']['id'], ['controller' => 'quotations', 'action' => 'details', $row['Quotation']['id']], ['title' => 'View details']); ?></td>
				<td><?php echo $row['Quotation']['to_name']; ?></td>
				<td><?php echo $row['Quotation']['comments']; ?></td>
				<td><?php echo Configure::read('QuotationTemplates.' . $row['Quotation']['template']); ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Quotation']['date'])); ?></td>
				<td>
					<?php echo $this->Html->link($this->Html->image('show_details.gif', ['class' => 'downloadImage']) . 'Details', ['controller' => 'quotations', 'action' => 'details', $row['Quotation']['id']], ['class' => 'btn btn-xs btn-default', 'escape' => false]); ?>
					&nbsp;&nbsp;
					<?php echo $this->Html->link('Download Quotation', ['controller' => 'quotations', 'action' => 'download', $row['Quotation']['id']], ['class' => 'btn btn-xs btn-default download', 'escape' => false]); ?>
					<span class="clear"></span>
					&nbsp;&nbsp;
					<?php echo $this->Html->link('Download Invoice', ['controller' => 'quotations', 'action' => 'download', $row['Quotation']['id'], 'invoice'], ['class' => 'btn btn-xs btn-primary download', 'escape' => false]); ?>
					<span class="clear"></span>
					&nbsp;&nbsp;
					<?php
					echo $this->Html->link('Delete', ['controller' => 'quotations', 'action' => 'delete/' . $row['Quotation']['id']], ['class' => 'btn btn-danger btn-xs', 'escape' => false], 'Quotation No.' . $row['Quotation']['id'] . ' - Are you sure you want to delete this quotation?');
					?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<br>
	<?php echo $this->Paginator->prev(' << ' . __('previous'), [], null, ['class' => 'prev disabled']); ?>
	&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->numbers(); ?>&nbsp;&nbsp;&nbsp;
	<?php echo $this->Paginator->next(__('next') . ' >>', [], null, ['class' => 'next disabled']); ?>
	<?php
} else {
	echo 'No Quotations Found <br> <br>';
}
?>
