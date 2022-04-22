<?php
$this->set('title_for_layout', 'Highlights - Photo Gallery');
$this->Html->meta('keywords', $this->Session->read('Site.title').' gallery, photo gallery, highlights', array('inline'=>false));
$this->Html->meta('description', "Showing higlights from ".$this->Session->read('Site.title'), array('inline'=>false));
?>
<script>
$('#photogallery').attr('class', 'active');
</script>
<?php 
echo $this->Html->css('jquery.lightbox-0.5'); // jQuery Light box
echo $this->Html->script('jquery.lightbox-0.5'); // jQuery Light box	
?>
<section id="ProductInfo">
	<article>
		<header>
			<h2><?php echo 'Highlights - '.$this->Session->read('Site.title');?></h2>
		</header>
		
		<div id="productImages">
			<?php
			$imageCount = 0;
			$captionDivs = '';
			$requestWindows = '';
			if(!empty($contentInfo['Images'])) {
														
				foreach($contentInfo['Images'] as $image) {						
					$imageCount++;
					$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
					$imageCaption = (!empty($image['Image']['caption'])) ? $image['Image']['caption'] : '';
					$imageCaptionSlug = Inflector::slug($imageCaption, '-');
					
					$imageUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'600','width'=>'600','type'=>'auto', 'quality'=>'75', 'filename'=>$imageCaptionSlug), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false), true);
					// $imageThumbUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop'), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false), true);	
					?>
					<div style="float:left; border:0px solid #fff; width:auto; padding:2px;">
						<a href="<?php echo $imageUrl;?>" title='<?php echo $imageCaption;?>'>
							<?php 
							echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop', 'quality'=>'50', 'filename'=>$imageCaptionSlug), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption));
							?>			
						</a>
					</div>					
					<?php																			
				}				
			}
			?>
			<div class='clear'></div>
		</div>
        
		<?php		
		if($imageCount) {			
		?>
			<script type="text/javascript">
			$(function() {
				$('#productImages a').lightBox();
			});
			</script>
		<?php
		}
		else {
		?>
			<p>No Images Found</p>		
		<?php
		}
		?>
		<br><br>
	</article>
</section>
<div>
	<div style="float:left">
		<g:plusone annotation="bubble" size="standard"></g:plusone>					
	</div>
	<div style="float:left">
		<div class="fb-like" data-send="true" data-width="350" data-show-faces="true"></div>
	</div>
	<div style="clear:both;"></div>
</div>