<?php $this->start('dealers_report_menu');?>
<?php echo $this->element('dealers_menu');?>
<?php $this->end();?>

<?php
if($dealerInfo) {
?>
	<h1>Dealer Details (<?php echo $this->Html->link('Edit', array('controller'=>'dealers', 'action'=>'edit', $dealerInfo['Dealer']['id']), array('title'=>'Edit Dealer - '.$dealerInfo['Dealer']['name']));?>)</h1>
	<br>
	<p>
	Name: <?php echo $dealerInfo['Dealer']['name'];?><br>
	Created on: <?php echo date('d-m-Y', strtotime($dealerInfo['Dealer']['created']));?><br>
	</p>	
<?php } else { echo 'Dealer not found'; }  ?>