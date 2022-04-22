<?php
$blogID = $blogInfo['Blog']['id'];					
$blogTitle = $blogInfo['Blog']['title'];
$blogViews = ($blogInfo['Blog']['views']) ? $blogInfo['Blog']['views'] : 1;
$blogTitleSlug = Inflector::slug($blogTitle, '-');
$blogDesc = $blogInfo['Blog']['description'];
// $blogDesc = $this->Text->autoLinkUrls($blogDesc);
// $blogTime = $this->Time->timeAgoInWords($blogInfo['Blog']['created'], array('format'=>'F jS, Y', 'end'=>'+1 days'));
$blogTime = date('l, dS M Y', strtotime($blogInfo['Blog']['created']));
?>

<?php
$metaDescription = Sanitize::stripTags($blogDesc, 'p', 'div', 'span', 'br', 'a', 'table', 'tr', 'td', 'colgroup', 'tbody', 'thead', 'tfooter', 'col', 'strong');
$this->set('title_for_layout', $blogTitle);
$this->Html->meta('keywords', $blogTitle, array('inline'=>false));
$this->Html->meta('description', $this->Text->truncate($metaDescription, 150), array('inline'=>false));
?>

<div>
	
	<div style="float:left; width:500px;">		
		<h2><?php echo $this->Html->link($blogTitle, '/blog/show/'.$blogID.'/'.$blogTitleSlug, array('title'=>$blogTitle));?></h2>
		<span style="font-style:normal; font-size:100%; color:orange;"><?php echo $blogTime;?></span>
		<br><br>
		<div style="text-align:justify;text-justify:inter-word;"><?php echo $blogDesc;?></div>
		<br>
	</div>
	<div style="float:left; width:380px; margin-left:30px;">
		<p><span class="small button">Page views: <?php echo $blogViews;?></span></p>
		<div class="fb-like" data-send="true" data-width="350" data-show-faces="true"></div>
		<div class="clear"></div>
		<br>
		<?php 
		$uri = $this->request->here();
		$domain = $this->request->domain();
		$url = $this->Html->url('/', true);
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

