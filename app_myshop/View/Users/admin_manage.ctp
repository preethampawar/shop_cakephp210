<?php
App::uses('AppModel', 'Model');
?>
<h1>Manage Users</h1>
<div class="mt-3 text-end">
	<a href="/admin/users/newUser" class="btn btn-sm btn-primary">Add User</a>
</div>

<div class="mt-3">
	Showing all "<b><?= $this->Paginator->params()['count'] ?></b>" users
</div>

<div class="mt-3">
	<?php
	if (!empty($users)) {
	?>
		<div class="table-responsive">
			<table class="table text-center">
				<thead>
				<tr>
					<th>User Id</th>
					<th>Name</th>
					<th>Mobile</th>
					<th>User Type</th>
					<th>Created On</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ($users as $row) {
						$i++;
						?>
						<tr>
							<td><a href="/admin/users/editUser/<?= $row['User']['id'] ?>"><?= $row['User']['id'] ?></a></td>
							<td><?= $row['User']['name'] ?></td>
							<td><?= $row['User']['mobile'] ?></td>
							<td class="<?= $row['User']['type'] != User::USER_TYPE_BUYER ? 'text-danger' : 'text-muted' ?>"><?= User::USER_TYPE_OPTIONS[$row['User']['type']] ?></td>
							<td><?= date('d/m/Y h:i A', strtotime($row['User']['created'])) ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<br>
		<?php
		// prints X of Y, where X is current page and Y is number of pages
		echo 'Page ' . $this->Paginator->counter();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;';

		// Shows the next and previous links
		echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
		echo '&nbsp;&nbsp;';
		// Shows the page numbers
		echo $this->Paginator->numbers();

		echo '&nbsp;&nbsp;';
		echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
	} else {
		?>
		No users found.
		<?php
	}
	?>
</div>

