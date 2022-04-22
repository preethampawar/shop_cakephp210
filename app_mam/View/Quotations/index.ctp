<h1 class="floatLeft">Quotations</h1>
<?php echo $this->Html->link('+ Create New Quotation', array('controller'=>'quotations', 'action'=>'selectTemplate'), array('class'=>'button grey medium floatRight', 'escape'=>false));?>
<div class="clear"></div>
<br>
<?php
if(!empty($quotations)) {
?>
	<table cellspacing='1' cellpadding='1'>
		<thead>
			<tr>			
				<th width='90'><?php echo $this->Paginator->sort('id', 'Quotation No.');?></th>				
				<th width='250'><?php echo $this->Paginator->sort('to_name', 'Quotation For:');?></th>				
				<th><?php echo $this->Paginator->sort('comments', 'Comments');?></th>				
				<th width='100'><?php echo $this->Paginator->sort('template', 'Template');?></th>				
				<th width='120'><?php echo $this->Paginator->sort('created', 'Date');?></th>
				<th width='280'></th>							
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($quotations as $row) {
			$i++;
			?>
			<tr>
				<td style="text-align:center;"><?php echo $this->Html->link($row['Quotation']['id'], array('controller'=>'quotations', 'action'=>'details', $row['Quotation']['id']), array('title'=>'View details'));?></td>
				<td><?php echo $row['Quotation']['to_name'];?></td>
				<td><?php echo $row['Quotation']['comments'];?></td>
				<td><?php echo Configure::read('QuotationTemplates.'.$row['Quotation']['template']);?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Quotation']['date']));?></td>
				<td>
					<?php echo $this->Html->link($this->Html->image('show_details.gif', array('class'=>'downloadImage')).'Details', array('controller'=>'quotations', 'action'=>'details', $row['Quotation']['id']), array('class'=>'button small grey', 'escape'=>false));?> 
					&nbsp;|&nbsp;
					<?php echo $this->Html->link('Download'.$this->Html->image('download.png', array('class'=>'downloadImage')), array('controller'=>'quotations', 'action'=>'download', $row['Quotation']['id']), array('class'=>'button small grey download', 'escape'=>false));?><span class="clear"></span>
					&nbsp;|&nbsp;					
					<?php					
					echo $this->Html->link('Delete &nbsp;'.$this->Html->image('error.png', array('style'=>'float:right;','height'=>'12', 'width'=>'12', 'alt'=>'&times;')), array('controller'=>'quotations', 'action'=>'delete/'.$row['Quotation']['id']), array('class'=>'button small grey', 'escape'=>false), 'Quotation No.'.$row['Quotation']['id'].' - Are you sure you want to delete this quotation?');				
					?>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>		
	</table>
	<br>
	<?php echo $this->Paginator->prev(' << ' . __('previous'), array(), null, array('class' => 'prev disabled'));?>
	&nbsp;&nbsp;&nbsp;<?php echo $this->Paginator->numbers();?>&nbsp;&nbsp;&nbsp;
	<?php echo $this->Paginator->next(__('next').' >>' , array(), null, array('class' => 'next disabled'));?>
<?php	
}
else {
	echo 'No Quotations Found <br> <br>';
}
?>