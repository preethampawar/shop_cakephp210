<?php
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set("display_errors", 1);

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

function uploadImage()
{
    try {
        $data = $_POST;
		
		if (empty($data)) {
			throw new Exception('No image selected');
		}
        
		$data['action'] = 'post';
        $imageUploader = new ImageUploader($data);
        $imageUploadPath = $imageUploader->upload();
        $msg = 'Image is successfully uploaded';
        $data = [
            'error' => false,
            'msg' => $msg,
            'imagePath' => $imageUploadPath,
        ];
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $data = [
            'error' => true,
            'msg' => $msg,
        ];
    }

    sendResponse($data);
}

function sendResponse($data)
{
    header('Content-type:application/json;charset=utf-8');
    echo json_encode( $data );
}

uploadImage();

