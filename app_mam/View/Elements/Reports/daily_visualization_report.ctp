<?php $vaxisTitle = date('d M Y', strtotime($this->data['Report']['startdate'])).' - to - '.date('d M Y', strtotime($this->data['Report']['enddate']));  ?>
<?php
if(!empty($dailyTransactionsResults)) {
	
	$transactions['totalIncome'] = 0;
	$transactions['totalExpenses'] = 0;
	$transactions['subTotalIncome'] = 0;
	$transactions['subTotalExpenses'] = 0;
	$transactions['totalSales'] = 0;
	$transactions['totalPurchases'] = 0;
	$transactions['totalCashCredit'] = 0;
	$transactions['totalCashDebit'] = 0;	
	$transactions['pendingIncome'] = 0;
	$transactions['pendingExpenses'] = 0;
	$transactions['pendingSales'] = 0;
	$transactions['pendingPurchases'] = 0;
	$transactions['pendingCashCredit'] = 0;
	$transactions['pendingCashDebit'] = 0;
	
	$cashDebit = array();
	$cashCredit = array();
	$sales = array();
	$purchases = array();
	
	$data = array();
	
	$i=0;
	foreach($dailyTransactionsResults as $row) {
		if($row['Report']['business_type'] == 'cash') {
			if($row['Report']['transaction_type'] == 'credit') {
				$cashCredit[$row['Report']['date']] = $row['0']['Amount'];
				$transactions['totalCashCredit'] = $transactions['totalCashCredit']+$row['0']['Amount'];
				$transactions['pendingCashCredit'] = $transactions['pendingCashCredit']+$row['0']['PendingAmount'];
			}
			if($row['Report']['transaction_type'] == 'debit') {
				$cashDebit[$row['Report']['date']] = $row['0']['Amount'];
				$transactions['totalCashDebit'] = $transactions['totalCashDebit']+$row['0']['Amount'];
				$transactions['pendingCashDebit'] = $transactions['pendingCashDebit']+$row['0']['PendingAmount'];
			}
		}
		
		if($row['Report']['business_type'] == 'sale') {
			$sales[$row['Report']['date']] = $row['0']['Amount'];
			$transactions['totalSales'] = $transactions['totalSales'] + $row['0']['Amount'];
			$transactions['pendingSales'] = $transactions['pendingSales']+$row['0']['PendingAmount'];
		}
		
		if($row['Report']['business_type'] == 'purchase') {
			$purchases[$row['Report']['date']] = $row['0']['Amount'];
			$transactions['totalPurchases'] = $transactions['totalPurchases'] + $row['0']['Amount'];
			$transactions['pendingPurchases'] = $transactions['pendingPurchases']+$row['0']['PendingAmount'];
		}		
		
		// calculate total income, expenses, pending payments
		if($row['Report']['transaction_type'] == 'debit') {					
			$transactions['totalExpenses'] = $transactions['totalExpenses']+$row['0']['Amount'];
			$transactions['subTotalExpenses'] = $transactions['subTotalExpenses']+$row['0']['PaymentAmount'];
			$transactions['pendingExpenses'] = $transactions['pendingExpenses']+$row['0']['PendingAmount'];
		}
		else {
			$transactions['totalIncome'] = $transactions['totalIncome']+$row['0']['Amount'];
			$transactions['subTotalIncome'] = $transactions['subTotalIncome']+$row['0']['PaymentAmount'];
			$transactions['pendingIncome'] = $transactions['pendingIncome']+$row['0']['PendingAmount'];
		}		
		$i++;
	}
	?>
	
	<?php echo $this->element('Reports/simple_report', array('transactions'=>$transactions));?>
	
	
	
	
	<?php
	
	//debug($transactions);
	
	$thisD = $startD = strtotime($this->data['Report']['startdate']);
	$endD = strtotime($this->data['Report']['enddate']);	
	$daysCount = ($endD - $startD)/(24*60*60);	
	$chartData = array();
	
	$cCV = 0; // cash credit value
	$cDV = 0; // cash debit value
	$sV = 0; // sales value
	$pV = 0; // purchases value
	
	$totalCredit=0;
	$totalDebit=0;
	$j=0;
	
	for($i=0; $i<=$daysCount;$i++) {
		$tmpDate = date('Y-m-d', $thisD);
		
		$cC = (isset($cashCredit[$tmpDate])) ? $cashCredit[$tmpDate] : 0; // cash credit
		$cD = (isset($cashDebit[$tmpDate])) ? $cashDebit[$tmpDate] : 0; // cash debit
		$s = (isset($sales[$tmpDate])) ? $sales[$tmpDate] : 0; // sale
		$p = (isset($purchases[$tmpDate])) ? $purchases[$tmpDate] : 0; // purchase
			
		$totalCredit+=($cC+$s);
		$totalDebit+=($cD+$p);
		$totalAmount = $totalCredit-$totalDebit;
		
		$date = date('d M', $thisD);		
		$linechartdate = date('d M', $thisD);		
		if(!(empty($s) and empty($cC) and empty($p) and empty($cD))) {
			$chartData[] = "['".$date."', ".$s.",".$cC.",".$p.",".$cD."]";
				$j++;
		}
		$lineChartData[] = "['".$linechartdate."', ".($cC+$s).",".($cD+$p)."]";		
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
	<br>
	<h4>Below charts are generated based on total amount </h4>
	<div id="line_growth_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Growth Chart ... </div>
	<div id="growth_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Income & Expenses Chart ... </div>
	<div id="line_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Income Vs. Expense Chart ... </div>
	<div id="performance_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Performance Chart ... </div>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Date');
			data.addColumn('number', 'Sales');
			data.addColumn('number', 'Cash Credit');
			data.addColumn('number', 'Purchases');
			data.addColumn('number', 'Cash Debit');
			data.addRows([
			  <?php echo $chartData;?>
			]);

			var options = {
				title: 'Performance from <?php echo $vaxisTitle;?>',
				titlePosition: 'out',
				hAxis: {title: '<?php echo $vaxisTitle;?>',  titleTextStyle: {color: 'red'}},
				vAxis: {title: 'Amount in <?php echo $this->Session->read('Company.currency');?>',  titleTextStyle: {color: 'green'}},
				colors: ['#06700E', '#05B014', '#D60F19', '#FCA862']
			};

			// var chart = new google.visualization.BarChart(document.getElementById('performance_chart_div'));
			var chart = new google.visualization.AreaChart(document.getElementById('performance_chart_div'));
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

			// var chart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
			var chart = new google.visualization.AreaChart(document.getElementById('line_chart_div'));

			chart.draw(data2, options);
			
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

			// Line Growth chart
			var data4 = new google.visualization.DataTable();
			data4.addColumn('string', 'Year');
			data4.addColumn('number', 'Growth');
			data4.addRows([
				<?php echo $lineGrowthChartData;?>
			]);

			var options = {
				title: 'Business Growth Report - <?php echo $vaxisTitle;?>',			
				vAxis: {title: 'Amount in <?php echo $this->Session->read('Company.currency');?>', titleTextStyle: {color: 'green'}},	
				hAxis: {title: '<?php echo $vaxisTitle;?>', titleTextStyle: {color: 'red'}},
				colors: ['<?php echo ($totalAmount > 0) ? '#06700E' : '#D60F19';?>']
			};

			var chart = new google.visualization.LineChart(document.getElementById('line_growth_chart_div'));
			chart.draw(data4, options);			
		}
	</script>
	
<?php
}

// Category report
if(!empty($categoryResults)) {
	$business_options = Configure::read('BusinessTypes');
	$query='';
	//$query.= ($this->data['Report']['pending_payment']) ? 'Pending Payments - ' : ''; 
	$query.= ($this->data['Report']['business_type']) ? $business_options[$this->data['Report']['business_type']].' Account - ' : ''; 
	$query.= ($this->data['Report']['category_id']) ? $categoriesList[$this->data['Report']['category_id']].' Category - ' : ''; 
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
				is3D: false
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
				is3D: false
		};

		var chart = new google.visualization.PieChart(document.getElementById('purchases_chart_div'));
		chart.draw(data, options);		
	}
    </script>

<?php
}
?>

  
  
  
