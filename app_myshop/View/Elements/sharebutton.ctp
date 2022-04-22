<?php
$isMobileApp = $this->Session->check('isMobileApp') ? $this->Session->read('isMobileApp') : false;

if (!$isMobileApp) {
	$title = $title ?? '';
	$text = $text ?? '';
	$url = $url ?? '';
	$files = $files ?? '[]';
	$class = $class ?? '';
	$showAsButton = (bool)($showAsButton ?? true);
	?>

	<?php
	if ($showAsButton === true) {
	?>
		<button title="Share" class="btn btn-sm btn-outline-success rounded-circle shareButton <?= $class ?> d-none" onclick="shareThis('<?= $title ?>', '<?= $text ?>', '<?= $url ?>', <?= $files ?>)">
			<i class="fa fa-share-nodes"></i>
		</button>
	<?php
	} else {
		?>
		<a href="#" title="Share" role="button" class="shareButton <?= $class ?> d-none" onclick="shareThis('<?= $title ?>', '<?= $text ?>', '<?= $url ?>', <?= $files ?>); return false;">
			<i class="fa fa-share-nodes"></i>
		</a>
		<?php
	}
}
