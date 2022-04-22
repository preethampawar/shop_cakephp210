<?php $this->start('quotation_menu'); ?>
<?php echo $this->element('quotation_menu'); ?>
<?php $this->end(); ?>

<?php
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
