<?php
App::uses('AppModel', 'Model');

class Franchise extends AppModel
{
	public $name = 'Franchise';

	public function getKeyValuePair($storeId)
	{
		$conditions = [
			'Franchise.is_active' => '1',
			'Franchise.is_deleted' => '0',
		];

		if ($storeId) {
			$conditions['Franchise.store_id'] = $storeId;
		}

		$franchiseList = $this->find('list', [
			'conditions' => $conditions]);

		return $franchiseList;
	}

	public function softDelete($franchiseId)
	{
		$data = [
			'Franchise.id' => $franchiseId,
			'Franchise.is_deleted' => 1,
			'Franchise.deleted_date' => date('Y-m-d'),
		];
		$this->save($data);
	}
}
