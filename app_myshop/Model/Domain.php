<?php
App::uses('AppModel', 'Model');

class Domain extends AppModel
{
	var $name = 'Domain';
	var $belongsTo = ['Site'];
}
