<?php $this->start('franchise_menu'); ?>
<?php echo $this->element('franchise_menu'); ?>
<?php $this->end(); ?>
<a href="/franchises/" class=""> &laquo; Back to franchise list</a><br>
<h1>
	Franchise Details: <?php echo $franchiseInfo['Franchise']['name']; ?>
	(<a href="/franchises/edit/<?php echo $franchiseInfo['Franchise']['id']; ?>" class="">Edit</a>)
</h1><br>
<table class="table table-striped" style="width:600px;">
	<tbody>
	<tr>
		<td>Name</td>
		<td><?php echo $franchiseInfo['Franchise']['name']; ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td><?php echo $franchiseInfo['Franchise']['is_active'] ? 'Active' : 'Inactive'; ?></td>
	</tr>
	<tr>
		<td>Code</td>
		<td><?php echo $franchiseInfo['Franchise']['code']; ?></td>
	</tr>
	<tr>
		<td>Login Pin</td>
		<td><?php echo $franchiseInfo['Franchise']['login_pin']; ?></td>
	</tr>
	<tr>
		<td>Mobile No.</td>
		<td><?php echo $franchiseInfo['Franchise']['mobile']; ?></td>
	</tr>
	<tr>
		<td>Email Address</td>
		<td><?php echo $franchiseInfo['Franchise']['email']; ?></td>
	</tr>
	<tr>
		<td>City</td>
		<td><?php echo $franchiseInfo['Franchise']['city']; ?></td>
	</tr>
	<tr>
		<td>District</td>
		<td><?php echo $franchiseInfo['Franchise']['district']; ?></td>
	</tr>
	<tr>
		<td>State</td>
		<td><?php echo $franchiseInfo['Franchise']['state']; ?></td>
	</tr>
	<tr>
		<td>Country</td>
		<td><?php echo $franchiseInfo['Franchise']['country']; ?></td>
	</tr>
	<tr>
		<td>Address</td>
		<td><?php echo $franchiseInfo['Franchise']['address1']; ?></td>
	</tr>
	</tbody>
</table>
