<?php
$map = ($this->Session->read('Site.embed_map')) ? $this->Session->read('Site.embed_map') : '';
?>
<section>
	<?php
	if ($map) {
		//echo $this->Html->link('Route map', array('controller'=>'sites', 'action'=>'routemap'));
		?>
		<h1>Route Map</h1>
		<div style="width:100%; height:450px;" id="gmapDiv">
			<?php echo $map; ?>
		</div>
		<?php
	}
	?>
	<br>
	<p>
		<?php echo $this->element('get_in_contact'); ?>
	</p>
</section>
