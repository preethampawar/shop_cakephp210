

<?php

$searchEngineCode = $this->Session->read('Site.search_engine_code');
//debug($searchEngineCode);
$userLoggedIn = $this->Session->check('User.id');	
$inLoginPage = false;

if(($this->request->params['controller'] == 'users') and ($this->request->params['action'] == 'login')) {
	$inLoginPage = true;
}		

if(!empty($searchEngineCode) and (!$userLoggedIn) and (!$inLoginPage)) {
?>
<div id="searchDiv">	
	<div id="searchDivText">
		SEARCH
	</div>
	<?php
		echo $searchEngineCode;
	?>	
</div>
<?php
}
?>
<style type="text/css">
/* ------------ google search box style ---------- */
input.gsc-input {
	border-color: #666;
	border-radius: 2px;
	font-size: 16px;
	padding: 4px 6px;
}	
.cse input.gsc-search-button, input.gsc-search-button {
	background-color: #eeeeee;
	border: 1px solid #666;
	border-radius: 2px;
	color: #000;
	font-family: inherit;
	font-size: 13px;
	font-weight: bold;
	height: 30px;
	min-width: 54px;
	padding: 0 8px;
}
/* ------------ end of google search box style ---------- */
</style>
