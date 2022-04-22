<section>
	<article>
		<header><h2>Promo Codes</h2></header>
		<div class="text-end mt-3">
			<a href="/admin/promo_codes/add/" class="btn btn-primary btn-sm">+ Add New PromoCode</a>
		</div>
		<div class="table-responsive mt-3">
			<?php
			if (!empty($promoCodes)) {
				$i = 1;
				?>
				<table class="table table-sm small">
					<thead>
					<tr>
						<th>#</th>
						<th>Status</th>
						<th>Promo Code</th>
						<th>Discount</th>
						<th>Min Purchase</th>
						<th>Start Date</th>
						<th>End Date</th>
						<th>Redeem</th>
						<th>Created on</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ($promoCodes as $row) {

						$promoCodeId = $row['PromoCode']['id'];
						$promoCodeActive = $row['PromoCode']['active'];
						$promoCodeName = $row['PromoCode']['name'];
						$promoCodeDiscount = $row['PromoCode']['discount_value'];
						$promoCodeMinPurchaseValue = $row['PromoCode']['min_purchase_value'];
						$startDate = date('d/m/Y', strtotime($row['PromoCode']['start_date']));
						$endDate = date('d/m/Y', strtotime($row['PromoCode']['end_date']));
						$promoCodeRedeemType = $row['PromoCode']['redeem_type'];
						$terms = $row['PromoCode']['terms'];
						$createdOn = date('d/m/Y', strtotime($row['PromoCode']['created']));
						?>
						<tr>
							<td><?php echo $i; ?>.</td>
							<td>
								<?php
								if ($promoCodeActive) {
									echo $this->Html->link('Active', '/admin/promo_codes/activate/' . $promoCodeId . '/false', ['escape' => false, 'style' => 'color:green'], 'Are you sure you want to deactivate this code?');
								} else {
									echo $this->Html->link('Inactive', '/admin/promo_codes/activate/' . $promoCodeId . '/true', ['escape' => false, 'style' => 'color:red;'], 'Are you sure you want to make this promo code public?');
								}
								?>
							</td>
							<td>
								<a href="/admin/promo_codes/edit/<?= $promoCodeId ?>"><?= $promoCodeName ?></a>
							</td>
							<td>
								<?= $promoCodeDiscount ?>
							</td>
							<td>
								<?= $promoCodeMinPurchaseValue ?>
							</td>
							<td>
								<?= $startDate ?>
							</td>
							<td>
								<?= $endDate ?>
							</td>
							<td>
								<?= $promoCodeRedeemType ?>
							</td>
							<td>
								<?= $createdOn ?>
							</td>

							<td class="text-nowrap text-end">
								<a href="/admin/promo_codes/edit/<?= $promoCodeId ?>" class="btn btn-sm btn-primary">Edit</a>
								<button
									class="ms-2 btn btn-sm btn-outline-danger"
									type="button"
									onclick="showConfirmPopup('/admin/promo_codes/delete/<?= $promoCodeId ?>', 'Delete Promo Code', 'Are you sure you want to delete this?')"
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
				echo "<br> - No promo codes found";
			}
			?>
		</div>
	</article>
</section>

