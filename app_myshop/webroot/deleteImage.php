<?php
header('Access-Control-Allow-Origin: *');
error_reporting(E_ALL);
ini_set("display_errors", 1);

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

function deleteImage()
{
    try {
        $data = $_GET;
        $images = $data['images'] ?? null;

        if (empty($images)) {
            sendResponse(['error' => true, 'msg' => 'Invalid Request']);
        }

        $images = base64_decode($images);

        if ($images = json_decode($images, true, 512, JSON_THROW_ON_ERROR)) {
            foreach ($images as $imagePath) {
                $data['action'] = 'delete';
                $data['delete_path'] = $imagePath;

                $imageUploader = new ImageUploader($data);
                $imageDeletedPath = $imageUploader->delete();
            }

            $msg = 'Image is successfully deleted';
            $data = [
                'error' => false,
                'msg' => $msg,
            ];
        }
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
    echo json_encode($data);
    exit;
}

deleteImage();
