<section>
	<article>
		<header><h1>Groups</h1></header>
		<div class="text-end mt-3">
			<a href="/admin/groups/products/" class="btn btn-outline-primary btn-sm">Group Products</a>
			<a href="/admin/groups/add/" class="btn btn-primary btn-sm ms-2">+ Add New Group</a>
		</div>
		<div class="table-responsive mt-3">
			<?php
			if (!empty($groups)) {
				$i = 1;
				?>
				<table class="table table-sm small">
					<thead>
					<tr>
						<th>#</th>
						<th>Group</th>
						<th>Status</th>
						<th>MRP Base Rate</th>
						<th>Default Paper Rate</th>
						<th>Created</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($groups as $row) {

						$groupId = $row['Group']['id'];
						$groupTitle = $row['Group']['name'];
						$groupCreatedOn = date('d/m/Y', strtotime($row['Group']['created']));
						$groupActive = $row['Group']['active'];
						$groupRate = $row['Group']['rate'];
						$defaultRate = $row['Group']['default_paper_rate'];
						
						?>
						<tr>
							<td><?php echo $i; ?>.</td>
							<td>
								<?php
								echo $this->Html->link("$groupTitle", '/admin/groups/edit/' . $groupId, ['escape' => false, 'style' => 'text-decoration:none;']);
								?>
							</td>
							<td>
								<?php
								if ($groupActive) {
									?>
									<span
										class="text-success text-decoration-underline"
										type="button"
										onclick="showConfirmPopup('/admin/groups/activate/<?= $groupId ?>/false', 'Deactivate Group', 'Are you sure you want to deactivate this group?')"
									>Active</span>
									<?php
									// echo $this->Html->link('Active', '/admin/groups/activate/' . $groupId . '/false', ['escape' => false, 'style' => 'color:green'], 'Are you sure you want to deactivate this article? Deactivating will hide this article from public.');
								} else {
									?>
									<span
										class="text-danger text-decoration-underline"
										type="button"
										onclick="showConfirmPopup('/admin/groups/activate/<?= $groupId ?>/true', 'Activate Group', 'Are you sure you want to activate this group?')"
									>Inactive</span>
									<?php
									// echo $this->Html->link('Inactive', '/admin/groups/activate/' . $groupId . '/true', ['escape' => false, 'style' => 'color:red;'], 'Are you sure you want to make this article to public?');
								}
								?>
							</td>
							<td>
								<?= $groupRate ?>
							</td>
							<td>
								<?= $defaultRate > 0 ? $defaultRate : '-' ?>
							</td>
							<td><?php echo $groupCreatedOn; ?></td>

							<td class="text-nowrap text-end">
								<a href="/admin/groups/edit/<?= $groupId ?>" class="btn btn-sm btn-primary">Edit</a>
								<button
									class="ms-2 btn btn-sm btn-outline-danger"
									type="button"
									onclick="showConfirmPopup('/admin/groups/delete/<?= $groupId ?>', 'Delete Group', 'Are you sure you want to delete this?')"
								>Delete</button>

							</td>
						</tr>
						<?php
						$i++;
					}
					?>
					</tbody>
				</table>
				<?php
			} else {
				echo "<br> - No groups found";
			}
			?>
		</div>
	</article>
</section>

