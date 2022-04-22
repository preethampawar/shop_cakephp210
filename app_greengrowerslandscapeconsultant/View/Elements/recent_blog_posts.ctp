<?php
App::uses('Blog', 'Model');
$this->Blog = new Blog;

$postCount = 6;
$conditions = array('Blog.site_id'=>$this->Session->read('Site.id'), 'Blog.active'=>'1');
$contents = $this->Blog->find('all', array('conditions'=>$conditions, 'order'=>'Blog.created DESC', 'limit'=>$postCount, 'recursive'=>'-1'));
if(!empty($contents)) {
?>
<h2>From the Blog</h2>

<div>		
	<?php
	$i = 0;
	foreach($contents as $row) {
		$i++;
		if($i<($postCount)) {
			$blogID = $row['Blog']['id'];		
			$blogTitle = $row['Blog']['title'];
			$blogDesc = $row['Blog']['description'];
			$blogViews = $row['Blog']['views'];
			$blogTitleSlug = Inflector::slug(strtolower($blogTitle), '-');
			
			// $blogDesc = Sanitize::html($row['Blog']['description'], array('remove'=>true));
			// $blogDesc = $this->Text->truncate($blogDesc, 200);
			// $blogDesc = $this->Text->autoLinkUrls($blogDesc);
			// $blogDesc = html_entity_decode($blogDesc);
			// $blogTime = $this->Time->timeAgoInWords($row['Blog']['created'], array('format'=>'F jS, Y', 'end'=>'+1 days'));
			
			//debug($blogDesc);
			//$blogDesc = Sanitize::stripTags(($row['Blog']['description']));
		
			$blogDesc = Sanitize::clean($blogDesc, array('encode'=>true, 'remove_html'=>true));	
			$blogDesc = html_entity_decode($blogDesc);
			$blogDesc = $this->Text->truncate($blogDesc, 220);	
			
			$blogTime = date('l, dS M Y', strtotime($row['Blog']['created']));
			
		?>
			<div onclick="window.location = '<?php echo $this->Html->url('/blog/show/'.$blogID.'/'.$blogTitleSlug);?>';" onmouseover="$(this).css('background-color', '#f6f6f6')" onmouseout="$(this).css('background-color', 'transparent')" title="<?php echo  'Read full article: '.$blogTitle;?>" style="cursor:pointer; border-bottom:1px dotted #666666; margin:5px 0;">
				<h3 style="font-size:15px; margin-bottom:5px; background-color:#f6f6f6;"><?php echo $this->Html->link($blogTitle, '/blog/show/'.$blogID.'/'.$blogTitleSlug, array('title'=>$blogTitle, 'style'=>'background-color:#f6f6f6;', 'id'=>'article'.$blogID));?></h3>
				<span style="font-size:11px; font-style:italic;">- <?php echo $blogTime;?></span>
				<p style="text-align: justify;" ><?php echo $blogDesc;?></p>
			</div>			
		<?php
		}
	}
	if($i>($postCount-1)) {
		?>
		<div class="more">
			<?php echo $this->Html->link('Show all blog posts', array('controller'=>'blog', 'action'=>'index'), array('title'=>'Show all blog posts'));?>
		</div>
		<?php
	}
	?>
</div>
<br><br>
<?php
}
?>
<!-- /nav -->