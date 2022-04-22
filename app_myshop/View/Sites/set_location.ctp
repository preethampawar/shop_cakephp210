<?php
$linkedLocations = Configure::read('LinkedLocations');
$subdomain = $this->request->subdomains()[0];

$locationTitle = null;
$locationUrl = null;

if (isset($linkedLocations[$subdomain][$locationId]) && !empty($linkedLocations[$subdomain][$locationId])) {
	$linkedLocation = $linkedLocations[$subdomain][$locationId];
	$locationTitle = $linkedLocation['title'];
	$locationUrl = $linkedLocation['url'];
	?>

		<script>
			setLocation('<?= $locationId ?>', '<?= $locationTitle ?>', '<?= $locationUrl ?>');
			window.location = '/';
		</script>
	<?php
} else {
	?>
	<script>
		selectLocation();
	</script>
	<?php
}

