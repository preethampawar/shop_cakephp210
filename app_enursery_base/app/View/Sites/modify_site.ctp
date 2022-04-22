<?php echo $this->element('administrator/message');?>
<div>
	<h3>Manage Site</h3>
	<?php
	echo $form->create(null, array('controller'=>'sites', 'action'=>'modifySite'));
	?>
	<table cellpadding='0' cellspacing='0'>
		<tr>
			<?php
			/*
			<td>
				<?php
				// echo '<div class="input text"><label for="SiteActive">Status</label>'.$form->select('Site.active', array('1'=>'Active', '0'=>'InActive'), $this->data['Site']['active'], array('empty'=>false)).'</div>';
				?>
			</td>
			<td>
				<?php
				// echo '<div class="input text"><label for="SiteShoppingCart">Shopping Cart</label>'.$form->select('Site.shopping_cart', array('1'=>'Show Shopping Cart', '0'=>'Hide Shopping Cart'), $this->data['Site']['shopping_cart'], array('empty'=>false)).'</div>';	
				?>
			</td>
			*/
			?>
			<td style="padding:0px;">
				<?php
				echo '<div class="input text"><label for="SiteFeaturedProducts">Featured Products</label>'.$form->select('Site.featured_products', array('1'=>'Show', '0'=>'Hide'), $this->data['Site']['featured_products'], array('empty'=>false)).'</div>';
				?>
			</td>
		</tr>
	</table>
	<?php	
	
	$this->data['Site']['title'] = html_entity_decode($this->data['Site']['title']);
	$this->data['Site']['caption'] = html_entity_decode($this->data['Site']['caption']);
	
	echo $form->input('domain_name', array('label'=>'Site Url', 'disabled'=>true));	
	echo $form->input('Site.title', array('label'=>'Title (This will be displayed accross the site)', 'value'=>$this->data['Site']['title']));
	echo $form->input('Site.caption', array('value'=>$this->data['Site']['caption']));
	echo $form->input('Site.meta_description', array('label'=>'Short Description (No special characters)', 'type'=>'text'));
	echo '<div class="input text"><label for="SiteDescription">Description</label>'.$form->textarea('Site.description', array('rows'=>'30')).'</div>';
	echo $form->input('Site.meta_keywords', array('type'=>'text', 'label'=>'Keywords'));
	?>
	<?php
	echo $form->end('Save Changes');
	?>
</div>
<!--
<div>
	<h3>Site Banner</h3>
	<?php echo $this->data['Site']['banner_image_id'];?>
</div>
-->