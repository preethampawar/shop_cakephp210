<?php
App::uses('Validation', 'Utility');

class UploadsController extends AppController
{

	public $name = 'Uploads';

	public function beforeFilter()
	{
		parent::beforeFilter();
	}

	public function index()
	{
		if ($this->request->is('post')) {
			//debug($this->request->data);
			$validFile = $this->validateFile($this->request->data['Upload']['fileinfo']);

			if (!$validFile) {
				$this->Flash->set('Invalid file selected');
				$this->redirect('/uploads/');
			}

			$this->processFile($this->request->data['Upload']['fileinfo']);

		}
	}

	private function processFile(&$fileInfo)
	{	
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileInfo['tmp_name']);
		debug($spreadsheet->getActiveSheet()->toArray(null, false, false, true));
		
		
		
	}

	private function validateFile(&$fileInfo)
	{
		$allowedFileType = [
			'application/vnd.ms-excel',
			'text/xls',
			'text/xlsx',
			'text/csv',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		];

		if (empty($fileInfo) || !isset($fileInfo['type'])) {
			return false;
		}
	
		if (!in_array($fileInfo['type'], $allowedFileType)) {
			return false;
		}

		return true;
	}

}