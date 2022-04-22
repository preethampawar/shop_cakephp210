<?php $this->start('reports_menu'); ?>
<?php echo $this->element('reports_menu'); ?>
<?php $this->end(); ?>

	<h1>Visual Reports</h1><br>
<?php
if (!empty($result)) {

	$height = 0;
	$header[] = "''";
	if ($this->data['Report']['showSales']) {
		$header[] = "'Sales'";
	}
	if ($this->data['Report']['showPurchases']) {
		$header[] = "'Purchases'";
	}
	if ($this->data['Report']['showProfitOnSale']) {
		$header[] = "'Profit on Sales'";
	}
	if ($this->data['Report']['showPredictedSaleValue']) {
		$header[] = "'Predicted Total Sales'";
	}
	if ($this->data['Report']['showPredictedProfitOnSale']) {
		$header[] = "'Predicted Total Profit On Sales'";
	}
	$header = implode(',', $header);
	?>
	<script>
		var reportPieData = [];
		var reportPieDataPredicted = [];
		var reportTopPerformingProductsByProfit = [];
		var reportTopPerformingProductsBySales = [];
		var reportData = [];
		reportData.push([<?php echo $header;?>]);
	</script>
	<?php
	$storePurchaseValue = 0;
	$storeSaleValue = 0;
	$storePredictedSaleValue = 0;
	$storeProfitValue = 0;
	$storePredictedProfitValue = 0;
	$storeClosingStockValue = 0;

	foreach ($result as $row) {

		$productID = $row['ProductStockReport']['product_id'];
		$productName = $row['ProductStockReport']['product_name'];
		$categoryName = $row['ProductStockReport']['category_name'];
		$productCategoryID = $row['ProductStockReport']['category_id'];
		$purchaseStock = $row['ProductStockReport']['purchase_qty'];
		$saleStock = $row['ProductStockReport']['sale_qty'];
		$breakageStock = $row['ProductStockReport']['breakage_qty'];
		$breakageValue = $row['ProductStockReport']['breakage_amount'];
		$closingStock = $row['ProductStockReport']['balance_qty'];
		$saleValue = $row['ProductStockReport']['sale_amount'];
		$purchaseValue = $row['ProductStockReport']['purchase_amount'];
		$unitSellingPrice = $row['Product']['unit_selling_price'];
		$profit = $row['ProductStockReport']['profit_amount'];
		$closingStockValue = $closingStock * $unitSellingPrice;
		$predictedSaleValue = $saleValue + $closingStockValue;
		$predictedTotalProfit = $predictedSaleValue - $purchaseValue - $breakageValue;
		$height += 50;

		$storePurchaseValue += $purchaseValue;
		$storeSaleValue += $saleValue;
		$storeProfitValue += $profit;
		$storeClosingStockValue += $closingStockValue;
		$storePredictedSaleValue += $predictedSaleValue;
		$storePredictedProfitValue += $predictedTotalProfit;


		if ($purchaseValue > 0) {

			// data for bar chart - product analytics
			$dataRow = ["'$productName'"];
			if ($this->data['Report']['showSales']) {
				$dataRow[] = $saleValue;
			}
			if ($this->data['Report']['showPurchases']) {
				$dataRow[] = $purchaseValue;
			}
			if ($this->data['Report']['showProfitOnSale']) {
				$dataRow[] = $profit;
			}
			if ($this->data['Report']['showPredictedSaleValue']) {
				$dataRow[] = $predictedSaleValue;
			}
			if ($this->data['Report']['showPredictedProfitOnSale']) {
				$dataRow[] = $predictedTotalProfit;
			}
			$dataRow = implode(',', $dataRow);

			// data for top performing product
			$topPerformingProductByProfit = "'$productName'" . ',' . $profit;
			$topPerformingProductBySales = "'$productName'" . ',' . $saleValue;
			?>

			<script>
				<?php if($saleValue > 0 && $profit > 0) { ?>
				reportData.push([<?php echo $dataRow;?>]);
				<?php } ?>

				<?php if($profit > 0) { ?>
				reportTopPerformingProductsByProfit.push([<?php echo $topPerformingProductByProfit;?>]);
				<?php } ?>

				reportTopPerformingProductsBySales.push([<?php echo $topPerformingProductBySales;?>]);
			</script>

			<?php
		}
	}
	?>
	<script>
		let performanceBarDataActual = [
			[
				'',
				'Sales ' + <?php echo $storeSaleValue;?>,
				'Purchases ' + <?php echo $storePurchaseValue;?>,
				'Closing ' + <?php echo $storeClosingStockValue;?>,
				'Profit ' + <?php echo $storeProfitValue;?>
			],
			[
				'Till Now - <?= date('d M Y') ?>',
				<?php echo $storeSaleValue;?>,
				<?php echo $storePurchaseValue;?>,
				<?php echo $storeClosingStockValue;?>,
				<?php echo $storeProfitValue;?>
			]
		];

		let performanceBarDataPredicted = [
			[
				'',
				'Sales ' + <?php echo $storeSaleValue;?>,
				'Purchases ' + <?php echo $storePurchaseValue;?>,
				'Profit ' + <?php echo $storePredictedProfitValue;?>
			],
			[
				'Till Now - <?= date('d M Y') ?>',
				<?php echo $storeSaleValue;?>,
				<?php echo $storePurchaseValue;?>,
				<?php echo $storePredictedProfitValue;?>
			]
		];
	</script>


	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	<?php
	if ($type == 'store_performance') {
		?>
		<!-- bar chart - store performance -->
		<script>
			google.charts.load('current', {'packages':['bar']});
			google.charts.setOnLoadCallback(drawPerformanceBarChart);

			function drawPerformanceBarChart() {
				var data = google.visualization.arrayToDataTable(performanceBarDataActual);

				var options = {
					chart: {
						title: 'Store Performance',
						subtitle: 'Sales, Purchases, Closing Stock and Profit on sales',
					},
					bars: 'vertical',
				};

				var chart = new google.charts.Bar(document.getElementById('barchart_performance_actual'));

				chart.draw(data, google.charts.Bar.convertOptions(options));
			}

			google.charts.setOnLoadCallback(drawPerformanceBarChartPredicted);

			function drawPerformanceBarChartPredicted() {
				var data = google.visualization.arrayToDataTable(performanceBarDataPredicted);

				var options = {
					chart: {
						title: 'Predicted - Store Performance',
						subtitle: 'Sales, Purchases and Profit on sales',
					}
				};

				var chart = new google.charts.Bar(document.getElementById('barchart_performance_predicted'));

				chart.draw(data, google.charts.Bar.convertOptions(options));
			}
		</script>

		<div class="row">
			<div class="col-xs-12">
				<div id="barchart_performance_actual" style="width: 100%; height: 500px;"></div>
				<br><hr><br>
				<div id="barchart_performance_predicted" style="width: 100%; height: 500px;"></div>
			</div>
		</div>
		<?php
	}
	?>

	<?php
	if ($type == 'top_performing_products') {
		?>
		<!-- pie chart - top performing products -->
		<script type="text/javascript">
			google.charts.load("current", {packages: ["corechart"]});
			google.charts.setOnLoadCallback(drawPieTopPerformingProductByProfitChart);
			console.log(reportTopPerformingProductsByProfit);

			//console.log(reportTopPerformingProductsBySales);

			function drawPieTopPerformingProductByProfitChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Performance by - Profit on sales');
				data.addColumn('number', 'Amount');
				data.addRows(reportTopPerformingProductsByProfit);

				var options = {
					title: 'Top performing products by - Profit on sales',
					sliceVisibilityThreshold: .035,
					chartArea: {left: 0, top: 20, width: '100%'},
					is3D: true,
				};

				var chart = new google.visualization.PieChart(document.getElementById('piechart_performance_by_profit_3d'));
				chart.draw(data, options);
			}

			google.charts.setOnLoadCallback(drawPieTopPerformingProductBySalesChart);

			function drawPieTopPerformingProductBySalesChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Performance by - Sales');
				data.addColumn('number', 'Amount');
				data.addRows(reportTopPerformingProductsBySales);

				var options = {
					title: 'Top performing products by - Sales',
					sliceVisibilityThreshold: .035,
					chartArea: {left: 0, top: 20, width: '100%'},
					is3D: true,
				};

				var chart = new google.visualization.PieChart(document.getElementById('piechart_performance_by_sales_3d'));
				chart.draw(data, options);
			}
		</script>
		<div class="row">
			<div class="col-xs-6">
				<div id="piechart_performance_by_profit_3d" style="width: 100%; height: 300px;"></div>
			</div>
			<div class="col-xs-6">
				<div id="piechart_performance_by_sales_3d" style="width: 100%; height: 300px;"></div>
			</div>
		</div>
		<?php
	}
	?>

	<?php
	if ($type == 'sales_purchases_profit') {
		?>
		<!-- Bar chart -->
		<h2>Sales, Purchases & Profit on sales</h2>
		<?php
		echo $this->Form->create();
		echo $this->Form->input('showSales', ['type' => 'checkbox', 'value' => 1]);
		echo $this->Form->input('showPurchases', ['type' => 'checkbox', 'value' => 1]);
		echo $this->Form->input('showProfitOnSale', ['type' => 'checkbox', 'value' => 1]);
		echo $this->Form->input('showPredictedSaleValue', ['type' => 'checkbox', 'value' => 1, 'label' => 'Show Predicted Total Sales']);
		echo $this->Form->input('showPredictedProfitOnSale', ['type' => 'checkbox', 'value' => 1, 'label' => 'Show Predicted Total Profit On Sales']);
		echo $this->Form->submit('Generate Report');
		echo $this->Form->end();
		?>
		<br>
		<script type="text/javascript">
			google.charts.load('current', {'packages': ['bar']});
			google.charts.setOnLoadCallback(drawBarChart);

			function drawBarChart() {
				var data = google.visualization.arrayToDataTable(reportData);

				var options = {
					chart: {
						title: 'Store Performance',
						subtitle: 'Sales, Purchases and Profit on sale',
					},
					bars: 'horizontal', // Required for Material Bar Charts.
					hAxis: {format: ''},
					axes: {
						x: {
							0: {side: 'top', label: 'Amount'} // Top x-axis.
						}
					},
					bar: {groupWidth: "80%"},
					legend: {position: "top"}
				};

				var chart = new google.charts.Bar(document.getElementById('barchart_material'));

				chart.draw(data, google.charts.Bar.convertOptions(options));
			}
		</script>


		<div class="table-responsive">
			<table class='table-sm small'>
				<tbody>
				<div id="barchart_material" style="width: 300%; height: <?php echo $height; ?>px; "></div>
				</tbody>
			</table>
		</div>

		<?php
	}
	?>
	<?php
} else {
	echo 'No products found';
}
?>
<br><br>
