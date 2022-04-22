<?php
$mapCode = trim($this->Session->read('Site.embed_map'));

if(!empty($mapCode) && $this->request->params['action'] === 'display' && $this->request->params['pass'][0] === 'home') {
	?>
	<div class="mt-4">
		<div class="text-center my-3 table-responsive">
			<h4 class="m-3 text-decoration-underline">Locate Us</h4>
			<?= $mapCode ?>
		</div>

	</div>
	<?php
}
?>
