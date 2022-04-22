<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>
	<br/>
<?php
echo $this->Form->button('&laquo; back', ['onclick' => 'history.back()', 'escape' => false]);
?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
echo $this->Html->link('Download', ['controller' => 'quotations', 'action' => 'download', $quotationID], ['class' => 'button small grey']);
?>
<?php
$template = (isset($quotationInfo['Quotation']['template'])) ? $quotationInfo['Quotation']['template'] : 'default';
switch ($template) {
	case 'default':
		echo $this->element('Quotations/default_template');
		break;
	case 'nursery':
		echo $this->element('Quotations/nursery_template');
		break;
	default:
		echo $this->element('Quotations/default_template');
		break;
}
?>
