<?php
$this->set('title_for_layout', 'Highlights - Photo Gallery');
$this->Html->meta('keywords', $this->Session->read('Site.title') . ' gallery, photo gallery, highlights', ['inline' => false]);
$this->Html->meta('description', "Showing higlights from " . $this->Session->read('Site.title'), ['inline' => false]);
?>

<section id="ProductInfo">
	<article>
		<header>
			<h2><?php echo 'Highlights - ' . $this->Session->read('Site.title'); ?></h2>
		</header>

		<div id="productImages">
			<?php
			$imageCount = 0;
			$captionDivs = '';
			$requestWindows = '';
			if (!empty($contentInfo['Images'])) {

				foreach ($contentInfo['Images'] as $image) {
					$imageCount++;
					$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
					$imageCaption = (!empty($image['Image']['caption'])) ? $image['Image']['caption'] : '';
					$imageCaptionSlug = Inflector::slug($imageCaption, '-');

					$imageUrl = $this->Img->showImage('img/images/' . $imageID, ['height' => '600', 'width' => '600', 'type' => 'auto', 'quality' => '75', 'filename' => $imageCaptionSlug], ['style' => '', 'alt' => $imageCaption, 'title' => $imageCaption, 'escape' => false], true);
					// $imageThumbUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop'), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false), true);
					?>
					<div style="float:left; border:0px solid #fff; width:auto; padding:2px;">
						<a href="<?php echo $imageUrl; ?>" title="<?php echo (string)$imageCaption; ?>">
							<?php
							echo $this->Img->showImage('img/images/' . $imageID, ['height' => '60', 'width' => '60', 'type' => 'crop', 'quality' => '50', 'filename' => $imageCaptionSlug], ['style' => '', 'alt' => $imageCaption]);
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
		if ($imageCount) {
			$this->set('enableLightbox', true);
		} else {
			$this->set('enableLightbox', false);
			?>
			<p>No Images Found</p>
			<?php
		}
		?>
		<br><br>
	</article>
</section>
