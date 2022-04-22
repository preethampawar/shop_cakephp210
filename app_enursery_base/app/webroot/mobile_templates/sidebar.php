<?php
header('Access-Control-Request-Headers: X-Requested-With, accept, content-type');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Origin: *');
?>
<div class="scrollable">
  <h1 class="scrollable-header app-name">Mobile Angular UI <small>1.2</small></h1>  
	<div class="scrollable-content">
		<div class="list-group" ui-turn-off='uiSidebarLeft'>
			<a class="list-group-item" href="#/">Home<i class="fa fa-chevron-right pull-right"></i></a>
			<a class="list-group-item" ng-repeat="row in categories.result" href="#/products/{{site_id}}/{{ row.Category.id }}">
				{{ row.Category.name }} <i class="fa fa-chevron-right pull-right"></i>
			</a>	
		</div>
	</div>
</div>
