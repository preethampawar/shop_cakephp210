<section>
	<article>



		<header><h1>Testimonials</h1></header>

		<?php
		if ((int)$this->Session->read('Site.show_testimonials') === 1) {
			?>
			<div class="alert alert-success mt-3">Testimonials slideshow on Homepage has been enabled. Go to Store Settings to disable slideshow.</div>
			<?php
		} else {
			?>
			<div class="alert alert-warning mt-3">Testimonials slideshow on Homepage has been disabled. Go to Store Settings to enable slideshow.</div>
			<?php
		}
		?>

		<div class="text-end mt-3">
			<a href="/admin/testimonials/add/" class="btn btn-primary btn-sm">+ Add New Testimonial</a>
		</div>
		<div class="table-responsive mt-3">
			<?php
			if (!empty($testimonials)) {
				$i = 1;
				?>
				<table class="table table-sm small">
					<thead>
					<tr>
						<th>#</th>
						<th>Testimonial</th>
						<th>Customer</th>
						<th>Status</th>
						<th>Created</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($testimonials as $row) {

						$testimonialId = $row['Testimonial']['id'];
						$testimonialTitle = $row['Testimonial']['title'];
						$blogCreatedOn = date('d/m/Y', strtotime($row['Testimonial']['created']));
						$testimonialActive = $row['Testimonial']['active'];
						$customerName = $row['Testimonial']['customer_name'];
						?>
						<tr>
							<td><?php echo $i; ?>.</td>
							<td>
								<?php
								echo $this->Html->link("$testimonialTitle", '/admin/testimonials/edit/' . $testimonialId, ['escape' => false, 'style' => 'text-decoration:none;']);
								?>
							</td>
							<td>
								<?= $customerName ?>
							</td>

							<td>
								<?php
								if ($testimonialActive) {
									echo $this->Html->link('Active', '/admin/testimonials/activate/' . $testimonialId . '/false', ['escape' => false, 'style' => 'color:green'], 'Are you sure you want to deactivate this article? Deactivating will hide this article from public.');
								} else {
									echo $this->Html->link('Inactive', '/admin/testimonials/activate/' . $testimonialId . '/true', ['escape' => false, 'style' => 'color:red;'], 'Are you sure you want to make this article to public?');
								}
								?>
							</td>
							<td><?php echo $blogCreatedOn; ?></td>

							<td class="text-nowrap">
								<a href="/admin/testimonials/edit/<?= $testimonialId ?>" class="btn btn-sm btn-primary">Edit</a>
								<button
									class="ms-2 btn btn-sm btn-outline-danger"
									type="button"
									onclick="showConfirmPopup('/admin/testimonials/delete/<?= $testimonialId ?>', 'Delete Testimonial', 'Are you sure you want to delete this?')"
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
				echo "<br> - No testimonials found";
			}
			?>
		</div>
	</article>
</section>

