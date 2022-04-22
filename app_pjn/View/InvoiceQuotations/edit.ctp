<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>

	<h1>Edit Invoice/Quotation</h1>
<?php echo '&nbsp;' . $this->Html->link('Cancel', '/invoice_quotations/index/invoice', ['class' => '', 'escape' => false]); ?>
	<br/><br/>
<?php
$template = 'default';

switch ($template) {
	case 'default':
		echo $this->element('Quotations/default_form');
		break;
	case 'nursery':
		echo $this->element('Quotations/nursery_form');
		break;
	default:
		echo $this->element('Quotations/default_form');
		break;
}

?>
