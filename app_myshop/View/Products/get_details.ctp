<?php
$this->set('enableLightbox', false);

$selectBoxQuantityOptions = '';
for ($i = 1; $i <= 50; $i++) {
	$selectBoxQuantityOptions .= "<option value='$i'>$i</option>";
}

$categoryID = $categoryInfo['Category']['id'];
$categoryName = ucwords($categoryInfo['Category']['name']);
$categoryNameSlug = Inflector::slug($categoryName, '-');

$productID = $productInfo['Product']['id'];
$productName = ucwords($productInfo['Product']['name']);
$productNameSlug = Inflector::slug($productName, '-');
$productDesc = $productInfo['Product']['description'];
$showRequestPriceQuote = $productInfo['Product']['request_price_quote'];

$assetDomainUrl = Configure::read('AssetDomainUrl');
$productUploadedImages = $productInfo['Product']['images'] ? json_decode($productInfo['Product']['images']) : [];

$imageDetails = $this->App->getRearrangedImages($productUploadedImages);
$mrp = (float)$productInfo['Product']['mrp'];
$discount = (float)$productInfo['Product']['discount'];
$salePrice = $mrp - $discount;
$showDiscount = $mrp !== $salePrice;
$noStock = $productInfo['Product']['no_stock'];
$cartEnabled = $this->Session->read('Site.shopping_cart');
$hideProductPrice = $productInfo['Product']['hide_price'];

// SEO data
$pageUrl = $this->Html->url($this->request->here, true);
$pageUniqueIdentifier = $categoryID . '-' . $productID;
$highlightImageDetails = $this->App->getHighlightImage($productUploadedImages);
$thumbUrl = "/img/noimage.jpg";

