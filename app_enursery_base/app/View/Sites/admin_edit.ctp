<?php
//echo $this->element('htmleditor');
?>
<div>
	<h2>Edit site: <?php echo $siteInfo['Site']['name'];?> </h2>
	<?php
	echo $this->Form->create();
	?>
	<table style="width:800px; border:0px;" cellpadding='0' cellspacing='0'>
		<tr>			
			<td>
				<?php	echo $this->Form->input('Site.active', array('div'=>false, 'label'=>false));?>
			</td>
			<td>
				<label for="SiteActive">Active</label>
			</td>
		</tr>	
		<tr>	
			<td>
				<?php	echo $this->Form->input('Site.suspended', array('div'=>false, 'label'=>false));?>
			</td>
			<td>
				<label for="SiteSuspended">Account Suspended</label>
			</td>
		</tr>	
		<tr>	
			<td>
				<?php	echo $this->Form->input('Site.under_maintenance', array('div'=>false, 'label'=>false));?>
			</td>
			<td>
				<label for="SiteUnderMaintenance">Under Maintenance</label>
			</td>			
		</tr>	
		<tr>
			<td>
				<?php	echo $this->Form->input('Site.shopping_cart', array('div'=>false, 'label'=>false));?>
			</td>
			<td>
				<label for="SiteShoppingCart">Enable Shopping Cart</label>
			</td>
		</tr>	
		<tr>	
			<td>
				<?php echo $this->Form->input('Site.featured_products', array('div'=>false, 'label'=>false));?>
			</td>
			<td>
				<label for="SiteFeaturedProducts">Show Featured Products</label>
			</td>
		</tr>	
		<tr>	
			<td>
				<?php echo $this->Form->input('Site.show_blog', array('div'=>false, 'label'=>false));?>
			</td>
			<td>
				<label for="SiteShowBlog">Show Blog</label>
			</td>
		</tr>	
		<tr>	
			<td>
				<?php echo $this->Form->input('Site.show_ads', array('div'=>false, 'label'=>false, 'type'=>'checkbox', 'value'=>'1'));?>
			</td>
			<td>
				<label for="SiteShowAds">Show Ads</label>
			</td>
		</tr>
		<tr>			
			<td>
				<?php	echo $this->Form->input('Site.show_in_clients_list', array('div'=>false, 'label'=>false));?>
			</td>
			<td>
				<label for="SiteShowInClientsList">Show in clients list (www.enursery.in)</label>
			</td>
			
			<td colspan='4'>&nbsp;</td>
		</tr>
		<tr>			
			<td>&nbsp;</td>
			<td><br><?php echo $this->Form->end('Save Changes');?></td>
		</tr>
	</table>
	
	
	<br/><hr/><br/>
	<div style="width:400px;">
	<h2>Add New Domain</h2>
	<?php echo $this->Form->create(null, array('controller'=>'sites', 'action'=>'admin_addDomain/'.$siteInfo['Site']['id']));?>
	<table>
		<tr>
			<td><?php echo $this->Form->input('Domain.name', array('label'=>'Domain Name', 'div'=>false));?></td>
			<td valign='bottom'><?php echo $this->Form->submit('Submit', array('div'=>false));?></td>
		</tr>
	</table>
	<?php
	echo $this->Form->end();
	?>
	</div>
	<br><br>
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
						echo '<strong>(Default)</strong>';
					}
					?>
				</td>
			</tr>
			<?php	
			}
			?>
			</tbody>
		</table>	
		<?php
	}
	else {
		echo 'No domains found for this site.';
	}
	?>
	
</div>
