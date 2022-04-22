<?php $vaxisTitle = date('d M Y', strtotime($this->data['Report']['startdate'])).' - to - '.date('d M Y', strtotime($this->data['Report']['enddate']));  ?>
<?php
if(!empty($dailyTransactionsResults)) {
	
	$transactions['totalIncome'] = 0;
	$transactions['totalExpenses'] = 0;
	$transactions['subTotalIncome'] = 0;
	$transactions['subTotalExpenses'] = 0;
	
	$transactions['totalCashCredit'] = 0;
	$transactions['totalCashDebit'] = 0;	
		
	$cashDebit = array();
	$cashCredit = array();
	
	$data = array();
	
	$i=0;
	foreach($dailyTransactionsResults as $row) {
	
		if($row['Report']['transaction_type'] == 'credit') {
			$cashCredit[$row['Report']['date']] = $row['0']['Amount'];
			$transactions['totalCashCredit'] = $transactions['totalCashCredit']+$row['0']['Amount'];
		}
		if($row['Report']['transaction_type'] == 'debit') {
			$cashDebit[$row['Report']['date']] = $row['0']['Amount'];
			$transactions['totalCashDebit'] = $transactions['totalCashDebit']+$row['0']['Amount'];
		}				
		
		// calculate total income, expenses, pending payments
		if($row['Report']['transaction_type'] == 'debit') {					
			$transactions['totalExpenses'] = $transactions['totalExpenses']+$row['0']['Amount'];
		}
		else {
			$transactions['totalIncome'] = $transactions['totalIncome']+$row['0']['Amount'];
		}		
		$i++;
	}
	?>	
	
	<?php
	$thisD = $startD = strtotime($this->data['Report']['startdate']);
	$endD = strtotime($this->data['Report']['enddate']);	
	$daysCount = ($endD - $startD)/(24*60*60);	
	$chartData = array();
	
	$cCV = 0; // cash credit value
	$cDV = 0; // cash debit value
		
	$totalCredit=0;
	$totalDebit=0;
	$j=0;
	
	for($i=0; $i<=$daysCount;$i++) {
		$tmpDate = date('Y-m-d', $thisD);
		
		$cC = (isset($cashCredit[$tmpDate])) ? $cashCredit[$tmpDate] : 0; // cash credit
		$cD = (isset($cashDebit[$tmpDate])) ? $cashDebit[$tmpDate] : 0; // cash debit
					
		$totalCredit+=($cC);
		$totalDebit+=($cD);
		$totalAmount = $totalCredit-$totalDebit;
		
		$date = date('d M', $thisD);		
		$linechartdate = date('d M', $thisD);		
		if(!(empty($s) and empty($cC) and empty($p) and empty($cD))) {
			$chartData[] = "['".$date."',".$cC.",".$cD."]";
				$j++;
		}
		$lineChartData[] = "['".$linechartdate."', ".($cC).",".($cD)."]";		
		$growthChartData[] = "['".$linechartdate."', ".$totalCredit.",".$totalDebit."]";	
		$lineGrowthChartData[] = "['".$linechartdate."', ".$totalAmount."]";				
		
		$thisD = strtotime('+1 days', $thisD);
	}
	// $chartData = array_reverse($chartData); 
	$chartData = implode(',', $chartData);
	$lineChartData = implode(',', $lineChartData);
	$growthChartData = implode(',', $growthChartData);	
	$lineGrowthChartData = implode(',', $lineGrowthChartData);	
	?>	
	
	
	<div id="growth_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Income & Expenses Chart ... </div>
	<!-- <div id="line_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Income Vs. Expense Chart ... </div> -->
	<div id="performance_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Performance Chart ... </div>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Date');
			data.addColumn('number', 'Income');
			data.addColumn('number', 'Expenses');
			data.addRows([
			  <?php echo $chartData;?>
			]);

			var options = {
				title: 'Performance from <?php echo $vaxisTitle;?>',
				titlePosition: 'out',
				hAxis: {title: '<?php echo $vaxisTitle;?>',  titleTextStyle: {color: 'red'}},
				vAxis: {title: 'Amount in <?php echo $this->Session->read('Company.currency');?>',  titleTextStyle: {color: 'green'}},
				colors: ['#06700E', '#D60F19']
			};

			// var chart = new google.visualization.BarChart(document.getElementById('performance_chart_div'));
			var chart = new google.visualization.LineChart(document.getElementById('performance_chart_div'));
			chart.draw(data, options);
			
			// line chart
			var data2 = new google.visualization.DataTable();
			data2.addColumn('string', 'Year');
			data2.addColumn('number', 'Income');
			data2.addColumn('number', 'Expenses');
			data2.addRows([
				<?php echo $lineChartData;?>
			]);

			var options = {
				title: 'Income Vs. Expense - From <?php echo $vaxisTitle;?>',
				colors: ['#06700E', '#D60F19'],
				vAxis: {title: 'Amount in <?php echo $this->Session->read('Company.currency');?>', titleTextStyle: {color: 'green'}},	
				hAxis: {title: '<?php echo $vaxisTitle;?>', titleTextStyle: {color: 'red'}}	
			};

			// var chart = new google.visualization.ColumnChart(document.getElementById('line_chart_div'));
			// var chart = new google.visualization.AreaChart(document.getElementById('line_chart_div'));
			// chart.draw(data2, options);
			
			// Income & Expenses Growth chart
			var data3 = new google.visualization.DataTable();
			data3.addColumn('string', 'Date');
			data3.addColumn('number', 'Income');
			data3.addColumn('number', 'Expenses');
			data3.addRows([
				<?php echo $growthChartData;?>
			]);

			var options = {
				title: 'Income & Expenses Growth Report- <?php echo $vaxisTitle;?>',
				colors: ['#06700E', '#D60F19'],
				vAxis: {title: 'Amount in <?php echo $this->Session->read('Company.currency');?>', titleTextStyle: {color: 'green'}},	
				hAxis: {title: '<?php echo $vaxisTitle;?>', titleTextStyle: {color: 'red'}}	
			};

			var chart = new google.visualization.AreaChart(document.getElementById('growth_chart_div'));
			chart.draw(data3, options);	
	
		}
	</script>
	
