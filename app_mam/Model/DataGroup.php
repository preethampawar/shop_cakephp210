<?php
App::uses('AppModel', 'Model');
class DataGroup extends AppModel {
    public $name = 'DataGroup';
	
	var $displayField = 'data_id';	
	var $belongsTo = array('Data', 'Group');		
}