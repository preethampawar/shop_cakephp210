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
