<?php
$blogID = $blogInfo['Blog']['id'];					
$blogTitle = $blogInfo['Blog']['title'];
$blogViews = ($blogInfo['Blog']['views']) ? $blogInfo['Blog']['views'] : 1;
$blogTitleSlug = Inflector::slug($blogTitle, '-');
$blogDesc = $blogInfo['Blog']['description'];
// $blogDesc = $this->Text->autoLinkUrls($blogDesc);
// $blogTime = $this->Time->timeAgoInWords($blogInfo['Blog']['created'], array('format'=>'F jS, Y', 'end'=>'+1 days'));
$blogTime = date('l, dS M Y', strtotime($blogInfo['Blog']['created']));
$blogPublishedDate = date('Y-m-d', strtotime($blogInfo['Blog']['created']));
$blogModifiedDate = date('Y-m-d', strtotime($blogInfo['Blog']['modified']));
$blogUrl = $this->Html->url($this->request['url'], true);
$blogLogo = $this->Html->url('/img/blogger.png', true);
$blogPublisher = $this->Html->url('/img/publisher_image.png', true);

$userId = $blogInfo['Blog']['user_id'];
App::uses('User', 'Model');
$this->User = new User();
$userInfo = $this->User->findById($userId);
$author = $userInfo['User']['name'];
$siteTitle = $this->Session->read('Site.title');
// echo '<pre>';
// print_r($this->Session->read('Site.title'));
// print_r($this->request->host());
?>

<?php
$metaDescription = Sanitize::stripTags($blogDesc, 'p', 'div', 'span', 'br', 'a', 'table', 'tr', 'td', 'colgroup', 'tbody', 'thead', 'tfooter', 'col', 'strong');
$this->set('title_for_layout', $blogTitle);
$this->Html->meta('keywords', $blogTitle, array('inline'=>false));
$this->Html->meta('description', $this->Text->truncate($metaDescription, 150), array('inline'=>false));
?>

<?php 
echo $this->Html->css('jquery.lightbox-0.5'); // jQuery Light box
echo $this->Html->script('jquery.lightbox-0.5'); // jQuery Light box	
?>

<div>
	
	<div style="float:left; width:500px;">		
		<h2><?php echo $this->Html->link($blogTitle, '/blog/show/'.$blogID.'/'.$blogTitleSlug, array('title'=>$blogTitle));?></h2>
		<span style="font-style:normal; font-size:100%; color:orange;"><?php echo $blogTime;?></span>
		<br><br>
		
		<?php
		$allImages = array();
		if(!empty($images)) {
		?>
			<script type="text/javascript">
				$(function() {
					$('#blogImages a').lightBox();
				});
			</script>
			<div id="blogImages" class="blog-details-slider">	
				<?php
				$higlightImage='';
				$k=0;
				foreach($images as $row) { 
					$imageID = $row['Image']['id'];
					$imageCaption = ($row['Image']['caption']) ? $row['Image']['caption'] : $productName;
					$imageCaptionSlug = Inflector::slug($imageCaption, '-');
					
					$imageUrl = $this->Html->url($this->Img->showImage('img/images/'.$imageID, array('height'=>'600','width'=>'600','type'=>'auto', 'quality'=>'85', 'filename'=>$imageCaptionSlug), array('style'=>'', 'alt'=>$blogTitle, 'title'=>$imageCaption), true), true);
					$imageThumbUrl = $this->Html->url($this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop', 'quality'=>'85', 'filename'=>$imageCaptionSlug), array('style'=>'', 'alt'=>$blogTitle, 'title'=>$imageCaption), true), true);		
					if($row['Image']['highlight']) {
						$higlightImage = $imageUrl;
					}
					$allImages[] = '"'.$imageUrl.'"';
					
				?>
				<div style="float:left; border:0px solid #fff; width:auto; padding:2px;">
					<a href="<?php echo $imageUrl;?>" title='<?php echo $imageCaption;?>'>
						<img src="<?php echo $imageThumbUrl;?>" alt="<?php echo $blogTitle;?>" width='150' height='150'/>
						<?php 
						// echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop'), array('style'=>'', 'alt'=>$productName, 'title'=>$imageCaption));
						?>			
					</a>
				</div>	
				<?php 
				}
				$allImages = implode(',', $allImages);	
				?>
				<div class='clear'></div>	
				<br><br>
			</div>
		<?php
		}
		
		if(empty($allImages)) {
			$allImages = '"'.$blogLogo.'"';
		}
		?>
		
		<div style="text-align:justify;text-justify:inter-word;"><?php echo $blogDesc;?></div>
		<br>
	</div>
	<div style="float:left; width:380px; margin-left:30px;">
		<p><span class="small button">Page views: <?php echo $blogViews;?></span></p>
		<div class="fb-like" data-send="true" data-width="350" data-show-faces="true"></div>
		<div class="clear"></div>
		<br>
		<?php 				
		$url = $this->Html->url($this->request['url'], true);
		$siteName = $this->Session->read('Site.title');
		
$facebookMetaTags = <<<TAGS
<meta property="og:title" content="$blogTitle" />
<meta property="og:type" content="blog" />
<meta property="og:url" content="$url" />
<meta property="og:image" content="" />
<meta property="og:site_name" content="$siteName" />		
TAGS;
		$this->set('facebookMetaTags', $facebookMetaTags);
		?>
		<div class="fb-comments" data-href="<?php echo $url;?>" data-num-posts="20" data-width="350"></div>				
	</div>
	<div class="clear"></div>
</div>

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo $blogUrl;?>"
  },
  "headline": "<?php echo $blogTitle;?>",
  "image": [
		<?php echo $allImages;?>
   ],
  "datePublished": "<?php echo $blogPublishedDate;?>",
  "dateModified": "<?php echo $blogModifiedDate;?>",
  "author": {
    "@type": "Person",
    "name": "<?php echo $author;?>"
  },
   "publisher": {
    "@type": "Organization",
    "name": "<?php echo $siteTitle;?>",
    "logo": {  
		"@type": "ImageObject",
		"url": "<?php echo $blogPublisher;?>"
    }
  },
  "description": "<?php echo $metaDescription;?>"
}
</script>

