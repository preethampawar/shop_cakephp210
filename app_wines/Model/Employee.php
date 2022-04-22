<?php
App::uses('AppModel', 'Model');
class Employee extends AppModel {
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Name is required'
            )
        )
    );
	
}