<?php
$this->set('title_for_layout', $contentInfo['Content']['title']);
$this->Html->meta('keywords', $contentInfo['Content']['meta_keywords'], array('inline'=>false));
$this->Html->meta('description', $contentInfo['Content']['meta_description'], array('inline'=>false));
?>
<section>
	<article>
		<header><h2><?php echo $contentInfo['Content']['title'];?></h2></header>
		<?php
		if(!empty($images)) {
		?>

		<div id="contentImages">	
			<?php
			$higlightImage='';
			$k=0;
			foreach($images as $row) { 
				$imageID = $row['Image']['id'];
				$imageCaption = ($row['Image']['caption']) ? $row['Image']['caption'] : $productName;
				$imageCaptionSlug = Inflector::slug($imageCaption, '-');
				
				$imageUrl = $this->Html->url($this->Img->showImage('img/images/'.$imageID, array('height'=>'600','width'=>'600','type'=>'auto', 'quality'=>'85', 'filename'=>$imageCaptionSlug), array('style'=>'', 'alt'=>$contentInfo['Content']['title'], 'title'=>$imageCaption), true), true);
				$imageThumbUrl = $this->Html->url($this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop', 'quality'=>'85', 'filename'=>$imageCaptionSlug), array('style'=>'', 'alt'=>$contentInfo['Content']['title'], 'title'=>$imageCaption), true), true);		
				if($row['Image']['highlight']) {
					$higlightImage = $imageUrl;
				}
				
			?>
			<div style="float:left; border:0px solid #fff; width:auto; padding:2px;">
				<a href="<?php echo $imageUrl;?>" title='<?php echo $imageCaption;?>'>
					<img src="<?php echo $imageThumbUrl;?>" alt="<?php echo $contentInfo['Content']['title'];?>" width='150' height='150'/>
					<?php 
					// echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop'), array('style'=>'', 'alt'=>$productName, 'title'=>$imageCaption));
					?>			
				</a>
			</div>	
			<?php } ?>
			<div class='clear'></div>	
			<br><br>
		</div>
		<?php
		}
		?>
		
		<p><?php echo $contentInfo['Content']['description'];?></p>		
	</article>
</section>
<script>
    document.getElementById("content<?php echo $contentInfo['Content']['id']?>").setAttribute('class', 'active');
</script>
