<?php
$response = [
	'error' => !empty($error),
	'errorMsg' => $error,
	'successMsg' => $successMsg
];
echo json_encode($response);
