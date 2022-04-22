<?php
$rating = (float)($rating ?? 0);
$count = (int)($count ?? 0);
$title = "Rated $rating out of 5 based on $count customer reviews";

if ($rating) {
?>
	<div class="d-flex justify-content-start" title="<?= $title ?>">
		<?php
		for ($i = 1; $i <= 5; $i++) {
			if ($i <= $rating) {
				$class = 'bi bi-star-fill';
			} elseif ($i == ceil($rating)) {
				$class = 'bi bi-star-half';
			} else {
				$class = 'bi bi-star';
			}
		?>
			<i class="<?= $class ?> text-orange me-1"></i>
		<?php
		}
		?>
		<?php
		if ($count) {
		?>
			<span class="text-muted small"><?= $count ?></span>
		<?php
		}
		?>
	</div>
<?php
}
