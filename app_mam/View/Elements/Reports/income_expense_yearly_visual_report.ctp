<?php
if(!empty($monthlyTransactionsResults)) {
	$transactions['totalIncome'] = 0;
	$transactions['totalExpenses'] = 0;
	$transactions['subTotalIncome'] = 0;
	$transactions['subTotalExpenses'] = 0;
	$transactions['totalCashCredit'] = 0;
	$transactions['totalCashDebit'] = 0;
	
	$query='';
	$query.= ($this->data['Report']['category_id']) ? $categories[$this->data['Report']['category_id']].' Category - ' : ''; 
	$query.= ' Year '.$this->data['Report']['year']; 
	
	$cashDebit = array();
	$cashCredit = array();
	
	$data = array();
	
	$i=0;
	foreach($monthlyTransactionsResults as $row) {
		
		if($row['Report']['transaction_type'] == 'credit') {
			$cashCredit[$row['0']['Month']] = $row['0']['Amount'];
			$transactions['totalCashCredit'] = $transactions['totalCashCredit']+$row['0']['Amount'];
		}
		if($row['Report']['transaction_type'] == 'debit') {
			$cashDebit[$row['0']['Month']] = $row['0']['Amount'];
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
	
	$months = array('1'=>'Jan', '2'=>'Feb', '3'=>'Mar', '4'=>'Apr', '5'=>'May', '6'=>'Jun', '7'=>'Jul', '8'=>'Aug', '9'=>'Sep', '10'=>'Oct', '11'=>'Nov', '12'=>'Dec');
		
	$chartData = array();
	$lineChartData = array();
	
	$cCV = 0; // cash credit value
	$cDV = 0; // cash debit value
	
	$totalCredit=0;
	$totalDebit=0;
	
	$totalMonths = 12;
	if($this->data['Report']['year'] == date('Y')) {
		$totalMonths = date('m');
	}
	
	for($i=1; $i<=$totalMonths; $i++) {		
		$cC = (isset($cashCredit[$i])) ? $cashCredit[$i] : 0; // cash credit
		$cD = (isset($cashDebit[$i])) ? $cashDebit[$i] : 0; // cash debit
				
		$cCV+=$cC;
		$cDV+=$cD;
				
		$totalCredit+=($cC);
		$totalDebit+=($cD);
		
		$totalAmount = $totalCredit-$totalDebit;
		
		$month = $months[$i];		
		$chartData[] = "['".$month."', ".$cC.",".$cD."]";		
		$lineChartData[] = "['".$month."', ".($cC).",".($cD)."]";		
		$lineGrowthChartData[] = "['".$month."', ".$totalAmount."]";		
		$growthChartData[] = "['".$month."', ".$totalCredit.",".$totalDebit."]";			
	}
	
	// $chartData = array_reverse($chartData); 
	$chartData = implode(',', $chartData);		
	$growthChartData = implode(',', $growthChartData);	
	?>
	
	
	<div id="growth_chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Income & Expenses Chart ... </div>	
	<div id="chart_div" style="width: 900px; height: 500px; text-align:left;"> <br><br><br>Generating Performance Chart [Income Vs. Expenses] ... </div>
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Year - <?php echo $this->data['Report']['year'];?>');        
        data.addColumn('number', 'Income');
        data.addColumn('number', 'Expenses');
        data.addRows([
          <?php echo $chartData;?>
        ]);

        var options = {
          title: 'Performance Bar Chart [Income & Expenses] - <?php echo $query;?>',
          hAxis: {title: 'Year - <?php echo $this->data['Report']['year'];?>', titleTextStyle: {color: 'red'}},
		  vAxis: {title: 'Amount in <?php echo $this->Session->read('Company.currency');?>', titleTextStyle: {color: 'green'}},	
		  colors: ['#06700E','#D60F19']
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
		
		// Growth chart
		var data3 = new google.visualization.DataTable();
        data3.addColumn('string', 'Year');
        data3.addColumn('number', 'Income');
        data3.addColumn('number', 'Expenses');
        data3.addRows([
			<?php echo $growthChartData;?>
        ]);

        var options = {
			title: 'Income & Expenses Growth Report - <?php echo $query;?>',
			colors: ['#06700E', '#D60F19'],
			vAxis: {title: 'Amount in <?php echo $this->Session->read('Company.currency');?>', titleTextStyle: {color: 'green'}},	
			hAxis: {title: 'Year - <?php echo $this->data['Report']['year'];?>', titleTextStyle: {color: 'red'}},	
        };

        var chart = new google.visualization.AreaChart(document.getElementById('growth_chart_div'));
        chart.draw(data3, options);	
    }
    </script>

	
	
<?php
}
else {
?>
	<h2>Results:</h2>
	&nbsp;Data not available
<?php	
}
// Category report
if(!empty($categoryResults)) {
	$query='';
	$query.= ($this->data['Report']['category_id']) ? $categories[$this->data['Report']['category_id']].' Category - ' : ''; 
	$query.= ' Year '.$this->data['Report']['year']; 

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
		<div id="purchases_chart_div" style="width: 900px; height: 500px; float:left;">Generating Expenses Report</div>
		<div style="clear:both;"></div>
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
			title: 'Income & Expenses Report',
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

  
  
  
