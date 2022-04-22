<?php
App::uses('AppModel', 'Model');

class GroupPaperRate extends AppModel
{
	public $name = 'GroupPaperRate';

	var $belongsTo = ['Group'];
}
