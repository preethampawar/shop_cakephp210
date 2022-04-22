<?php
echo $this->element('text_editor');
echo $this->element('message');
?>
<script type="text/javascript">
function checkProducts() {
	var showProducts = false;
	var showFeaturedProducts = false;
	var showRequestPriceQuote = false;
	var showImageGallery = false;
	
	if($('#SiteShowProducts').prop('checked')) {
		// check featured products, request price quote, image gallery
		$('#SiteFeaturedProducts').prop('checked', true);
		$('#SiteRequestPriceQuote').prop('checked', true);
		$('#SiteImageGallery').prop('checked', true);
	}
	else {
		// uncheck featured products, request price quote, image gallery
		$('#SiteFeaturedProducts').prop('checked', false);
		$('#SiteRequestPriceQuote').prop('checked', false);
		$('#SiteImageGallery').prop('checked', false);
	}
}

function checkOrUncheckShowProductsCheckbox(element) {
	
	if($('#'+element.id).prop('checked')) {
		$('#SiteShowProducts').prop('checked', true);	
	}
}

</script>
<div>
	<h2>Edit Site Information</h2>
	<?php echo $this->Html->link('&laquo; Back &nbsp;', array('controller'=>'sites', 'action'=>'index'), array('escape'=>false, 'class'=>'button')); ?>
	<br><br>
	
	<hr>
	<p>Features Description</p>
	1. <b>Show Products</b>: Enable this feature to add products on this website.<br>
	2. <b>Show Featured Products</b>: Enable this feature to show 'Featured Products' on the Home page. <br>
	3. <b>Show Request Price Quote</b>: Enable this feature to show 'Request Price Quote' form for all the products.<br>
	4. <b>Show Products Photo Gallery</b>: Enable this feature to show photo gallery of all the products.<br>
	5. <b>Show Landing Page</b>: Enable this feature to show Images and some extra content on the Home page.<br>
	6. <b>Show Blog </b>: Enable this feature to show your Blog on this website<br>
	7. <b>Under Maintenance </b>: Enable this feature to temporarily suspend your website<br>
	
	<hr><br>
	<?php
	echo $this->Form->create();
	?>
	<table>
		<tr>
			<td style="vertical-align:top;">				
				<?php echo $this->Form->input('Site.show_products', array('div'=>false, 'label'=>false, 'onchange'=>'checkProducts()'));?> <label for="SiteShowProducts">Show Products </label> <br>
				
				<?php echo $this->Form->input('Site.featured_products', array('div'=>false, 'label'=>false, 'onclick'=>'checkOrUncheckShowProductsCheckbox(this)'));?> <label for="SiteFeaturedProducts">Show Featured Products</label> <br>
				
				<?php echo $this->Form->input('Site.request_price_quote', array('div'=>false, 'label'=>false, 'onclick'=>'checkOrUncheckShowProductsCheckbox(this)'));?> <label for="SiteRequestPriceQuote">Show Request Price Quote</label> <br>
				
				<?php echo $this->Form->input('Site.image_gallery', array('div'=>false, 'label'=>false, 'onclick'=>'checkOrUncheckShowProductsCheckbox(this)'));?> <label for="SiteImageGallery">Show Products Photo Gallery</label>
				
			</td>
			<td style="vertical-align:top; padding-left:50px;">
				<?php echo $this->Form->input('Site.show_landing_page', array('div'=>false, 'label'=>false));?> <label for="SiteShowLandingPage">Show Landing Page</label> <br>
				
				<?php echo $this->Form->input('Site.show_blog', array('div'=>false, 'label'=>false));?> <label for="SiteShowBlog">Show Blog <br>
				
				<?php echo $this->Form->input('Site.under_maintenance', array('div'=>false, 'label'=>false));?> <label for="SiteUnderMaintenance">Under Maintenance</label> <br>
				
				<?php // echo $this->Form->input('Site.sms_notifications', array('div'=>false, 'label'=>false));?>
				
			</td>
		</tr>
	</table>
	

	
	<br/>
	<table style="width:94%; border:0px;" cellpadding='0' cellspacing='0'>
		<tr>			
			<td style='width:180px;'><label for='SiteTitle'>Site Title*</label></td>			
			<td style="width:10px;">:</td>
			<td><?php echo $this->Form->input('Site.title', array('label'=>false, 'value'=>html_entity_decode($this->data['Site']['title'])));?>
				<div class="note">*between 60-70 characters in length</div>
			</td>
		</tr>
		<tr>			
			<td><label for='SiteCaption'>Site Caption</label></td>			
			<td style="width:10px;">:</td>
			<td><?php echo $this->Form->input('Site.caption', array('label'=>false, 'value'=>html_entity_decode($this->data['Site']['caption'])));?></td>
		</tr>
		<tr>			
			<td><label for='SiteServiceType'>Service Type</label></td>			
			<td style="width:10px;">:</td>
			<td>
				<?php echo $this->Form->input('Site.service_type', array('label'=>false, 'value'=>html_entity_decode($this->data['Site']['service_type']), 'list'=>'servicetypes', 'type'=>'text'));
				?>
				<datalist id='servicetypes'>
					<?php if(!empty($serviceTypes)) {
						foreach($serviceTypes as $row) {
						?>
						<option value="<?php echo $row['Site']['service_type'];?>"></option>
						<?php
						}
					}
					?>
				</datalist>
			
			</td>
		</tr>		
		<tr>
			<td>Contact Phone No.*</td>			
			<td style="width:10px;">:</td>
			<td><?php echo $this->Form->input('Site.contact_phone', array('label'=>false, 'type'=>'number', 'div'=>false, 'minlength'=>'10', 'maxlength'=>'55', 'required'=>true, 'placeholder'=>'Enter Contact Phone No.', 'title'=>'Enter Contact Phone No.'));?></td>			
		</tr>
		<tr>
			<td>Contact Email Address*</td>			
			<td style="width:10px;">:</td>
			<td><?php echo $this->Form->input('Site.contact_email', array('label'=>false, 'type'=>'email', 'div'=>false, 'required'=>true, 'placeholder'=>'Enter Contact Email.. ', 'title'=>'Enter Contact Email..'));?></td>			
		</tr>	
		<tr>			
			<td><label for='SiteAddress'>Contact Address*</label></td>			
			<td style="width:10px;">:</td>
			<td>
				<?php echo $this->Form->input('Site.address', array('label'=>false, 'type'=>'textarea', 'rows'=>2, 'required'=>true));?>				
			</td>
		</tr>
	</table>
	<br>
	<table style="width:95%;">	
		<tr>			
			<td>
				<label for="SiteDescription">Site Description:</label>
				<?php echo $this->Form->textarea('Site.description', array('rows'=>'20', 'class'=>'tinymce'));?>
			</td>			
			<td style="width:10px;">:</td>
		</tr>
	</table>
	<br>
	<table style="width:94%; border:0px;" cellpadding='0' cellspacing='0'>
		<tr>			
			<td style='width:180px;'><label for='SiteMetaKeywords'>Meta Keywords</label></td>			
			<td style="width:10px;">:</td>
			<td><?php echo $this->Form->input('Site.meta_keywords', array('label'=>false, 'type'=>'text', 'value'=>html_entity_decode($this->data['Site']['meta_keywords'])));?>
				<div class="note">*max 10 unique words or phrases, without any special characters. (Required for search engines. ex: google, bing, yahoo)</div>
			</td>
		</tr>
		<tr>			
			<td><label for='SiteMetaDescription'>Meta Description</label></td>			
			<td style="width:10px;">:</td>
			<td><?php echo $this->Form->input('Site.meta_description', array('label'=>false, 'type'=>'text', 'value'=>html_entity_decode($this->data['Site']['meta_description'])));?>
				<div class="note">*max 150-160 characters, without any special characters. (Required for search engines. ex: google, bing, yahoo)</div>
			</td>
		</tr>
		<tr>			
			<td><label for='SiteAnalyticsCode'>Analytics Code</label></td>			
			<td style="width:10px;">:</td>
			<td>
				<?php echo $this->Form->input('Site.analytics_code', array('label'=>false, 'type'=>'textarea', 'rows'=>2));?>
				<div class="note">*copy paste, google/bing analytics script</div>
			</td>			
		</tr>
		<tr>			
			<td><label for='SiteSearchEngineCode'>Search Engine Code</label></td>			
			<td style="width:10px;">:</td>
			<td>
				<?php echo $this->Form->input('Site.search_engine_code', array('label'=>false, 'type'=>'textarea', 'rows'=>2));?>
				<div class="note">*copy paste, google/bing custom search engine script</div>
			</td>
		</tr>
		<tr>		
			<td><label for='SiteEmbedMap'>Embed Map</label></td>			
			<td style="width:10px;">:</td>
			<td>				
				<?php echo $this->Form->input('Site.embed_map', array('label'=>false, 'type'=>'text'));?>
				<div class="note">*copy paste, google/bing map script</div>
			</td>
		</tr>
		<tr>			
			<td colspan='2'>
			</td>
			<td>
				<br><br><?php echo $this->Form->end('Save Changes');?>
				<br><br> 
				<?php echo $this->Html->link('&nbsp; Cancel &nbsp;', array('controller'=>'sites', 'action'=>'index'), array('escape'=>false, 'class'=>'button')); ?>
			</td>
		</tr>
	</table>
</div>
<br/>
