<?php 
if(isset($errorMsg) and !empty($errorMsg))
{
	echo '<p class="error">'.$errorMsg.'</p>';
}
if(isset($successMsg) and !empty($successMsg))
{
	echo '<p class="success">'.$successMsg.'</p>';
} 
?>