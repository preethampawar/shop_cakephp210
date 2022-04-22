<?php
$slideshowEnabled = (int)$this->Session->read('Site.show_testimonials') === 1;

if($slideshowEnabled && $this->request->params['action'] === 'display' && $this->request->params['pass'][0] === 'home') {
?>

		<?php
		$siteId = $this->Session->read('Site.id');

		App::uses('Testimonial', 'Model');
		$testimonialModel = new Testimonial();
		$conditions = [
			'Testimonial.site_id' => $siteId,
			'Testimonial.active' => 1,
		];
		$fields = [
				'Testimonial.id',
				'Testimonial.title',
				'Testimonial.customer_name',
				'Testimonial.url',
		];
		$testimonials = $testimonialModel->find('all', ['conditions' => $conditions, 'fields'=>$fields, 'order'=>'Testimonial.created DESC', 'recursive'=> -1]);
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
	?>

	<?php
	if ($slideShowImages) {
	?>

	<div class="alert alert-warning pb-0">
		<h4 class="text-center text-decoration-underline">Testimonials</h4>
		<div id="testimonialSlideShow" class="carousel slide carousel-dark mt-4" data-bs-ride="carousel">
			<div class="carousel-indicators">
				<?php
				$i = 0;
				foreach($slideShowImages  as $row) {
					?>
					<button
							type="button"
							data-bs-target="#testimonialSlideShow"
							data-bs-slide-to="<?=$i?>"
							<?= $i === 0 ? 'class="active"' : '' ?>
							aria-current="true"
							aria-label="Slide <?=$i?>"></button>
					<?php
					$i++;
				}
				?>
			</div>
			<div class="carousel-inner">
				<?php
				$i = 0;
				foreach($slideShowImages  as $row) {
					$testimonialId = $row['testimonialId'];
					$title = $row['title'];
					$customerName = $row['customerName'];
					$linkUrl = $row['linkUrl'];
					?>
					<div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" data-bs-interval="6000">
						<div class="container text-center pb-5">
							<div class="fst-italic">
								<i class="fa fa-quote-left small text-orange me-1"></i>
								<?php
								if ($linkUrl) {
									?>
									<a href="<?= $linkUrl ?>" title="<?= $title ?>" class="text-decoration-none text-dark">
										<?= $title ?>
									</a>
									<?php
								} else {
									echo  $title;
								}
								?>
								<i class="fa fa-quote-right small text-orange ms-1"></i>
							</div>

							<?php
							if ($customerName) {
								?>
								<div class="text-center mt-2 text-orange"><?= $customerName ?></div>
								<?php
							}
							?>
							<div class="my-3 small"><a href="/testimonials/" class="btn btn-outline-secondary btn-sm rounded-pill py-0">Show All Reviews</a></div>

						</div>
					</div>
					<?php
					$i++;
				}
				?>
			</div>
		</div>
	</div>

<?php } ?>


<?php
}
?>
