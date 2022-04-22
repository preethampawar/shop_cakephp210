<br/>
<?php
echo $this->Form->button('&laquo; back', array('onclick'=>'history.back()', 'escape'=>false));
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
echo $this->Html->link('Download', array('controller'=>'quotations', 'action'=>'download', $quotationID), array('class'=>'button small grey'));
?>
<?php
$template = (isset($quotationInfo['Quotation']['template'])) ? $quotationInfo['Quotation']['template'] : 'default';
switch($template) {
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