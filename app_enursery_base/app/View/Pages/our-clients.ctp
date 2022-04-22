<?php $this->set('clientsLinkActive', true); ?>
<?php $this->set('title_for_layout', 'Our Clients'); ?>

<?php
$sites = $this->requestAction('/sites/getActiveSitesList/1');
?>
<section itemscope itemtype ="http://schema.org/Organization">
	<header>
		<h2><span itemprop="name">LetsGreenify</span> Clients</h2>
	</header>
	<meta itemprop="description" content="LetsGreenify is a web platform which provides online presence(websites) for plant nurseries, gardeners, pot makers & landscapers." />
	<meta itemprop="url" content="http://www.LetsGreenify.com" />
	<meta itemprop="email" content="support@LetsGreenify.com" />
	<div itemprop="reviews" itemscope itemtype="http://schema.org/AggregateRating">
		<meta itemprop="ratingValue" content="4" />
		<meta itemprop="bestRating" content="5" />
		<meta itemprop="ratingCount" content="25" />
	</div>
	
	<?php
	$sitesList = array();
	if(!empty($sites)) {
	?>
		<strong>Businesses using LetsGreenify platform. <?php echo count($sites);?>+</strong>
		<div style="border-bottom:1px dotted #aaa; margin:10px 0px 10px 0px;"></div>

		<?php
		$i=0;
		$clientListCount = 20;
		foreach($sites as $row) {					
			$i++;
			$siteID = $row['Site']['id'];
			$siteTitle = $row['Site']['title'];
			if($i<$clientListCount) {
				$sitesList[] = $siteTitle;
			}
			$domainName = (isset($row['Domain'][0]['name'])) ? $row['Domain'][0]['name'] : '';
			$websiteUrl = 'http://'.$domainName;
			
			$email = $row['Site']['contact_email'];
			$phone = $row['Site']['contact_phone'];
			$contactAddress = $row['Site']['address'];
			$caption = $row['Site']['caption'];
			$siteDescription = $row['Site']['description'];
			
			$showProducts = $row['Site']['show_products'];
			$serviceType = $row['Site']['service_type'];
			
			$userName = $row['User']['name'];
			$address = array();
			$streetAddress = '';					
			$postCode = '';					
			if(!empty($row['User']['address'])) { 
				$streetAddress = $row['User']['address'].'<br>'; 
			}
			
			if(!empty($row['User']['city'])) { $address[] = $row['User']['city']; }
			if(!empty($row['User']['state'])) { $address[] = ' '.$row['User']['state']; }
			$country = $row['User']['country'];
			$address = implode(',', $address);
			
			if(!empty($row['User']['postcode'])) { 
				$postCode = 'PIN: '.$row['User']['postcode']; 
			}
			$products = array();
			$moreLink = null;
			if(!empty($row['ProductInfo'])) {
				foreach($row['ProductInfo'] as $productInfo) {
					if(!empty($productInfo['Product']['id']) and !empty($productInfo['Category']['name'])) {
						$productID = $productInfo['Product']['id']; 
						$productName = ucwords($productInfo['Product']['name']);
						$productNameSlug = Inflector::slug($productName, '-');
						
						$categoryID = $productInfo['Category']['id'];
						$categoryName = $productInfo['Category']['name'];
						$categoryNameSlug = Inflector::slug($categoryName, '-');
						if($domainName) {
							$link = $this->Html->link($productName, 'http://'.$domainName.'/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug, array('title'=>$productName, 'escape'=>false, 'target'=>'_blank'));
						}
						else {
							$link = $productName;
						}
						$products[] = ' '.$link;
					}
				}
				
				$link = $this->Html->link('more...', $websiteUrl.'/products/showAll', array('title'=>'Products Catalog', 'escape'=>false, 'target'=>'_blank'));
				$moreLink = '<p style="float:left; margin-right:15px;">'.$link.'</p>';
			}
			
			if($showProducts) {
				$products = implode(',', $products);					
			}
			else {
				$products = null;
			}
		?>
		<div itemprop="member" itemscope  itemtype ="http://schema.org/Organization">						
			<div style="background-color:#eeeeee; padding:2px 5px; cursor:pointer;"  onclick="$('<?php echo '#ExtraInfo'.$siteID;?>').toggle('fast')" title="Click here for more information">
				<span class="heading" itemprop="name" style="font-weight:bold;"><?php echo $siteTitle;?></span><br>
				<?php echo ($caption) ? '<span style="font-style:italic; font-size:85%; margin-bottom:5px;">- '.$caption.'</span><br>' : null;?>
				<?php echo ($domainName) ? $this->Html->link($websiteUrl, $websiteUrl, array('title'=>$siteTitle, 'style'=>'background-color:#eeeeee;', 'itemprop'=>'url', 'target'=>'_blank')) : ' - ';?>
				<?php
				if($serviceType) {
				?>
					<span class="floatRight" style="margin-right:10px; font-weight:bold;" itempprop="description"><?php echo $serviceType;?></span>						
					<div style="clear:both;"></div>
				<?php
				}
				?>
			</div>	
			<div style="padding-left:5px; display:none; border:2px solid #eeeeee; margin-bottom:25px;" id="<?php echo 'ExtraInfo'.$siteID;?>">
				<?php if($siteDescription) { ?>
				
					<?php echo $siteDescription;?>
				
				<?php } ?>
				<div>
					<div style="margin:10px 0;">
					<strong>Phone</strong>: <span itemprop="telephone"><?php echo $phone;?></span><br>
					<strong>Email</strong>: <span itemprop="email"><?php echo $this->Html->link($email, 'mailto:'.$email);?></span>
					</div>
					<?php
					if(!empty($contactAddress)) {
					?>
					<div itemprop="location" itemscope itemtype="http://schema.org/PostalAddress">	
						<strong>Address</strong>:<br>
						<span itemprop="streetAddress"><?php echo $contactAddress;?></span>
					</div>	
					<?php
					}
					?>
				</div>
				
				<div style="margin-top:10px;">
					<?php echo ($products) ? '<strong>Products:</strong><div>'.$products.'</div>' : '';?>
					<?php echo ($showProducts) ? $moreLink: '';?>
				</div>
				<div style="clear:both;"></div>							
			</div>
			<div style="border-bottom:0px dotted #aaa; margin:10px 0px 15px 0px;"></div>
		</div>			
		<?php					
		}
		?>		
	<?php
	}
	?>
	<br/>
	<p>
	<?php echo $this->Html->link('Register your plant nursery or garden with us','/users/register', array('style'=>'text-decoration:underline;'));?>. 
	</p>	
</section>
<?php $sitesList = implode(',', $sitesList); ?>	
<?php $this->Html->meta('keywords', 'LetsGreenify clients, e nursery clients list', array('inline'=>false)); ?>
<?php $this->Html->meta('description', $sitesList, array('inline'=>false)); ?>


	<?php
	/*
	?>	
	<?php echo $this->set('homeLinkActive', true);?>
	<?php echo $this->element('slider');?>   
	<!-- main content -->
    <div id="homepage">
      <!-- Introduction -->
      <section id="intro" class="clear">
        <article class="one_fifth">
          <figure><a href="#"><?php echo $this->Html->image('images/demo/130x130.gif', array('width'=>'130', 'height'=>'130'));?></a>
            <figcaption>Nullamlacus dui ipsum conseque</figcaption>
          </figure>
        </article>
        <article class="one_fifth">
          <figure><a href="#"><?php echo $this->Html->image('images/demo/130x130.gif', array('width'=>'130', 'height'=>'130'));?></a>
            <figcaption>Nullamlacus dui ipsum conseque</figcaption>
          </figure>
        </article>
        <article class="one_fifth">
          <figure><a href="#"><?php echo $this->Html->image('images/demo/130x130.gif', array('width'=>'130', 'height'=>'130'));?></a>
            <figcaption>Nullamlacus dui ipsum conseque</figcaption>
          </figure>
        </article>
        <article class="one_fifth">
          <figure><a href="#"><?php echo $this->Html->image('images/demo/130x130.gif', array('width'=>'130', 'height'=>'130'));?></a>
            <figcaption>Nullamlacus dui ipsum conseque</figcaption>
          </figure>
        </article>
        <article class="one_fifth lastbox">
          <figure><a href="#"><?php echo $this->Html->image('images/demo/130x130.gif', array('width'=>'130', 'height'=>'130'));?></a>
            <figcaption>Nullamlacus dui ipsum conseque</figcaption>
          </figure>
        </article>
      </section>
      <!-- / Introduction -->
      <!-- ########################################################################################## -->
      <!-- Services -->
      <section id="services" class="last clear">
        <article class="one_third">
          <figure><?php echo $this->Html->image('images/demo/290x120.gif', array('width'=>'290', 'height'=>'120'));?>
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Proin quam. Etiam ultrices. Suspendisse in justo eu magna luctus suscipit. This is a W3C compliant free website template from <a href="http://www.os-templates.com/" title="Free Website Templates">OS Templates</a>. For full terms of use of this template please read our <a href="http://www.os-templates.com/template-terms">website template licence</a>.</p>
              <footer class="more"><a href="#">Read More &raquo;</a></footer>
            </figcaption>
          </figure>
        </article>
        <article class="one_third">
          <figure><?php echo $this->Html->image('images/demo/290x120.gif', array('width'=>'290', 'height'=>'120'));?>
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>You can use and modify the template for both personal and commercial use. You must keep all copyright information and credit links in the template and associated files. For more HTML5 templates visit <a href="http://www.os-templates.com/">free website templates</a>.</p>
              <footer class="more"><a href="#">Read More &raquo;</a></footer>
            </figcaption>
          </figure>
        </article>
        <article class="one_third lastbox">
          <figure><?php echo $this->Html->image('images/demo/290x120.gif', array('width'=>'290', 'height'=>'120'));?>
            <figcaption>
              <h2>Indonectetus facilis</h2>
              <p>Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien.</p>
              <footer class="more"><a href="#">Read More &raquo;</a></footer>
            </figcaption>
          </figure>
        </article>
      </section>
      <!-- / Services -->
    </div>
	<?php	
	*/	
	?>

