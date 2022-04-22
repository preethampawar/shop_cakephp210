<?php
$response = [
	'error' => !empty($error),
	'errorMsg' => $error,
	'successMsg' => $msg
];
if (!$error) {
	$response['orderEmailUrl'] = $orderEmailUrl;
}
echo json_encode($response);
