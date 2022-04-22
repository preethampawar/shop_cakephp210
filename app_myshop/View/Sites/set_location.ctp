<?php
$siteConfiguration = $this->Session->check('siteConfiguration') ? $this->Session->read('siteConfiguration') : null;
$andriodAppBadgeUrl = $siteConfiguration['andriodAppBadgeUrl'] ?? null;
$andriodAppUrl = $siteConfiguration['andriodAppUrl'] ?? null;
$siteLocations = $siteConfiguration['locations'] ?? null;
$subdomain = $this->request->subdomains()[0];
$locationTitle = null;
$locationUrl = null;

if (isset($siteLocations[$locationId]) && !empty($siteLocations[$locationId])) {
	$linkedLocation = $siteLocations[$locationId];
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

