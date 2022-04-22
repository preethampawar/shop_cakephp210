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
				<?php echo $this->Html->link('Groups', '#');?>
				<ul>
					<li><?php echo $this->Html->link('Create New Group', array('controller'=>'groups', 'action'=>'add'));?></li>
					<li><?php echo $this->Html->link('Show Groups', '/groups/');?></li>
				</ul>
			</li>			
			<li>
				<?php echo $this->Html->link('Users', '#');?>
				<ul>
					<li><?php echo $this->Html->link('Show Users', '/users/');?></li>
					<li><?php echo $this->Html->link('Invite User', array('controller'=>'users', 'action'=>'inviteUser'));?></li>
				</ul>
			</li>			
			<?php 
			}
			?>			
			<li>
				<?php echo $this->Html->link('Finance', '#');?>
				<ul>
					<li><?php echo $this->Html->link('Add New Record', array('controller'=>'cash', 'action'=>'add'));?></li>
					<li><?php echo $this->Html->link('Cash Book', '/cash/');?></li>
				</ul>
			</li>		
			<li>
				<?php echo $this->Html->link('Reports', '#');?>
				<ul>
					<li><?php echo $this->Html->link('General Report', array('controller'=>'reports', 'action'=>'index'));?></li>					
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
				</ul>
			</li>
			
		</ul>
	</div>
<?php 
}
?>