<?php
}

// Category report
if(!empty($categoryResults)) {
	$query='';
	$query.= ($this->data['Report']['category_id']) ? $categories[$this->data['Report']['category_id']].' Category - ' : ''; 
	$query.= ' From '.date('d M Y', strtotime($this->data['Report']['startdate'])). ' to '.date('d M Y', strtotime($this->data['Report']['enddate'])); 

	$income = 0;
	$expenses = 0;
	
	$salesChartData = array();
	$purchasesChartData = array();

	$categorySales = array();
	$categoryPurchases = array();
	foreach($categoryResults as $row) {
		if($row['Report']['transaction_type'] == 'credit') {
			$income+=$row[0]['Amount'];
			$categorySales[$row['Report']['category_name']] = $row[0]['Amount'];
			$salesChartData[] = "['".$row['Report']['category_name']." - ".$this->Number->currency($row[0]['Amount'], $CUR)."', ".$row[0]['Amount']."]";
		}
		if($row['Report']['transaction_type'] == 'debit') {
			$expenses+=$row[0]['Amount'];
			$categoryPurchases[$row['Report']['category_name']] = $row[0]['Amount'];
			$purchasesChartData[] = "['".$row['Report']['category_name']." - ".$this->Number->currency($row[0]['Amount'], $CUR)."', ".$row[0]['Amount']."]";
		}
	}
	$salesChartData = implode(',',$salesChartData);
	$purchasesChartData = implode(',',$purchasesChartData);
	?>
	
	<div id="income_expenses_chart_div" style="width: 900px; height: 500px;">Generating Income & Expenses Report</div>	
	<div>
		<div id="sales_chart_div" style="width: 900px; height: 500px; float:left;">Generating Income Report</div>
		<div id="purchases_chart_div" style="width: 900px; height: 500px;float:left">Generating Expenses Report</div>
		<div class="clear"></div>
	</div>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			// income and expense report
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Task');
			data.addColumn('number', 'Hours per Day');
			//data.addRows(['Income', <?php echo $income;?>], ['Expense', <?php echo $expenses;?>]);
			data.addRows([
							['Income - <?php echo $this->Number->currency($income, $CUR);?>', <?php echo $income;?>], 
							['Expenses - <?php echo $this->Number->currency($expenses, $CUR);?>', <?php echo $expenses;?>]
						]); 

			var options = {
				title: 'Income & Expenses Report: <?php echo $vaxisTitle;?>',
				is3D: true
			};

			var chart = new google.visualization.PieChart(document.getElementById('income_expenses_chart_div'));
			chart.draw(data, options);
			
			// income report
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Task');
			data.addColumn('number', 'Hours per Day');
			data.addRows([
			  <?php echo $salesChartData;?>
			]);

			var options = {
				title: 'Income Report: <?php echo $query.' - '.$this->Number->currency($income, $CUR);?>',
				is3D: true
			};

			var chart = new google.visualization.PieChart(document.getElementById('sales_chart_div'));
			chart.draw(data, options);
			
			// expenses report
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Task');
			data.addColumn('number', 'Hours per Day');
			data.addRows([
				<?php echo $purchasesChartData;?>
			]);

			var options = {
				title: 'Expenses Report: <?php echo $query.' - '.$this->Number->currency($expenses, $CUR);?>',
				is3D: true
		};

		var chart = new google.visualization.PieChart(document.getElementById('purchases_chart_div'));
		chart.draw(data, options);		
	}
    </script>

<?php
}
?>

  
  
  
