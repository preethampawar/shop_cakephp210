<?php 
if($this->Session->check('User')) {
	if($this->Session->check('UserCompany')) {	
		switch($this->Session->read('Company.business_type')) {
			case 'personal':
				echo $this->element('navigation_menu/personal_account');
				break;
				
			case 'general':
				echo $this->element('navigation_menu/general_account');
				break;
				
			case 'inventory':
				echo $this->element('navigation_menu/inventory_account');
				break;
				
			case 'wineshop':
				echo $this->element('navigation_menu/wineshop_account');
				break;	
				
			case 'finance':
				echo $this->element('navigation_menu/finance_account');
				break;
			
			case 'default':
				echo $this->element('navigation_menu/personal_account');
				break;
		}	
	}
	else {
	?>
		<div class="black">  
			<ul id="mega-menu-3" class="mega-menu">
				<li><?php echo $this->Html->link('Home', '/companies/selectCompany');?></li>
			</ul>
		</div>
	<?php
	}
}
?>

<script type="text/javascript">
$('#mega-menu-3').dcMegaMenu({
    rowItems: '3',
    speed: 'fast',
	effect: 'slide',
	fullWidth: false
});
</script>
<style type="text/css">
#mega-menu-3 { background-color:#fff;}
</style>
		