<?php
App::uses('AppModel', 'Model');

class Banner extends AppModel
{
	var $name = 'Banner';
	var $belongsTo = ['Site'];
	var $useTable = 'banners';
}

?>
