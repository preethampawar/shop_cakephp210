<?php
App::uses('AppModel', 'Model');

class DeliveryUser extends AppModel
{
	var $name = 'DeliveryUser';
	var $belongsTo = ['Site'];
}
