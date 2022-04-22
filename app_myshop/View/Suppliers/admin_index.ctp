<section>
	<article>
		<header><h1>Suppliers</h1></header>
		<div class="text-end mt-3">
			<a href="/admin/suppliers/add/" class="btn btn-primary btn-sm">+ Add New Supplier</a>
		</div>
		<div class="table-responsive mt-3">
			<?php
			if (!empty($suppliers)) {
				$i = 1;
				?>
				<table class="table table-sm small">
					<thead>
					<tr>
						<th>#</th>
						<th>Supplier</th>
						<th>Status</th>
						<th>Phone</th>
						<th>Created</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($suppliers as $row) {

						$supplierId = $row['Supplier']['id'];
						$supplierTitle = $row['Supplier']['name'];
						$supplierCreatedOn = date('d/m/Y', strtotime($row['Supplier']['created']));
						$supplierActive = $row['Supplier']['active'];
						$supplierPhone = $row['Supplier']['phone'];
						?>
						<tr>
							<td><?php echo $i; ?>.</td>
							<td>
								<?php
								echo $this->Html->link("$supplierTitle", '/admin/suppliers/edit/' . $supplierId, ['escape' => false, 'style' => 'text-decoration:none;']);
								?>
							</td>
							<td>
								<?php
								if ($supplierActive) {
									?>
									<span
										class="text-success text-decoration-underline"
										type="button"
										onclick="showConfirmPopup('/admin/suppliers/activate/<?= $supplierId ?>/false', 'Deactivate Supplier', 'Are you sure you want to deactivate this supplier?')"
									>Active</span>
									<?php
									// echo $this->Html->link('Active', '/admin/suppliers/activate/' . $supplierId . '/false', ['escape' => false, 'style' => 'color:green'], 'Are you sure you want to deactivate this article? Deactivating will hide this article from public.');
								} else {
									?>
									<span
										class="text-danger text-decoration-underline"
										type="button"
										onclick="showConfirmPopup('/admin/suppliers/activate/<?= $supplierId ?>/true', 'Activate Supplier', 'Are you sure you want to activate this supplier?')"
									>Inactive</span>
									<?php
									// echo $this->Html->link('Inactive', '/admin/suppliers/activate/' . $supplierId . '/true', ['escape' => false, 'style' => 'color:red;'], 'Are you sure you want to make this article to public?');
								}
								?>
							</td>
							<td>
								<?= $supplierPhone ?>
							</td>
							<td><?php echo $supplierCreatedOn; ?></td>

							<td class="text-nowrap text-end">
								<a href="/admin/suppliers/edit/<?= $supplierId ?>" class="btn btn-sm btn-primary">Edit</a>
								<button
									class="ms-2 btn btn-sm btn-outline-danger"
									type="button"
									onclick="showConfirmPopup('/admin/suppliers/delete/<?= $supplierId ?>', 'Delete Supplier', 'Are you sure you want to delete this?')"
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
				echo "<br> - No suppliers found";
			}
			?>
		</div>
	</article>
</section>

