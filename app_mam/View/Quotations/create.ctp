<?php
switch($template) {
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