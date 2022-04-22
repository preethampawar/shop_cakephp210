<?php
class Bill extends AppModel {
	public $useTable = 'bills';

	public $hasMany = ['Sale'];
}
