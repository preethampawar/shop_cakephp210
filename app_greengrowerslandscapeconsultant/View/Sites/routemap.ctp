<?php 	
	$map = ($this->Session->read('Site.embed_map')) ? $this->Session->read('Site.embed_map') : '';
?>
<section>
<?php
	if($map) {
		//echo $this->Html->link('Route map', array('controller'=>'sites', 'action'=>'routemap'));
	?>
	<h1>Route Map</h1>
	<div style="max-width:640px; overflow:hidden; float:left;" id="gmapDiv"></div>		
	
	<div style="clear:both;"></div>
	<script>
		// Load the SDK Asynchronously
		$(document).ready(function() {
			if(document.getElementById('gmapDiv')) {
				document.getElementById('gmapDiv').innerHTML = '<?php echo $map;?>';
			}
		});
	</script>
	<?php
	}
	?>
</section>