<?php
	$homeLinkActive = (isset($homeLinkActive)) ? 'class="active"' : null;
	$productLinkActive = (isset($productLinkActive)) ? 'class="active"' : null;
	$loginLinkActive = (isset($loginLinkActive)) ? 'class="active"' : null;
	$contactUsLinkActive = (isset($contactUsLinkActive)) ? 'class="active"' : null;
	$siteInfoLinkActive = (isset($siteInfoLinkActive)) ? 'class="active"' : null;
	$accountInfoLinkActive = (isset($accountInfoLinkActive)) ? 'class="active"' : null;
	$productInfoLinkActive = (isset($productInfoLinkActive)) ? 'class="active"' : null;
	$categoryInfoLinkActive = (isset($categoryInfoLinkActive)) ? 'class="active"' : null;
	$pagesLinkActive = (isset($pagesLinkActive)) ? 'class="active"' : null;
	$blogLinkActive = (isset($blogLinkActive)) ? 'class="active"' : null;
	$priceQuoteInfoLinkActive = (isset($priceQuoteInfoLinkActive)) ? 'class="active"' : null;
	
	App::uses('Content', 'Model');
	$contentModel = new Content;
	$pages = $contentModel->getTopNavContent();
	
	$map = ($this->Session->read('Site.embed_map')) ? $this->Session->read('Site.embed_map') : '';	
?>

<?php		
if(!$this->Session->read('Site.under_maintenance')) {	
?>
<div id="topNavMenuDiv">				
	<nav class="clear" id="topnav">		
		<ul class="topnavUL">
			<li <?php echo $homeLinkActive;?>><?php echo $this->Html->link('Home', '/', array('title'=>'Home Page'));?></li>

			<?php 
			if($this->Session->read('Site.show_products')) {
			?>
				<li <?php echo $productLinkActive;?>>
					<?php	echo $this->Html->link('Products', '/products', array('title'=>'Products Page'));	?>
				</li>
			<?php
			}
			?>

			<?php 
			// if(isset($showShoppingListInTopMenu) and !empty($showShoppingListInTopMenu)) {
				// echo ($this->Session->read('Site.request_price_quote')) ? $this->element('myshoppinglist_topnav') : null;
			// }
			?>
			<?php echo $this->element('image_gallery_topnav');?>
			<?php echo ($this->Session->read('Site.show_blog')) ? '<li>'.$this->Html->link('Blog', array('controller'=>'blog', 'action'=>'index')).'</li>' : null;?>
			<?php echo ($map) ? '<li>'.$this->Html->link('Route map', array('controller'=>'sites', 'action'=>'routemap'), array('escape'=>false)).'</li>' : null;?>
			<?php echo $this->element('top_nav_content_links');?>
			
			<?php /* if(!$this->Session->check('User.id')) { ?>
			<li <?php echo $loginLinkActive;?> style="float:right"><?php echo $this->Html->link('Admin Login', array('controller'=>'users', 'action'=>'login'), array('style'=>'text-decoration:none;', 'escape'=>false, 'title'=>'Admin Login'));?></li>				
			<?php } */?>
		</ul>
		
		
		<?php
		App::uses('Category', 'Model');
		$categoryModel = new Category;
		$categories = $categoryModel->getCategories();
		if(!empty($categories)) {
		?>
		<div style="clear:both;"></div>
		<div class="mobileHgroup" style="padding:0; margin-top:25px;">					
			<a class="menu btn" style="font-size:16px; background-color:#333; text-transform:uppercase;">
				<?php echo $this->Html->image('arrow.png', array('alt'=>' ', 'title'=>'Click here for more options', 'height'=>'32px'));?> &nbsp; Browse by category
			</a>
		</div>
		<div id="mobileCategoryMenu">
			<ul class="topnavUL">
			
				<?php
				foreach($categories as $row) {
					$categoryID = $row['Category']['id'];
					$categoryName = Inflector::humanize($row['Category']['name']);
					$categoryNameSlug = Inflector::slug($row['Category']['name'], '-');			
				?>
					<li>
					<?php echo $this->Html->link($categoryName, '/products/show/'.$categoryID.'/'.$categoryNameSlug, array('title'=>$categoryName, 'escape'=>false));?>
					</li>
				<?php
				}
				?>
				<li><?php echo $this->Html->link('Show All', '/products/showAll', array('title'=>'Show all products', 'style'=>'font-weight:bold;'));?></li>
			</ul>	
		</div>	
		<?php
		}
		?>
	</nav>
</div>
<?php
}
?>
		
<?php		
if($this->Session->check('User.id')) {				
?>	
	<nav class="clear" id="subnav" style="">		
		<ul>	
			<li>ADMIN:</li>
			<?php
			if($this->Session->read('Site.show_products')) {
			?>
			<li <?php echo $categoryInfoLinkActive;?>><?php echo $this->Html->link('Category Info', '/admin/categories/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
			<li <?php echo $productInfoLinkActive;?>><?php echo $this->Html->link('Product Info', '/admin/products/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
			<li <?php echo $priceQuoteInfoLinkActive;?>><?php echo $this->Html->link('Price Quotes', '/admin/RequestPriceQuote/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
			<?php
			}
			?>		
			
			<?php if($this->Session->read('Site.show_blog')) { ?>	
			<li <?php echo $blogLinkActive;?>>
				<?php echo $this->Html->link('Blog', '/admin/blog/', array('style'=>'text-decoration:none;', 'escape'=>false));?>
			</li>
			<?php } ?>
			
			<li <?php echo $pagesLinkActive;?>><?php echo $this->Html->link('Pages', '/admin/contents/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
			
			<?php if(empty($pages)) { ?><li style="float:right"><?php echo $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout'), array('style'=>'text-decoration:none;', 'escape'=>false));?></li><?php }?>
			<li style="float:right">
				<?php echo $this->Html->link('Settings', '#', array('title'=>'settings'));?>
				<ul>
					<li><?php echo $this->Html->link('My Account', '/admin/users/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
					<li><?php echo $this->Html->link('Manage Site', '/admin/sites/', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
					<li><?php echo $this->Html->link('Change Password', '/admin/users/changePassword', array('style'=>'text-decoration:none;', 'escape'=>false));?></li>
				</ul>
			</li>
		</ul>
	</nav>
<?php				
}
?>
		