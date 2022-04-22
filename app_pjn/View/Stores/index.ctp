<style type="text/css">
	#selectStoreDiv {
		font-size: 120%;
	}
</style>
<article>
	<header><h1>My Stores</h1></header>
	<p>
		<?php
		if ($this->Session->read('manager') == '1') {
			echo $this->Html->link('+ Add New Store', ['controller' => 'stores', 'action' => 'add']);
			?>
			&nbsp;&nbsp; | &nbsp;&nbsp;
			<a href="/users/add" class="">+ Add New User</a>
			<?php
		}
		//echo $this->Html->link('Backup database', array('controller'=>'stores', 'action'=>'createbackup'));
		?>
	</p>
	<?php
	if (!empty($stores)) {
		?><br>
		<div id="selectStoreDiv">
			<h3>Select a store</h3>
			<table class='table table-striped'>
				<thead>
				<tr>
					<th style="width:30px;">Sl.No.</th>
					<th>
						Store
					</th>
					<?php
					if ($this->Session->read('manager') == '1') {
						?>
						<th>Owner</th>
						<?php
					}
					?>
					<th>Status</th>
					<th>Expiry Date</th>
					<th>Created on</th>
					<th style="width:200px; text-align:center;">Actions</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$k = 0;
				foreach ($stores as $row) {
					$k++;
					?>
					<tr>
						<td><?php echo $k; ?></td>
						<td>
							<?php
							echo $this->Html->link(strtoupper($row['Store']['name']), ['controller' => 'stores', 'action' => 'selectStore', $row['Store']['id']], ['title' => 'Select this store']);
							?>
						</td>
						<?php
						if ($this->Session->read('manager') == '1') {
							?>
							<td>
								<a href="/users/edit/<?php echo $row['Store']['user_id']; ?>">
									<?php echo $userInfo[$row['Store']['user_id']]; ?>
								</a>
							</td>
							<?php
						}
						?>
						<td>
							<?php
							$status = 'active';
							if ($row['Store']['active']) {
								if ($row['Store']['name'] != 'test') {
									// check for expiry
									$storeExpiredOn = $row['Store']['expiry_date'];
									$unixTimeStoreExpiry = strtotime($storeExpiredOn);
									$unixTimeNow = strtotime("now");
									if ($unixTimeNow > $unixTimeStoreExpiry) {
										$status = 'expired';
									}
								}

							} else {
								$status = 'inactive';
							}

							if ($status == 'active') echo 'Active';
							if ($status == 'inactive') echo 'inactive';
							if ($status == 'expired') echo 'Expired';
							?>
						</td>
						<td><?php echo $row['Store']['expiry_date'] ? date('d-m-Y', strtotime($row['Store']['expiry_date'])) : '-'; ?></td>
						<td><?php echo date('d-m-Y', strtotime($row['Store']['created'])); ?></td>
						<td style="text-align:center;">
							<div class="dropdown dropleft">
								<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton"
										data-toggle="dropdown" aria-expanded="false">
									Actions
								</button>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<li><a href="/stores/settings/<?php echo $row['Store']['id']; ?>"
										   class="dropdown-item">Settings</a></li>
									<?php
									if ($this->Session->read('manager') == '1') {
										?>
										<li role="separator" class="dropdown-divider"></li>
										<li>
											<?php echo $this->Html->link('Edit Details', ['controller' => 'stores', 'action' => 'edit', $row['Store']['id']], ['title' => 'Edit ' . $row['Store']['name'], 'class' => 'dropdown-item']); ?>
										</li>
										<li role="separator" class="dropdown-divider"></li>
										<li>
											<form
												method="post"
												style=""
												name="sales_<?php echo $row['Store']['id']; ?>"
												id="sales_<?php echo $row['Store']['id']; ?>"
												action="<?php echo $this->Html->url("/stores/delete/" . $row['Store']['id']); ?>">
											</form>
											<a
												href="javascript:return false;"
												onclick="if (confirm('All store related data like Products, Sales, Purchases, Expenses etc. will be deleted. \nThis action is irreversable..\n\nAre you sure you want to delete this store - <?php echo $row['Store']['name']; ?> from the list?')) { $('#sales_<?php echo $row['Store']['id']; ?>').submit(); } event.returnValue = false; return false;"
												class="dropdown-item"
											>
												Delete Store
											</a>
										</li>
										<?php
									}
									?>
								</ul>
							</div>

						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
	} else {
		?>
		<p>No Stores Found</p>
		<?php
	}
	?>

</article>
