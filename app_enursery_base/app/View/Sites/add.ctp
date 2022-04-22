<?php echo $this->element('/sites/admin_menu');?>

<div>
	<h3>Add New Site</h3>
	<?php
	echo $form->create(null, array('type'=>'file', 'controller'=>'sites', 'action'=>'add'));
	echo $form->input('Site.name');
	echo $form->input('Site.caption');
	echo $form->input('Site.title', array('label'=>'Title (This will be displayed accross the site)'));
	echo $this->Form->input('Image.file', array('type'=>'file', 'label'=>'Select banner for the site'));
	echo '<div class="input text"><label for="SiteDescription">Description</label>'.$form->textarea('Site.description').'</div>';
	echo '<div class="input text"><label for="SiteActive">Status</label>'.$form->select('Site.active', array('1'=>'Active', '0'=>'InActive'), 1, array('empty'=>false)).'</div>';	
	echo '<div class="input text"><label for="SiteShoppingCart">Shopping Cart</label>'.$form->select('Site.shopping_cart', array('1'=>'Show Shopping Cart', '0'=>'Hide Shopping Cart'), $this->data['Site']['shopping_cart'], array('empty'=>false)).'</div>';
	echo '<div class="input text"><label for="SiteFeaturedProducts">Shopping Cart</label>'.$form->select('Site.featured_products', array('1'=>'Show Featured Products', '0'=>'Hide Featured Products'), $this->data['Site']['featured_products'], array('empty'=>false)).'</div>';

	echo $form->end('Create');
	?>
</div>