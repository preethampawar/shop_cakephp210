<?php
$slideShowImages = [];

if ($testimonials) {
	$i = 0;
	foreach ($testimonials as $testimonial) {

		$testimonialId = $testimonial['Testimonial']['id'];
		$title = $testimonial['Testimonial']['title'];
		$customerName = $testimonial['Testimonial']['customer_name'];
		$url = $testimonial['Testimonial']['url'];

		$slideShowImages[$i]['testimonialId'] = $testimonialId;
		$slideShowImages[$i]['title'] = htmlentities(trim($title));
		$slideShowImages[$i]['customerName'] = htmlentities(trim($customerName));
		$slideShowImages[$i]['linkUrl'] = $url;

		$i++;
	}
}

if ($slideShowImages) {
?>

<div class="mb-4 alert alert-warning">
	<h4 class="text-decoration-underline text-center">Testimonials</h4>
	<div id="homepageSlideshow" class="carousel carousel-dark slide" data-bs-ride="carousel">

		<div class="carousel-inner">
			<?php
			$i = 0;
			foreach($slideShowImages  as $row) {
				$testimonialId = $row['testimonialId'];
				$title = $row['title'];
				$customerName = $row['customerName'];
				$linkUrl = $row['linkUrl'];
				?>
				<div class="carousel-item <?= $i === 0 ? 'active' : '' ?> px-lg-5" data-bs-interval="2000" style="min-height: 200px;">

						<div class="d-block w-100 text-center py-4 px-5">
							<div class="fs-4 text-secondary px-lg-5">
								<?php
								if ($linkUrl) {
									?>
									<a href="<?= $linkUrl ?>" title="<?= $title ?>" class="text-decoration-none text-secondary">
										<?= $title ?>
									</a>
									<?php
								} else {
									?>
									<?= $title ?>
									<?php
								}
								?>
							</div>

							<?php
							if ($customerName) {
								?>
								<div class="text-center mt-3 fst-italic text-muted">
									<?= $customerName ?>
								</div>
								<?php
							}
							?>
						</div>


				</div>
				<?php
				$i++;
			}
			?>
			<button class="carousel-control-prev" type="button" data-bs-target="#homepageSlideshow" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#homepageSlideshow" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>

		</div>
	</div>
</div>

<?php } ?>
