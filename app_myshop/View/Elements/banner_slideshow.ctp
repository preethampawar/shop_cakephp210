<?php
$slideshowEnabled = (int)$this->Session->read('Site.show_banners') === 1;

if($slideshowEnabled && $this->request->params['action'] === 'display' && $this->request->params['pass'][0] === 'home') {
?>

		<?php
		$siteId = $this->Session->read('Site.id');

		App::uses('Banner', 'Model');
		$bannerModel = new Banner();
		$conditions = [
			'Banner.site_id' => $siteId,
			'Banner.active' => 1,
		];
		$fields = [
				'Banner.id',
				'Banner.title',
				'Banner.description',
				'Banner.images',
				'Banner.url',
		];
		$banners = $bannerModel->find('all', ['conditions' => $conditions, 'fields'=>$fields, 'order'=>'Banner.created DESC', 'recursive'=> -1]);
		$slideShowImages = [];

		if ($banners) {
			$assetDomainUrl = Configure::read('AssetDomainUrl');
			$i = 0;
			foreach ($banners as $banner) {

				$bannerId = $banner['Banner']['id'];
				$title = $banner['Banner']['title'];
				$description = $banner['Banner']['description'];
				$url = $banner['Banner']['url'];
				$bannerUploadedImages = $banner['Banner']['images'] ? json_decode($banner['Banner']['images']) : [];
				$highlightImage = $this->App->getHighlightImage($bannerUploadedImages);

				if ($highlightImage) {
					$image = $highlightImage['thumb'];
					$imageUrl = $assetDomainUrl.$image->imagePath;

					$slideShowImages[$i]['bannerId'] = $banner['Banner']['id'];
					$slideShowImages[$i]['title'] = htmlentities(trim($banner['Banner']['title']));
					$slideShowImages[$i]['description'] = htmlentities(trim($banner['Banner']['description']));
					$slideShowImages[$i]['linkUrl'] = $banner['Banner']['url'];
					$slideShowImages[$i]['imageUrl'] = $imageUrl;
				}
				$i++;
			}
		}
		?>

		<?php if ($slideShowImages) { ?>

			<div class="mb-4 container-xxl p-0">
				<div id="homepageSlideshow" class="carousel slide carousel-fade" data-bs-ride="carousel">
					<div class="carousel-indicators">
						<?php
						$i = 0;
						foreach($slideShowImages  as $row) {
							?>
							<button
									type="button"
									data-bs-target="#homepageSlideshow"
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
							$bannerId = $row['bannerId'];
							$title = $row['title'];
							$desc = $row['description'];
							$linkUrl = $row['linkUrl'];
							$imageUrl = $row['imageUrl'];
							$loadingImageUrl = '/loading4_1080_360.jpg';
							?>
							<div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" data-bs-interval="4000">
								<a href="<?= $linkUrl ?>" title="<?= $title ?>" class="text-decoration-none">
									<img
										src="<?= $loadingImageUrl ?>"
										data-original="<?php echo $imageUrl; ?>"
										class="lazy d-block w-100 bg-light img-fluid"
										alt=""
										width="1200"
										height="480"
										border="0"
									>
								</a>
								<div class="carousel-caption d-none d-md-block" role="button">
									<p>
										<?php if ($title) {
											?>
											<span class="bg-dark bg-opacity-25 text-opacity-100 text-white shadow-sm rounded px-2"><?= $title ?></span><br>
											<?php
										}
										if ($desc) {
											?>
											<span class="bg-dark bg-opacity-25 text-opacity-100 text-white shadow-sm rounded px-2 small"><?= $desc ?></span>
										<?php } ?>
									</p>
								</div>
							</div>
							<?php
							$i++;
						}
						?>

					</div>
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

		<?php } ?>


<?php
}
?>
