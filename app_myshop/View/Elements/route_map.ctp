<?php
$map = ($this->Session->read('Site.embed_map')) ? $this->Session->read('Site.embed_map') : '';
$site = $this->Session->read('Site.title');
if ($map) {
	?>
	<section>
		<h2>Route Map</h2>
		<p><?php echo $this->Html->link('Find us on google maps', ['controller' => 'sites', 'action' => 'routemap'], ['escape' => false]); ?></p>
		<div id="googleMapsLocation" style="width:300px; overflow: hidden;">
			<?php echo $map; ?>
		</div>
		<?php
		//$image = $this->Html->image('gmap_logo.jpg', array('alt'=>'Route map to '.$site, 'width'=>'300', 'height'=>'150'));
		//echo $this->Html->link($image, array('controller'=>'sites', 'action'=>'routemap'), array('escape'=>false));
		?>
	</section>
	<?php
}
?>
