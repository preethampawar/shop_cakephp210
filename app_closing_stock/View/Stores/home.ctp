<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12 text-center">

			<h1>Welcome to <br><?php echo strtoupper($this->Session->read('Store.name')); ?></h1>
			<br>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Closing Stock</h4>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a href="/sales/addClosingStockMobile" class="list-group-item"><i class="fa fa-plus-circle"></i>
							Add Closing Stock</a>
						<a href="/sales/viewClosingStock" class="list-group-item"><i class="fas fa-clipboard-list"></i> Show
							Closing Stock Report</a>
					</div>
				</div>
			</div>

			<div class="panel panel-default mt-4">
				<div class="panel-heading">
					<h4>Invoices</h4>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a href="/invoices/add" class="list-group-item"><i class="fa fa-plus-circle"></i> Add
							Invoice</a>
						<a href="/invoices/" class="list-group-item"><i class="fa fa-list-alt"></i> Invoice List</a>
					</div>
				</div>
			</div>

			<div class="panel panel-default mt-4">
				<div class="panel-heading">
					<h4>Cash Book</h4>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a href="/cashbook/" class="list-group-item"><i class="fa fa-indian-rupee"></i> Manage Expenses</a>
						<a href="/reports/cashbookReport" class="list-group-item"><i class="fas fa-clipboard-list"></i> Expenses Report</a>
					</div>
				</div>
			</div>

			<div class="panel panel-default mt-4">
				<div class="panel-heading">
					<h4>Transactions</h4>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a href="/transactions/" class="list-group-item"><i class="fa fa-indian-rupee"></i> Manage Transactions</a>
						<a href="/TransactionCategories/" class="list-group-item"><i class="fa fa-list-alt"></i> Manage Transaction Categories</a>
						<a href="/reports/transactionsReport" class="list-group-item"><i class="fas fa-clipboard-list"></i> Transactions Report</a>
					</div>
				</div>
			</div>

			<div class="panel panel-default mt-4">
				<div class="panel-heading">
					<h4>Products</h4>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a href="/products/" class="list-group-item"><i class="fa fa-folder-tree"></i> Products List</a>
					</div>
				</div>
			</div>

			<div class="panel panel-default mt-4">
				<div class="panel-heading">
					<h4>Stock Reports</h4>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a class="list-group-item" href="/reports/dayWiseStockReport"><i class="fas fa-clipboard-list"></i>
							Show Daywise Stock Report</a>
						<a class="list-group-item" href="/reports/completeStockReport"><i class="fas  fa-clipboard-list"></i>
							Show Complete Stock Report</a>
					</div>
				</div>
			</div>

			<div class="panel panel-default mt-4">
				<div class="panel-heading">
					<h4>Visual Reports</h4>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<a class="list-group-item" href="/reports/completeStockReportChart/top_performing_products"><i
								class="fas fa-chart-pie"></i>
							Top Performing Products</a>
						<a class="list-group-item" href="/reports/completeStockReportChart/sales_purchases_profit"><i
								class="fas fa-chart-line"></i>
							Sales, Purchases & Profit on sales</a>
						<a class="list-group-item" href="/reports/completeStockReportChart/store_performance"><i
									class="fas fa-chart-bar"></i>
							My Store Performance</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br><br>

