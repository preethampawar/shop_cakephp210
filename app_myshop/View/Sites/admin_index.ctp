<div>
	<h2>Site Information</h2>
	<?php echo $this->Html->link('Modify Site Information &raquo;', ['controller' => 'sites', 'action' => 'edit'], ['escape' => false, 'class' => 'button']); ?>
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

	<hr>
	<br>
	<table style="font-weight:bold; width:95%;">
		<tr>
			<td style="vertical-align:top;">
				<?php echo $this->Form->input('Site.show_products', ['div' => false, 'label' => false, 'disabled' => true]); ?>
				<label for="SiteShowProducts">Show Products </label> <br>

				<?php echo $this->Form->input('Site.featured_products', ['div' => false, 'label' => false, 'disabled' => true]); ?>
				<label for="SiteFeaturedProducts">Show Featured Products</label> <br>

				<?php echo $this->Form->input('Site.request_price_quote', ['div' => false, 'label' => false, 'disabled' => true]); ?>
				<label for="SiteRequestPriceQuote">Show Request Price Quote</label> <br>

				<?php echo $this->Form->input('Site.image_gallery', ['div' => false, 'label' => false, 'disabled' => true]); ?>
				<label for="SiteImageGallery">Show Products Photo Gallery</label>

			</td>
			<td style="vertical-align:top; padding-left:50px;">
				<?php echo $this->Form->input('Site.show_landing_page', ['div' => false, 'label' => false, 'disabled' => true]); ?>
				<label for="SiteShowLandingPage">Show Landing Page</label> <br>

				<?php echo $this->Form->input('Site.show_blog', ['div' => false, 'label' => false, 'disabled' => true]); ?>
				<label for="SiteShowBlog">Show Blog <br>

					<?php echo $this->Form->input('Site.under_maintenance', ['div' => false, 'label' => false, 'disabled' => true]); ?>
					<label for="SiteUnderMaintenance">Under Maintenance</label> <br>

					<?php // echo $this->Form->input('Site.sms_notifications', array('div'=>false, 'label'=>false, 'disabled'=>true));?>

			</td>
		</tr>
	</table>

	<br/>

	<table style="width:95%;" cellpadding='0' cellspacing='0' class='table'>
		<tr>
			<td style='width:150px; font-weight:bold;'>Site Title</td>
			<td style="width:10px;">:</td>
			<td><?php echo $siteInfo['Site']['title']; ?></td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Site Caption</td>
			<td style="width:10px;">:</td>
			<td><?php echo $siteInfo['Site']['caption']; ?></td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Service Type</td>
			<td style="width:10px;">:</td>
			<td><?php echo $siteInfo['Site']['service_type']; ?></td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Contact Phone</td>
			<td style="width:10px;">:</td>
			<td><?php echo $siteInfo['Site']['contact_phone']; ?></td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Contact Email</td>
			<td style="width:10px;">:</td>
			<td><?php echo $siteInfo['Site']['contact_email']; ?></td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Contact Address</td>
			<td style="width:10px;">:</td>
			<td>
				<pre><?php echo $siteInfo['Site']['address']; ?></pre>
			</td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Meta Keywords</td>
			<td style="width:10px;">:</td>
			<td><?php echo $siteInfo['Site']['meta_keywords']; ?></td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Meta Description</td>
			<td style="width:10px;">:</td>
			<td><?php echo $siteInfo['Site']['meta_description']; ?></td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Analytics Code</td>
			<td style="width:10px;">:</td>
			<td>
				<div
					style="max-width:600px;word-wrap:break-word;"><?php echo htmlentities($siteInfo['Site']['analytics_code']); ?></div>
			</td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Search Engine Code</td>
			<td style="width:10px;">:</td>
			<td>
				<div
					style="max-width:600px;word-wrap:break-word;"><?php echo htmlentities($siteInfo['Site']['search_engine_code']); ?></div>
			</td>
		</tr>
		<tr>
			<td style='font-weight:bold;'>Map Code</td>
			<td style="width:10px;">:</td>
			<td>
				<div
					style="max-width:600px;word-wrap:break-word;"><?php echo htmlentities($siteInfo['Site']['embed_map']); ?></div>
			</td>
		</tr>
	</table>

	<strong>Site description: </strong>
	<table style="width:95%" class='table'>
		<tbody>
		<tr>
			<td>
				<?php echo $siteInfo['Site']['description']; ?>
			</td>
		</tr>
		</tbody>
	</table>
	<br>
	<?php echo $this->Html->link('Modify Site Information &raquo;', ['controller' => 'sites', 'action' => 'edit'], ['escape' => false, 'class' => 'button']); ?>
</div>
<br/>
