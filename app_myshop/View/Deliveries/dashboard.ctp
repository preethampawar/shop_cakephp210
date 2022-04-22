<div class="text-start"><h5>Delivery Dashboard</h5>
</div>
<?php
App::uses('Order', 'Model');
$ordersCountStatus = [];
foreach($ordersCountByStatus as $row) {
	$ordersCountStatus[$row['orders']['status']] = (int)$row[0]['count'];
}

if(empty($ordersCountStatus)) {
	?>
		<div class="text-muted small">No Orders found.</div>
	<?php
return;
}
?>

<div id="donutchart" style="width: 100%; height: 600px;"></div>

<?php
$confirmedCount = $ordersCountStatus[Order::ORDER_STATUS_CONFIRMED] ?? 0;
$shippedCount = $ordersCountStatus[Order::ORDER_STATUS_SHIPPED] ?? 0;
$deliveredCount = $ordersCountStatus[Order::ORDER_STATUS_DELIVERED] ?? 0;
$cancelledCount = $ordersCountStatus[Order::ORDER_STATUS_CANCELLED] ?? 0;
$closedCount = $ordersCountStatus[Order::ORDER_STATUS_CLOSED] ?? 0;
$totalDeliveries = $deliveredCount+$cancelledCount+$closedCount;
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

	google.charts.load("current", {packages:["corechart"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Order Status', 'Count'],
			['New (<?= $confirmedCount ?>)', <?= $confirmedCount ?>],
			['Picked-up (<?= $shippedCount ?>)', <?= $shippedCount ?>],
			['Delivered (<?= $totalDeliveries ?>)', <?= $totalDeliveries ?>],
		]);

		var options = {
			title: 'Orders',
			legend: {position: 'top', maxLines: 7},
			fontSize: '11',
			pieHole: 0.3,
		};

		var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
		chart.draw(data, options);
	}
</script>
