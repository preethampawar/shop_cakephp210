<?php if($this->Session->check('User')): ?>

<div class="black">  
	<ul id="mega-menu-3" class="mega-menu">
		<li><?php echo $this->Html->link('Home', '/');?></li>		
		<li><?php echo $this->Html->link('Users', '/admin/users/');?></li>	
		<li><?php echo $this->Html->link('Companies', '/admin/companies/');?></li>	
	</ul>
</div>


<?php endif;?>

<script type="text/javascript">
$('#mega-menu-3').dcMegaMenu({
    rowItems: '3',
    speed: 'fast'
});
</script>
		