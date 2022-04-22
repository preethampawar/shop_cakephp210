<?php
App::uses('AppModel', 'Model');
class Data extends AppModel {
    public $name = 'Data';
	
	var $useTable = 'data';
	var $belongsTo = array('Category');
	
    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty'
        ),
		'keyword' => array(
            'rule' => 'notEmpty'
        ),
        'body' => array(
            'rule' => 'notEmpty'
        )
    );
}
?>