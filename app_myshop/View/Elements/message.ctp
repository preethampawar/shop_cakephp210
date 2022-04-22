<?php
if (isset($errorMsg) and !empty($errorMsg)) {
	echo '<div class="alert alert-danger">' . $errorMsg . '</div>';
}
if (isset($successMsg) and !empty($successMsg)) {
	echo '<div class="alert alert-success">' . $successMsg . '</div>';
}
?>
