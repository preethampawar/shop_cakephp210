<?php
$rating = (float)($rating ?? 0);
$count = (int)($count ?? 0);
$title = "Rated $rating out of 5 based on $count customer reviews";

if ($rating) {
?>
	<div class="d-flex justify-content-start" title="<?= $title ?>">
		<?php
		for($i=1; $i <= 5; $i++) {
			if ($i <= $rating) {
				$class = 'fa fa-star';
			} elseif ($i == ceil($rating)) {
				$class = 'fas fa-star-half-alt';
			} else {
				$class = 'far fa-star';
			}
			?>
			<span class="text-orange me-1">
				<i class="<?= $class ?>"></i>
			</span>
			<?php
		}
		?>
		<?php
		if ($count){
			?>
			<span class="text-muted small mt-1"><?= $count ?></span>
			<?php
		}
		?>

	</div>
<?php
}

