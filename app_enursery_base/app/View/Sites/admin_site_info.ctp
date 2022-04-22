<div>
	<h2>My Site Information</h2>
	
	<table style="width:500px; border:0px;" cellpadding='0' cellspacing='0'>
		<tr>			
			<td>
				<?php echo $this->Form->input('Site.active', array('div'=>false, 'label'=>false, 'disabled'=>true));?>
			</td>
			<td>
				<label for="SiteActive">Active</label>
			</td>
			<td>
				<?php	echo $this->Form->input('Site.shopping_cart', array('div'=>false, 'label'=>false, 'disabled'=>true));?>
			</td>
			<td>
				<label for="SiteShoppingCart">Shopping Cart</label>
			</td>
			<td>
				<?php echo $this->Form->input('Site.featured_products', array('div'=>false, 'label'=>false, 'disabled'=>true));?>
			</td>
			<td>
				<label for="SiteFeaturedProducts">Featured Products</label>
			</td>
		</tr>
	</table>
	<br/>
	<table style="width:600px;" cellpadding='0' cellspacing='0' class='table'>		
		<tr>			
			<td style='width:150px;'><label for='SiteTitle'>Site Title</label></td>
			<td><?php echo $siteInfo['Site']['title']; ?></td>
		</tr>
		<tr>			
			<td><label for='SiteCaption'>Site Caption</label></td>
			<td><?php echo $siteInfo['Site']['caption']; ?></td>
		</tr>
		<tr>			
			<td><label for='SiteMetaKeywords'>Meta Keywords</label></td>
			<td><?php echo $siteInfo['Site']['meta_keywords']; ?></td>
		</tr>
		<tr>			
			<td><label for='SiteMetaDescription'>Meta Description</label></td>
			<td><?php echo $siteInfo['Site']['meta_description']; ?></td>
		</tr>
		<tr>			
			<td><label for='SiteAnalyticsCode'>Analytics Code</label></td>
			<td><?php echo htmlentities($siteInfo['Site']['analytics_code']); ?></td>
		</tr>
		<tr>			
			<td><label for='SiteSearchEngineCode'>Search Engine Code</label></td>
			<td><?php echo htmlentities($siteInfo['Site']['search_engine_code']); ?></td>
		</tr>		
	</table>
	
	
	<br/><hr/><br/>
	<h3>Site Domains</h3>
	<?php
	if(!empty($siteInfo['Domain'])) {
		?>
		<table style="width:600px;" class='table'>
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Domain Name</th>
				<th>Created</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php 
			$k=0;
			$defaultDomain = null;	
			foreach($siteInfo['Domain'] as $row) {
				$k++;
			?>
			<tr>
				<td style='width:20px;'><?php echo $k;?></td>
				<td><?php echo ($row['default']) ? '<strong>'.$row['name'].'</strong>' : $row['name'];?></td>
				<td style='width:90px;'><?php echo date('d-M-Y', strtotime($row['created']));?></td>
				<td style='width:160px;'>
					<?php 
					if(!$row['default']) {
						echo $this->Html->link('Make Default', array('action'=>'setDefaultDomain/'.$row['id'].'/'.$row['site_id']), array('class'=>'button grey small'));
						
						echo '&nbsp;|&nbsp;';
						
						echo $this->Html->link('Delete', array('action'=>'deleteDomain/'.$row['id'].'/'.$row['site_id']), array('class'=>'button grey small'), 'Are you sure you want to delete this domain name?');
					}
					else {
						$defaultDomain = $row['name'];
						echo '<strong>(Default)</strong>';
					}
					?>
				</td>
			</tr>
			<?php	
			}
			if(!$defaultDomain) {
				$defaultDomain = $siteInfo['Domain'][0]['name'];
			}
			?>
			</tbody>
		</table>	
		<br/>
		<h2><?php echo $this->Html->link('Visit my site &nbsp;&raquo;', 'http://'.$defaultDomain, array('escape'=>false, 'style'=>'text-decoration:underline;', 'target'=>'_blank'));?></h2>
		<br/>
		
		<?php
	}
	else {
		echo 'No domains found for this site.';
	}
	?>
	
</div>
