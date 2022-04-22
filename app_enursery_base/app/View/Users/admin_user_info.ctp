<h1>Account Information</h1>
<?php
if($this->Session->read('User.superadmin')) {
	echo $this->Html->link('&laquo; Back', array('controller'=>'users', 'action'=>'index'), array('escape'=>false));
	echo '&nbsp;|&nbsp;';
}
?>
<?php echo $this->Html->link('Modify &raquo;', array('controller'=>'users', 'action'=>'edit', $userID), array('escape'=>false)); ?>
<br><br>
<table class='table'>
	<tr>
		<td style='width:150px;'>Site Name</td>
		<td><?php echo $userInfo['Site']['name'];?></td>
	</tr>
	<tr>
		<td style='width:150px;'>Name</td>
		<td><?php echo $userInfo['User']['name'];?></td>
	</tr>
	<tr>
		<td>Email Address</td>
		<td><?php echo $userInfo['User']['email'];?></td>
	</tr>
	<tr>
		<td>Phone</td>
		<td><?php echo $userInfo['User']['phone'];?></td>
	</tr>
	<tr>
		<td>Address</td>
		<td><?php echo $userInfo['User']['address'];?></td>
	</tr>
	<tr>
		<td>City</td>
		<td><?php echo $userInfo['User']['city'];?></td>
	</tr>
	<tr>
		<td>State</td>
		<td><?php echo $userInfo['User']['state'];?></td>
	</tr>
	<tr>
		<td>Country</td>
		<td><?php echo $userInfo['User']['country'];?></td>
	</tr>
	<tr>
		<td>Pincode</td>
		<td><?php echo $userInfo['User']['postcode'];?></td>
	</tr>
</table>