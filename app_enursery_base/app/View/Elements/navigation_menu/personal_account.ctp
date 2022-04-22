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
					
			<?php 
			}
			?>			
			<li>
				<?php echo $this->Html->link('Transactions', '#');?>
				<ul>
					<li><?php echo $this->Html->link('New Transaction', array('controller'=>'transactions', 'action'=>'add'));?></li>
					<li><?php echo $this->Html->link('List All Transactions', '/transactions/');?></li>
				</ul>
			</li>		
			<li>
				<?php echo $this->Html->link('Reports', '#');?>
				<ul>
					<li><?php echo $this->Html->link('Income/Expense Report', array('controller'=>'reports', 'action'=>'income_expense_report'));?></li>
					<li><?php echo $this->Html->link('Weekly Visual Report', array('controller'=>'reports', 'action'=>'income_expense_daterange_visual_report'));?></li>
					<li><?php echo $this->Html->link('Yearly Visual Report', array('controller'=>'reports', 'action'=>'income_expense_yearly_visual_report'));?></li>
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
					<li><?php echo $this->Html->link('Income/Expense Report', array('controller'=>'reports', 'action'=>'income_expense_report'));?></li>		
				</ul>
			</li>
			
		</ul>
	</div>
<?php 
}
?>

