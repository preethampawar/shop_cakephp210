<?php
App::uses('AppModel', 'Model');

class Testimonial extends AppModel
{
	var $name = 'Testimonial';
	var $belongsTo = ['Site'];
	var $useTable = 'testimonials';
}

?>
