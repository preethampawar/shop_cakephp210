<?php
App::uses('AppModel', 'Model');

class Image extends AppModel
{
	var $name = 'Image';

	var $belongsTo = ['Product', 'Category'];
}

?>
