<?php echo $this->element('/sites/admin_menu'); ?>

<div>
	<h3>Add New Site</h3>
	<?php
	echo $form->create(null, ['type' => 'file', 'controller' => 'sites', 'action' => 'add', 'encoding' => false]);
	echo $form->input('Site.name');
	echo $form->input('Site.caption');
	echo $form->input('Site.title', ['label' => 'Title (This will be displayed accross the site)']);
	echo $this->Form->input('Image.file', ['type' => 'file', 'label' => 'Select banner for the site']);
	echo '<div class="input text"><label for="SiteDescription">Description</label>' . $form->textarea('Site.description') . '</div>';
	echo '<div class="input text"><label for="SiteActive">Status</label>' . $form->select('Site.active', ['1' => 'Active', '0' => 'InActive'], 1, ['empty' => false]) . '</div>';
	echo '<div class="input text"><label for="SiteShoppingCart">Shopping Cart</label>' . $form->select('Site.shopping_cart', ['1' => 'Show Shopping Cart', '0' => 'Hide Shopping Cart'], $this->data['Site']['shopping_cart'], ['empty' => false]) . '</div>';
	echo '<div class="input text"><label for="SiteFeaturedProducts">Shopping Cart</label>' . $form->select('Site.featured_products', ['1' => 'Show Featured Products', '0' => 'Hide Featured Products'], $this->data['Site']['featured_products'], ['empty' => false]) . '</div>';

	echo $form->end('Create');
	?>
</div>
