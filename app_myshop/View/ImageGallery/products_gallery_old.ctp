<?php
echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom'); // jQuery UI
echo $this->Html->script('jquery-ui-1.8.18.custom.min'); // jQuery UI
?>

<?php
echo $this->Html->css('nivo-slider/themes/default/default'); // Nivo slider default theme
echo $this->Html->css('nivo-slider/nivo-slider'); // Nivo slider basic css
echo $this->Html->script('jquery.nivo.slider'); // // Nivo slider js
?>

<section id="ProductInfo">
	<article>
		<header>
			<h2 style="text-align:center;">Products Gallery</h2>
		</header>
		<div style="width:800px; margin:auto;">
			<div class="slider-wrapper theme-default">
				<div id="slider" class="nivoSlider">
					<?php
					$imageCount = 0;
					$captionDivs = '';
					$requestWindows = '';
					if (!empty($allCategories)) {
						foreach ($allCategories as $categoryInfo) {

							if (!empty($categoryInfo['CategoryProducts'])) {
								foreach ($categoryInfo['CategoryProducts'] as $row) {
									$productID = $row['Product']['id'];
									$productName = $row['Product']['name'];
									$productNameSlug = Inflector::slug($productName, '-');

									$categoryID = $categoryInfo['Category']['id'];
									$categoryName = $categoryInfo['Category']['name'];
									$categoryNameSlug = Inflector::slug($categoryName, '-');

									$categoryLink = $this->Html->link($categoryName, '/products/show/' . $categoryID . '/' . $categoryNameSlug, ['title' => $categoryName, 'escape' => false, 'style' => 'background-color:transparent;', 'target' => '_blank']);
									$productLink = $this->Html->link($productName, '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug, ['title' => $categoryName . ' &raquo; ' . $productName, 'escape' => false, 'style' => 'background-color:transparent;', 'target' => '_blank']);

									if (!empty($row['Images'])) {
										foreach ($row['Images'] as $image) {
											//debug($image['Image']['id']); exit;

											$imageCount++;
											$imageID = (isset($image['Image']['id'])) ? $image['Image']['id'] : 0;
											$imageCaption = (!empty($image['Image']['caption'])) ? $image['Image']['caption'] : '';

											$imageUrl = $this->Img->showImage('img/images/' . $imageID, ['height' => '500', 'width' => '800', 'type' => 'crop'], ['style' => '', 'alt' => $productName, 'title' => $imageCaption, 'escape' => false], true);
											$imageThumbUrl = $this->Img->showImage('img/images/' . $imageID, ['height' => '60', 'width' => '60', 'type' => 'crop'], ['style' => '', 'alt' => $productName, 'title' => $imageCaption, 'escape' => false], true);

											?>
											<img src="<?php echo $imageUrl; ?>"
												 data-thumb="<?php echo $imageThumbUrl; ?>"
												 alt="<?php echo $imageCaption; ?>"
												 title="#htmlcaption<?php echo $imageID; ?>"/>


											<?php
											$requestButtons = null;
											if ($this->Session->read('Site.request_price_quote')) {
												$requestButtons = '
											<div style="text-align:center;">
											' . $this->Form->submit('Request Price Quote &raquo;', ['div' => false, 'escape' => false, 'style' => 'width:200px;', 'onclick' => "showRequestPriceQuoteForm('$categoryID','$productID', '$productName')"]) . '
											&nbsp;|&nbsp;
											' . $this->Form->submit('Add To Cart &raquo;', ['div' => false, 'escape' => false, 'style' => 'width:200px;', 'onclick' => "showAddToCartForm('$categoryID','$productID', '$productName')"]) . '
											</div>
											';
											}
											?>

											<?php
											// html caption div's
											$imageCaption = ($imageCaption) ? $imageCaption . '<br><br>' : null;
											$captionDivs .= '
										<div id="htmlcaption' . $imageID . '" class="nivo-html-caption">
											' . $categoryLink . ' &raquo; ' . $productLink . ' <br><br>
											' . $imageCaption . '
											' . $requestButtons . '
										</div>
										';
										}

									}

									$requestWindows .= '
									<div style="display:none;" id="addToShoppingListDiv' . $productID . '">' . $this->element('addtocart_form', ['productID' => $productID, 'categoryID' => $categoryID, 'productName' => $productName, 'categoryName' => $categoryName]) . '</div>

									<div style="display:none;" id="requestPriceQuoteDiv' . $productID . '">' . $this->element('request_price_quote_form', ['productID' => $productID, 'categoryID' => $categoryID, 'productName' => $productName, 'categoryName' => $categoryName]) . '</div>
								';
									?>


									<?php
								}
							}
						}
						?>
						<?php
					}
					?>
				</div>
				<div id="htmlcaption" class="nivo-html-caption">
					<strong>This</strong> is an example of a <em>HTML</em> caption with <a href="#">a link</a>.
				</div>
			</div>
		</div>
		<?php
		if ($imageCount) {
			echo $requestWindows;
			echo $captionDivs;
			?>
			<script type="text/javascript">
				$(window).load(function () {
					$('#slider').nivoSlider();
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
	</article>
</section>
