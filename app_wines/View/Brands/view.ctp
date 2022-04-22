<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>

<?php
if($brandInfo) {
?>
	<h1>Brand Details (<?php echo $this->Html->link('Edit', array('controller'=>'brands', 'action'=>'edit', $brandInfo['Brand']['id']), array('title'=>'Edit Brand - '.$brandInfo['Brand']['name']));?>)</h1>
	<br>
	<p>
	Name: <?php echo $brandInfo['Brand']['name'];?><br>
	Created on: <?php echo date('d-m-Y', strtotime($brandInfo['Brand']['created']));?><br>
	</p>	
<?php } else { echo 'Brand not found'; }  ?>