if ($highlightImageDetails) {
	$thumbUrl = $assetDomainUrl . $highlightImageDetails['thumb']->imagePath;
}
$productImageUrl = $this->Html->url($thumbUrl, true);
?>
<script src="/vendor/jquery/jquery-3.6.0.slim.min.js"></script>
<div itemscope itemtype="http://schema.org/Product">
	<?php
	if (!$isAjax) {
		?>
		<nav aria-label="breadcrumb" class="mb-4">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a
							href="/products/show/<?php echo $categoryID; ?>"><?= $categoryName; ?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><?= $productName ?></li>
			</ol>
		</nav>


		<?php
	}
	?>
	<h1 itemprop="name"><?= $productName; ?></h1>

	<?php
	if ($ratingsInfo && (int)$ratingsInfo['ratingsCount'] > 0) {
		?>
		<div class="mt-3">
			<?= $this->element('show_rating_stars', ['rating' => $ratingsInfo['avgRating'], 'count' => $ratingsInfo['ratingsCount']]) ?>
		</div>
		<div class="mt-2 text-muted small">
			<div itemprop="aggregateRating"
				 itemscope itemtype="https://schema.org/AggregateRating">
				Rated <span itemprop="ratingValue" class="fw-bold"><?= $ratingsInfo['avgRating'] ?></span> out of <span
						class="fw-bold">5</span>
				based on <span itemprop="reviewCount"><?= $ratingsInfo['ratingsCount'] ?></span> customer reviews.
			</div>
		</div>
		<?php
	}
	?>


	<?php
	$imageUrl = null;
	$higlightImage = '';
	if (!empty($imageDetails)) {
		$this->set('enableLightbox', true);
		?>
		<div id="productImages"
			 class="mt-3 product-details-page-slider row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-2 g-lg-x-2 p-0">
			<?php

			$k = 0;
			foreach ($imageDetails as $row) {
				$k++;
				$imageID = random_int(1, 10000);
				$imageCaption = ($row['ori']->caption) ? $row['ori']->caption : $productName;
				$imageUrl = $assetDomainUrl . $row['ori']->imagePath;
				$imageThumbUrl = $assetDomainUrl . $row['thumb']->imagePath;
				?>
				<div class="col bg-white hoverHighlightPink" id="productCard<?php echo $imageID . '-' . $productID; ?>">

					<a
							href="<?= $imageUrl ?>"
							class="text-decoration-underline p-0"
							title='<?php echo $imageCaption; ?>'
							data-lightbox="productImages<?php echo $productID; ?>">
						<img
								itemprop="image"
								src="<?php echo $imageThumbUrl; ?>"
								data-original="<?php echo $imageUrl; ?>"
								class="img-thumbnail"
								role="button"
								alt="<?php echo $productName; ?>"
								id="Img<?php echo $imageID; ?>"
								loading="lazy"
						/>
					</a>

				</div>
				<?php
			}
			?>
			<div style="clear: both"></div>
		</div>
		<?php
	}
	?>

	<div id="productDetails" class="mt-3">
		<section>
			<article>
				<?php if (!$hideProductPrice): ?>

					<div class="mt-3 alert bg-light rounded border">
						<div class="d-flex">
							<h4>
								<span
										class="text-danger font-weight-bold"><?php echo $this->App->price($salePrice); ?></span>
							</h4>
							<?php if ($showDiscount): ?>
								<div class="ms-3">
									<span
											class="small text-decoration-line-through">MRP <?php echo $this->App->price($mrp); ?></span>
								</div>
							<?php endif; ?>
						</div>


						<?php if ($showDiscount): ?>
							<div class="small text-left text-success fw-bold">
								Save - <?php echo $this->App->priceOfferInfo($salePrice, $mrp); ?>
							</div>
						<?php endif; ?>

						<?php if ($cartEnabled && !$noStock): ?>
							<form id="AddToCart<?php echo $productID; ?>"
								  action="/shopping_carts/add/<?php echo $categoryID; ?>/<?php echo $productID; ?>"
								  method="post" class="flex">
								<div class="row mt-3">
									<div class="col">
										<label for="ShoppingCartProductQuantity<?php echo $productID; ?>"
											   class="small">Select Quantity</label>
										<select
												name="data[ShoppingCartProduct][quantity]"
												id="ShoppingCartProductQuantity<?php echo $productID; ?>"
												class="form-select form-select-sm"
												style="margin-top: 1px;"
										>
											<?php echo $selectBoxQuantityOptions; ?>
										</select>
									</div>
									<div class="col">
										<button
												onclick="addToCartFromProductDetailsPage('<?= $categoryID ?>', '<?= $productID ?>', $('#ShoppingCartProductQuantity<?php echo $productID; ?>').val())"
												type="button"
												class="btn btn-sm btn-primary mt-4">
											Add To Cart
										</button>
									</div>
								</div>
								<div class="ms-2" style="width:45px">
									<div id="addQtyProductDetails-spinner" class="d-none">
										<div class="spinner-border spinner-border-sm mt-2 text-primary" role="status">
											<span class="visually-hidden">Loading...</span>
										</div>
									</div>
								</div>
							</form>
						<?php elseif ($cartEnabled && $noStock): ?>
							<div class="row mt-3">
								<div class="col">
									<button type="button" class="btn btn-sm btn-outline-secondary disabled">Out of
										stock
									</button>
								</div>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php
				if (!empty($productDesc)) {
					?>
					<div class="mt-3 bg-light p-3 rounded border">
						<div itemprop="description" class="overflow-auto mt-2">
							<?php echo $productDesc; ?>
						</div>
					</div>
					<?php
				}
				?>

			</article>
		</section>
	</div>

	<div class="mt-5">
		<h2 class="">Did you love it? Let us know!</h2>
		<div class="mt-4">
			<?php
			$userRating = 0;
			if (!empty($userReview)) {
				$userRating = (int)($userReview['ProductReview']['rating'] ? $userReview['ProductReview']['rating'] : 0);
			}
			?>
			<span class="text-purple">Rate this product</span>
			<div class="mt-1 fs-5" id="ratingsDiv<?= $productID ?>">
				<span class="text-orange" id="starRating1" data-rating="1"
					  onclick="setRating(this.id, <?= $productID ?>)" role="button"><i class="far fa-star"></i></span>
				<span class="text-orange" id="starRating2" data-rating="2"
					  onclick="setRating(this.id, <?= $productID ?>)" role="button"><i class="far fa-star"></i></span>
				<span class="text-orange" id="starRating3" data-rating="3"
					  onclick="setRating(this.id, <?= $productID ?>)" role="button"><i class="far fa-star"></i></span>
				<span class="text-orange" id="starRating4" data-rating="4"
					  onclick="setRating(this.id, <?= $productID ?>)" role="button"><i class="far fa-star"></i></span>
				<span class="text-orange" id="starRating5" data-rating="5"
					  onclick="setRating(this.id, <?= $productID ?>)" role="button"><i class="far fa-star"></i></span>
			</div>

			<?php
			if (!empty($userReview)) {
				?>
				<div class="text-start mt-3">
					<div class="text-start text-purple"><label for="productReview<?= $productID ?>">Your review
							comments</label></div>
					<textarea id="productReview<?= $productID ?>" class="form-control"
							  rows="2"><?= $userReview['ProductReview']['comments'] ?></textarea>
				</div>
				<script defer>
					$(document).ready(function () {
						fillProductRatingStars(<?= $userRating ?>)
					})
				</script>
			<?php
			} else {
			?>
				<div class="text-start mt-3">
					<div class="text-start text-purple"><label for="productReview<?= $productID ?>">Write a
							review</label></div>
					<textarea id="productReview<?= $productID ?>" class="form-control" rows="2"
							  placeholder="Enter your comments here..."></textarea>
				</div>

				<div class="text-start mt-4">
					<?php
					if ($this->Session->check('User.id')) {
						?>
						<button type="button" id="submitReviewButton" class="btn btn-orange btn-sm"
								onclick="submitProductReview('<?= $categoryID ?>', '<?= $productID ?>')">Submit Review
						</button>
						<?php
					} else {
						?>
						<button type="button" class="btn btn-orange btn-sm disabled">Submit</button>
						<span class="ms-2 text-danger small">Please <a href="/users/login">login</a> to submit a review.</span>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<?php
	if ($productReviews) {
		?>
		<div class="mt-5">
			<h2 class="">Review Comments</h2>
			<div class="mt-4">
				<?php
				foreach($productReviews as $row) {
					?>
						<div class="border rounded p-3 mb-3">
							<div class="text-start small text-muted d-flex justify-content-between">
								<div class="small text-muted"><i class="fa fa-user"></i> <?= $row['ProductReview']['user_name'] ?></div>
								<div class="small"><small><?= $this->App->convertTimeToDays($row['ProductReview']['created']) ?></small></div>
							</div>

							<div class="mt-4 mb-2">
								<?= $this->element('show_rating_stars', ['rating' => $row['ProductReview']['rating']]) ?>
								<div class="mt-1 text-dark">
									<?= $row['ProductReview']['comments'] ?>
								</div>
							</div>
						</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
	?>


	<div class="mt-5">
		<?php
		if ($isAjax && !empty($this->Session->read('Site.contact_info'))):
			?>
			<div class="text-center small alert alert-info">
				<h4 class="mb-3 text-decoration-underline">Contact</h4>
				<?= $this->Session->read('Site.contact_info') ?>
			</div>
		<?php
		endif;
		?>

		<?php
		if ($isAjax && !empty($this->Session->read('Site.payment_info'))):
			?>

			<div class="text-center small alert alert-info">
				<h4 class="mb-3 text-decoration-underline">Payment Details</h4>
				<?= $this->Session->read('Site.payment_info') ?>
			</div>
		<?php
		endif;
		?>

		<?php
		if ($isAjax && !empty($this->Session->read('Site.tos'))):
			?>
			<div class="text-center small alert alert-warning">
				Please read our <a href="/sites/tos">Terms of Service</a> before you place an order with us.
			</div>
		<?php
		endif;
		?>
	</div>
</div>

<?php
$customMeta = '';
$customMeta .= $this->Html->meta(['property' => 'og:url', 'content' => $pageUrl, 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:type', 'content' => 'product', 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:title', 'content' => strip_tags($productName), 'inline' => false]);
$customMeta .= $this->Html->meta(['property' => 'og:description', 'content' => strip_tags(trim($productDesc) == '' ? $productName : $productDesc), 'inline' => false]);
$customMeta .= ($productImageUrl) ? $this->Html->meta(['property' => 'og:image', 'content' => $productImageUrl, 'inline' => false]) : '';
$customMeta .= $this->Html->meta(['property' => 'og:site_name', 'content' => $this->Session->read('Site.title'), 'inline' => false]);

$this->set('customMeta', $customMeta);
$this->set('title_for_layout', $productName);

$metaKeywords = trim($productInfo['Product']['meta_keywords']) != '' ? $productInfo['Product']['meta_keywords'] : $productName;
$metaDesc = trim($productInfo['Product']['meta_description']) != '' ? $productInfo['Product']['meta_description'] : $productDesc;

if (trim($metaKeywords)) {
	$this->Html->meta('keywords', strip_tags($metaKeywords), ['inline' => false]);
}

if (trim($metaDesc)) {
	$this->Html->meta('description', strip_tags($metaDesc), ['inline' => false]);
}
?>


<?php
/*
?>
<div id="disqus_thread" class="my-5"></div>
<script>
	//  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
	//  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables

	var disqus_config = function () {
		this.page.url = '<?= $pageUrl ?>';
		this.page.identifier = '<?= $pageUniqueIdentifier ?>';
	};

	(function() { // DON'T EDIT BELOW THIS LINE
		var d = document, s = d.createElement('script');
		s.src = 'https://https-www-herbsnnaturals-in.disqus.com/embed.js';
		s.setAttribute('data-timestamp', +new Date());
		(d.head || d.body).appendChild(s);
	})();
</script>
<?php
*/
?>
