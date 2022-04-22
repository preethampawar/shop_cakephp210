<?php 
if(($this->Session->read('UserCompany.user_level') == 3) or ($this->Session->read('UserCompany.user_level') == 2)) { 
?> 
	<div class="black">  
		<ul id="mega-menu-3" class="mega-menu">
			<li><?php echo $this->Html->link('Home', '/');?></li>
			
			<?php if($this->Session->read('UserCompany.user_level') == 3) { ?>
			<li>
				<?php echo $this->Html->link('Categories', '#');?>
				<ul>
					<li><?php echo $this->Html->link('New Category', array('controller'=>'categories', 'action'=>'add'));?></li>
					<li><?php echo $this->Html->link('List All Categories', '/categories/');?></li>
				</ul>
			</li>			
			<li>
				<?php echo $this->Html->link('Stock', '#');?>
				<ul>
					<li><?php echo $this->Html->link('New Stock', '#');?>
						<ul>
							<li><?php echo $this->Html->link('Add New Stock', array('controller'=>'inventory', 'action'=>'add'));?></li>
							<li><?php echo $this->Html->link('Show Stock Updates', '/inventory/');?></li>											
						</ul>
					</li>
					<li><?php echo $this->Html->link('Damaged Stock', '#');?>	
						<ul>
							<li><?php echo $this->Html->link('Add Damaged Stock', array('controller'=>'inventory', 'action'=>'addDamagedStock'));?></li>
							<li><?php echo $this->Html->link('Show Damaged Stock', '/inventory/showDamagedStock/');?></li>			
						</ul>
					</li>		
				</ul>
			</li>						
			<?php 
			}
			?>
			<li>
				<?php echo $this->Html->link('Purchase Book', '#');?>
				<ul>
					<li><?php echo $this->Html->link('New Purchase', array('controller'=>'purchases', 'action'=>'add'));?></li>
					<li><?php echo $this->Html->link('List All Purchases', '/purchases/');?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link('Sales Book', '#');?>
				<ul>
					<li><?php echo $this->Html->link('New Sale', array('controller'=>'sales', 'action'=>'add'));?></li>
					<li><?php echo $this->Html->link('List All Sales', '/sales/');?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link('Cash Book', '#');?>
				<ul>
					<li><?php echo $this->Html->link('Add New Cash Record', array('controller'=>'cash', 'action'=>'add'));?></li>
					<li><?php echo $this->Html->link('List All Cash Transactions', '/cash/');?></li>
				</ul>
			</li>	
			<li>
				<?php echo $this->Html->link('Extras', '#');?>
				<ul>
					<?php if($this->Session->read('UserCompany.user_level') == 3) { ?>						
					<li><?php echo $this->Html->link('Users', '#');?>						
						<ul>
							<li><?php echo $this->Html->link('Invite User', array('controller'=>'users', 'action'=>'inviteUser'));?></li>
							<li><?php echo $this->Html->link('Show All Users', '/users/');?></li>
						</ul>							
					</li>
					<li><?php echo $this->Html->link('Groups', '#');?>
						<ul>
							<li><?php echo $this->Html->link('Create New Group', array('controller'=>'groups', 'action'=>'add'));?></li>		
							<li><?php echo $this->Html->link('Show All Groups', '/groups/');?></li>
						</ul>
					</li>
					<?php } ?>
					<li><?php echo $this->Html->link('Quotations', '#');?>
						<ul>
						<li><?php echo $this->Html->link('Create New Quotation', array('controller'=>'quotations', 'action'=>'selectTemplate'));?></li>
						<li><?php echo $this->Html->link('Show All Quotations', '/quotations/');?></li>
						</ul>
					</li>	
				</ul>
			</li>				
			<li>
				<?php echo $this->Html->link('Reports', '#');?>
				<ul>
					<li><?php echo $this->Html->link('General Report', array('controller'=>'reports', 'action'=>'index'));?></li>
					<li><?php echo $this->Html->link('Day to Day Analytics', array('controller'=>'reports', 'action'=>'generateVisualizationDailyReport'));?></li>
					<li><?php echo $this->Html->link('Yearly Analytics', array('controller'=>'reports', 'action'=>'generateVisualizationMonthlyReport'));?></li>
					<li><?php echo $this->Html->link('Monthly Stock Report', array('controller'=>'reports', 'action'=>'showStockReport'));?></li>
				</ul>
			</li>
			
		</ul>
	</div>

<?php
} 
?>

<?php 
if($this->Session->read('UserCompany.user_level') == 1) { 
?> 
	<div class="black">  
		<ul id="mega-menu-3" class="mega-menu">
			<li><?php echo $this->Html->link('Home', '/');?></li>				
			<li>
				<?php echo $this->Html->link('Reports', '#');?>
				<ul>
					<li><?php echo $this->Html->link('General Report', array('controller'=>'reports', 'action'=>'index'));?></li>
					<li><?php echo $this->Html->link('Day to Day Analytics', array('controller'=>'reports', 'action'=>'generateVisualizationDailyReport'));?></li>
					<li><?php echo $this->Html->link('Yearly Analytics', array('controller'=>'reports', 'action'=>'generateVisualizationMonthlyReport'));?></li>
					<li><?php echo $this->Html->link('Monthly Stock Report', array('controller'=>'reports', 'action'=>'showStockReport'));?></li>
				</ul>
			</li>
			
		</ul>
	</div>
<?php 
}
?>

