
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12 text-center">
			<br><br>
			<h1>Welcome to  <?php echo strtoupper($this->Session->read('Store.name'));?></h1>
			<br>
			<div class="panel panel-default hidden">
				<div class="panel-heading">
					<h2 class="panel-title">Reports</h2>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-2 col-md-3 col-lg-3">
							<h2>Stock Reports</h2>
							<div class="list-group">
								<?php echo $this->Html->link('Day Wise Stock Report', array('controller'=>'reports', 'action'=>'dayWiseStockReport'), array('class'=>'list-group-item'));?>
								<?php echo $this->Html->link('Month Wise Stock Report', array('controller'=>'reports', 'action'=>'monthWiseStockReport'), array('class'=>'list-group-item'));?>
								<?php echo $this->Html->link('Complete Stock Report', array('controller'=>'reports', 'action'=>'completeStockReport'), array('class'=>'list-group-item'));?>
							</div>
							
							<h2>Income & Expense Reports</h2>
							<div class="list-group">
								<?php echo $this->Html->link('Day Wise Stock Report', array('controller'=>'reports', 'action'=>'incomeAndExpensesReport'), array('class'=>'list-group-item'));?>
							</div>
						</div>
						<div class="col-sm-2 col-md-3 col-lg-3">
							<h2>Purchase Reports</h2>
							<div class="list-group">							
								<?php echo $this->Html->link('Purchase Report', array('controller'=>'reports', 'action'=>'purchaseReport'), array('class'=>'list-group-item'));?>
							</div>
							
							<h2>Sale Reports</h2>
							<div class="list-group">
								<?php echo $this->Html->link('Sales Report', array('controller'=>'reports', 'action'=>'salesReport'), array('class'=>'list-group-item'));?>
							</div>
							
							<h2>Cash Book Reports</h2>
							<div class="list-group">
								<?php echo $this->Html->link('Cash Book Report', array('controller'=>'reports', 'action'=>'cashbookReport'), array('class'=>'list-group-item'));?>
							</div>
						</div>
						<div class="col-sm-2 col-md-3 col-lg-3">
							<h2>Invoice Reports</h2>
							<div class="list-group">								
								<?php echo $this->Html->link('Invoice Report', array('controller'=>'reports', 'action'=>'invoiceReport'), array('class'=>'list-group-item'));?>
								<?php echo $this->Html->link('Invoice DD Report', array('controller'=>'reports', 'action'=>'invoiceDdReport'), array('class'=>'list-group-item'));?>
							</div>
						</div>
					</div>					
				</div>			
			</div>
		</div>

	</div>
	

	<div class="row hidden">
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Products</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">					
						<?php echo $this->Html->link('Show all Products', array('controller'=>'product_categories', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Create New Product', array('controller'=>'product_categories', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Default Price List', array('controller'=>'default_price_list', 'action'=>'index'), array('class'=>'list-group-item'));?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Invoices</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">					
						<?php echo $this->Html->link('Show all Invoices', array('controller'=>'invoices', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Create New Invoice', array('controller'=>'invoices', 'action'=>'add'), array('class'=>'list-group-item'));?>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Purchases</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">			
						<?php echo $this->Html->link('Show all Purchases', array('controller'=>'purchases', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Add New Purchase', array('controller'=>'purchases', 'action'=>'add'), array('class'=>'list-group-item'));?>
					</div>
				</div>
			</div>
		</div>

		<div class="clearfix visible-sm-block"></div>
		
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Closing Stock</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">					
						<?php echo $this->Html->link('Show Closing Stock Report', array('controller'=>'sales', 'action'=>'viewClosingStock'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Add Closing Stock', array('controller'=>'sales', 'action'=>'addClosingStock'), array('class'=>'list-group-item'));?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="clearfix visible-md-block visible-lg-block"></div>
		
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Sales</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">					
						<?php echo $this->Html->link('Show all Sales', array('controller'=>'sales', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Add New Sale', array('controller'=>'sales', 'action'=>'add'), array('class'=>'list-group-item'));?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Breakage Stock</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">					
						<?php echo $this->Html->link('Show Breakage Stock', array('controller'=>'breakages', 'action'=>'addBreakageStock'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Add Breakage Stock', array('controller'=>'breakages', 'action'=>'viewBreakageStock'), array('class'=>'list-group-item'));?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="clearfix visible-sm-block"></div>
		
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Cash Book</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">					
						<?php echo $this->Html->link('Show all Expenses/Income', array('controller'=>'cash', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Add new Expense/Income', array('controller'=>'cash', 'action'=>'index'), array('class'=>'list-group-item'));?>
					</div>
				</div>			
			</div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Employees</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">
						<?php echo $this->Html->link('Show all Employees', array('controller'=>'employees', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Add New Employee', array('controller'=>'employees', 'action'=>'add'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Show Salary Records', array('controller'=>'salaries', 'action'=>'index'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Make Salary Payment', array('controller'=>'salaries', 'action'=>'add'), array('class'=>'list-group-item'));?>
					</div>
				</div>			
			</div>
		</div>
		
		<div class="clearfix visible-md-block visible-lg-block"></div>
		
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h2 class="panel-title">Templates</h2>
				</div>
				<div class="panel-body">
					<div class="list-group">					
						<?php echo $this->Html->link('Product List Template', array('controller'=>'stores', 'action'=>'downloadProductListTemplate'), array('class'=>'list-group-item'));?>
						<?php echo $this->Html->link('Closing Stock Template', array('controller'=>'stores', 'action'=>'downloadClosingStockTemplate'), array('class'=>'list-group-item'));?>
					</div>
				</div>			
			</div>
		</div>

		<div class="clearfix visible-sm-block"></div>
		
		
	</div>
	
</div>
<table style="width:900px; display: none;">
	<tr>
		<td>
			<h2>Products</h2>
			<p><?php echo $this->Html->link('Show all Categories', array('controller'=>'product_categories', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Create New Category/Product', array('controller'=>'product_categories', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Default Price List', array('controller'=>'default_price_list', 'action'=>'index'));?></p>
		</td>
		<td>
			<h2>Invoices</h2>
			<p><?php echo $this->Html->link('Show all Invoices', array('controller'=>'invoices', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Create New Invoice', array('controller'=>'invoices', 'action'=>'add'));?></p>
		</td>
		<td>
			<h2>Purchases</h2>
			<p><?php echo $this->Html->link('Show all Purchases', array('controller'=>'purchases', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Add New Purchase', array('controller'=>'purchases', 'action'=>'add'));?></p>
		</td>
		<td>
			<h2>Sales</h2>
			<p><?php echo $this->Html->link('Show all Sales', array('controller'=>'sales', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Add New Sale', array('controller'=>'sales', 'action'=>'add'));?></p>
		
		</td>
	</tr>
	<tr>
		<td>
			<h2>Closing Stock</h2>
			<p><?php echo $this->Html->link('Show Closing Stock Report', array('controller'=>'sales', 'action'=>'viewClosingStock'));?></p>
			<p><?php echo $this->Html->link('Add Closing Stock', array('controller'=>'sales', 'action'=>'addClosingStock'));?></p>			
		</td>
		<td>			
			<h2>Breakage Stock</h2>
			<p><?php echo $this->Html->link('Show Breakage Stock', array('controller'=>'breakages', 'action'=>'addBreakageStock'));?></p>
			<p><?php echo $this->Html->link('Add Breakage Stock', array('controller'=>'breakages', 'action'=>'viewBreakageStock'));?></p>
		</td>
		
		<td colspan='2'></td>
	</tr>
	<tr>
		<td>
			<h2>Employees</h2>
			<p><?php echo $this->Html->link('Show all Employees', array('controller'=>'employees', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Add New Employee', array('controller'=>'employees', 'action'=>'add'));?></p>		
		</td>
		<td>
			<h2>Salaries</h2>
			<p><?php echo $this->Html->link('Show Salary Records', array('controller'=>'salaries', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Make Salary Payment', array('controller'=>'salaries', 'action'=>'add'));?></p>		
		</td>
		<td>
			<h2>Expenses/Income</h2>
			<p><?php echo $this->Html->link('Show all Expenses/Income', array('controller'=>'cash', 'action'=>'index'));?></p>
			<p><?php echo $this->Html->link('Add new Expense/Income', array('controller'=>'cash', 'action'=>'index'));?></p>		
		</td>		
	</tr>
	<tr>
		<td>
			<h2>Templates</h2>
			<p><?php echo $this->Html->link('Product List Template', array('controller'=>'stores', 'action'=>'downloadProductListTemplate'));?></p>
			<p><?php echo $this->Html->link('Closing Stock Template', array('controller'=>'stores', 'action'=>'downloadClosingStockTemplate'));?></p>		
		</td>
		<td colspan='3'>&nbsp;</td>
	</tr>
</table>

