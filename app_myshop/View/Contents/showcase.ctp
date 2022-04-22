<?php
if (!empty($contentInfo['Images'])) {
	echo $this->Html->css('nivo-slider/themes/default/default'); // Nivo slider default theme
	echo $this->Html->css('nivo-slider/nivo-slider'); // Nivo slider basic css
	echo $this->Html->script('jquery.nivo.slider'); // // Nivo slider js
	?>
	<div style="width:100%; margin-bottom:30px;">
		<div class="slider-wrapper theme-default">
			<div id="slider" class="nivoSlider">
				<?php
				$imageCount = 0;
				$captionDivs = '';

				foreach ($contentInfo['Images'] as $image) {
					$imageCount++;
					$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
					$imageCaption = (!empty($image['Image']['caption'])) ? trim($image['Image']['caption']) : '';

					$imageUrl = $this->Img->showImage('img/images/' . $imageID, ['height' => '500', 'width' => '960', 'type' => 'crop', 'quality' => '85', 'filename' => $imageID], ['style' => '', 'alt' => $imageCaption, 'title' => $imageCaption, 'escape' => false], true);
					//$imageThumbUrl = $this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop', 'quality'=>'75', 'filename'=>$imageID), array('style'=>'', 'alt'=>$imageCaption, 'title'=>$imageCaption, 'escape'=>false), true);
					?>

					<img src="<?php echo $imageUrl; ?>" alt="<?php echo $imageCaption; ?>"
						 title="#htmlcaption<?php echo $imageID; ?>"/>
					<?php
					// html caption div's
					if (!empty($imageCaption)) {
						$captionDivs .= '<div id="htmlcaption' . $imageID . '" class="nivo-html-caption">' . $imageCaption . '</div>';
					}
				}
				?>
			</div>
		</div>
	</div>
	<?php
	echo $captionDivs;
	?>
	<script type="text/javascript">
		$(window).load(function () {
			$('#slider').nivoSlider();
			$('.nivo-caption').css('padding', '0');
			$('.row3').css('background-color', '#fff');
		});
	</script>
	<?php

} else {
	?>
	<!--
	<p>This Site is under construction. Please check back soon.</p>
	-->
	<?php
}

if (!empty($contentInfo['Content']['description'])) {
	?>
	<div style="margin:0px 0px 30px 0px;">
		<?php echo $contentInfo['Content']['description']; ?>
	</div>
	<?php
}
?>

