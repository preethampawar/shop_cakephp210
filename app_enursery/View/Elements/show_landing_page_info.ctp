<?php
App::uses('Content', 'Model');
$this->Content = new Content;	
$contentInfo = $this->Content->getLandingPageInfoWithImages();

if($contentInfo['Content']['active']) {	
?>	

	<section class="landingPageSection">
		<?php 		
		// Landing page images
		if(!empty($contentInfo['Images'])) {		
		?>
			<div class="landing-page-slider">
				<?php
				$imageCount = 0;
				$captionDivs = '';			
				
				foreach($contentInfo['Images'] as $image) {				
					$imageCount++;
					$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
					$imageCaption = (!empty($image['Image']['caption'])) ? trim($image['Image']['caption']) : '';
					$captionSlug = Inflector::slug($imageCaption, '-');
					
					$imageUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'500','width'=>'960','type'=>'exact', 'quality'=>'85', 'filename'=>$captionSlug), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false), true);
					//$imageThumbUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop', 'quality'=>'50', 'filename'=>$captionSlug), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false), true);
					
					?>
					
					<!-- <img src="<?php echo $imageUrl;?>" alt="<?php echo $imageCaption;?>" title="#htmlcaption<?php echo $imageID;?>" /> -->
					<div style="text-align:center;">
						<img alt="<?php echo $imageCaption;?>" data-lazy="<?php echo $imageUrl;?>" style="width: 100%; height: auto;"/>	
						
						<?php
						if($imageCaption != '') {
						?>
							<div style="background-color: #25488f; color:#ffffff; padding: 5px 5px;"><?php echo $imageCaption;?></div>	
						<?php
						}
						?>
					</div>
					<?php
					// html caption div's
					if(!empty($imageCaption)) {
						$captionDivs.='<div id="htmlcaption'.$imageID.'" class="nivo-html-caption">'.$imageCaption.'</div>';		
					}
					
					// if mobile do not show more than 10 images.
					if($this->Session->read('isMobile') == true) {
						if($imageCount == 10) {	
							break;
						}
					}
				}					
				?>
			</div>		

		<?php
		}	
		?>
		
		<?php
		if(!empty($contentInfo['Content']['description'])) {
		?>
		<div style="margin:0px 0px 30px 0px;">
			<?php echo $contentInfo['Content']['description'];?>
		</div>	
		<?php	
		}
		?>	
	</section>

<?php
}
?>