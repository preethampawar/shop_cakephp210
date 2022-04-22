<div class="text-start"><h5>Admin Dashboard</h5>
</div>
<?php
App::uses('Order', 'Model');
$ordersCountStatus = [];
foreach($ordersCountByStatus as $row) {
	$ordersCountStatus[$row['orders']['status']] = (int)$row[0]['count'];
}

//debug($ordersCountStatus);
?>

<div id="donutchart" style="width: 100%; height: 600px;"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

	google.charts.load("current", {packages:["corechart"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Order Status', 'Count'],
			['<?= Order::ORDER_STATUS_NEW ?> (<?= $ordersCountStatus[Order::ORDER_STATUS_NEW] ?? 0 ?>)', <?= $ordersCountStatus[Order::ORDER_STATUS_NEW] ?? 0 ?>],
			['<?= Order::ORDER_STATUS_CONFIRMED ?> (<?= $ordersCountStatus[Order::ORDER_STATUS_CONFIRMED] ?? 0 ?>)', <?= $ordersCountStatus[Order::ORDER_STATUS_CONFIRMED] ?? 0 ?>],
			['<?= Order::ORDER_STATUS_SHIPPED ?> (<?= $ordersCountStatus[Order::ORDER_STATUS_SHIPPED] ?? 0 ?>)', <?= $ordersCountStatus[Order::ORDER_STATUS_SHIPPED] ?? 0 ?>],
			['<?= Order::ORDER_STATUS_DELIVERED ?> (<?= $ordersCountStatus[Order::ORDER_STATUS_DELIVERED] ?? 0 ?>)', <?= $ordersCountStatus[Order::ORDER_STATUS_DELIVERED] ?? 0 ?>],
			['<?= Order::ORDER_STATUS_CLOSED ?> (<?= $ordersCountStatus[Order::ORDER_STATUS_CLOSED] ?? 0 ?>)', <?= $ordersCountStatus[Order::ORDER_STATUS_CLOSED] ?? 0 ?>],
			['<?= Order::ORDER_STATUS_CANCELLED ?> (<?= $ordersCountStatus[Order::ORDER_STATUS_CANCELLED] ?? 0 ?>)', <?= $ordersCountStatus[Order::ORDER_STATUS_CANCELLED] ?? 0 ?>],
		]);

		var options = {
			title: 'Orders',
			legend: {position: 'top', maxLines: 7},
			fontSize: '11',
			pieHole: 0.4,
		};

		var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
		chart.draw(data, options);
	}
</script>
