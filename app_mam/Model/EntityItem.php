<?php
App::uses('AppModel', 'Model');
class EntityItem extends AppModel {
    public $name = 'EntityItem';
	var $belongsTo = array('Quotation');
	
}
?>