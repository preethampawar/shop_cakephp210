<?php
//debug($confirmedOrders);
//debug($shippedOrders);
?>
<script>
	function updateSupplier(supplierId, orderProductId) {
		if (supplierId != "" && confirm("Are you sure you want to update the supplier?")) {
			document.getElementById("OrderProductAdminDetailsForm"+orderProductId).submit()
		}

		if (supplierId == "" && confirm("Are you sure you want to remove the supplier?")) {
			document.getElementById("OrderProductAdminDetailsForm"+orderProductId).submit()
		}
	}
</script>

<div class="accordion" id="deliveryStatus">
	<div class="accordion-item">
		<h2 class="accordion-header" id="headingOne">
			<span class="accordion-button text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				New Orders <span class="badge bg-danger ms-2 rounded-circle"><?= count($confirmedOrders) ?></span>
			</span>
		</h2>
		<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#deliveryStatus">
			<div class="accordion-body">
				<?php
				if ($confirmedOrders) {
					?>
					<table class="w-100">
						<tbody>
						<?php
						$i = 0;
						foreach($confirmedOrders as $row) {
							$i++;
							?>
							<tr>
								<td class="py-3">
									<div class="bg-light rounded p-2 py-3">
										<div class="fs-5">
											<a class="text-decoration-none link-danger d-block" data-bs-toggle="collapse" href="#orderNo<?= $row['Order']['id'] ?>" role="button" aria-expanded="false" aria-controls="collapseExample">

												<div class="d-flex justify-content-between">
													<div><?= $i ?>. Order No. <span class="fw-bold">#<?= $row['Order']['id'] ?></span></div>
													<span class="small"><i class="fa fa-circle-chevron-down ms-2 small"></i></span>
												</div>
											</a>
										</div>

										<div class="collapse mt-3 small" id="orderNo<?= $row['Order']['id'] ?>">
											<?php
											if ($row['OrderProduct']) {
												?>
												<div class="card card-body p-2 mb-3">
													<table class="table table-sm table-striped">
														<thead>
														<tr>
															<th>Product Name</th>
															<th>Supplier</th>
															<th style="width: 60px;" class="text-center">Qty</th>
														</tr>
														</thead>
														<tbody>
														<?php
														foreach($row['OrderProduct'] as $orderProduct) {
															$orderProductId = $orderProduct['id'];
															$orderProductSupplierId = $orderProduct['supplier_id'];
															?>
															<tr>
																<td><?= $orderProduct['product_name'] ?></td>
																<td>
																	<?php
																	echo $this->Form->create('OrderProduct', ['url' => '/deliveries/updateOrderProductSupplier', 'id' => 'OrderProductAdminDetailsForm' . $orderProductId]);
																	echo $this->Form->hidden('id', ['value' => $orderProductId]);
																	echo $this->Form->select('supplier_id', $suppliers, [
																			'empty' => '- Select Supplier -',
																			'class' => 'form-select form-select-sm ' . ($orderProductSupplierId ? 'text-success border-success' : 'text-danger border-danger'),
																			'onchange' => 'updateSupplier(this.value, "' . $orderProductId . '")',
																			'default' => $orderProductSupplierId,
																	]);
																	echo $this->Form->end();
																	?>
																</td>
																<td class="text-center"><?= $orderProduct['quantity'] ?></td>
															</tr>
															<?php
														}
														?>
														</tbody>
													</table>

													<div class="mt-2 small">
														<span class="fw-bold">Delivery Details</span>
														<hr class="my-1">
														<div class="">
															<table class="table-sm">
																<tbody>
																<tr>
																	<td>Name</td>
																	<td class="fw-bold"><?= $row['Order']['customer_name'] ?></td>
																</tr>
																<tr>
																	<td>Phone</td>
																	<td class="fw-bold"><?= $row['Order']['customer_phone'] ?></td>
																</tr>
																<tr>
																	<td>Address</td>
																	<td class="fw-bold"><?= $row['Order']['customer_address'] ?></td>
																</tr>
																<tr>
																	<td>Message</td>
																	<td class="fw-bold"><?= $row['Order']['customer_message'] ?></td>
																</tr>
																</tbody>
															</table>
														</div>
													</div>

													<div class="text-center mt-4 mb-2">

														<button
															onclick="showConfirmPopup('/deliveries/updateOrderStatusShipped/<?= base64_encode($row['Order']['id']) ?>', 'Order No. #<?= $row['Order']['id'] ?>', 'Are you sure you have picked up this order?', 'Yes'); return false;"
															class="btn btn-orange btn-sm"
															title="Order #<?= $row['Order']['id'] ?> - Click if you have picked the order from supplier"
														>Picked-Up</button>
													</div>
												</div>
												<?php
											} else {
												echo 'No products found.';
											}
											?>
										</div>
									</div>

								</td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
					<?php
				} else {
					echo '<span class="text-muted small">No Orders found.</span>';
				}
				?>
			</div>
		</div>
	</div>
	<div class="accordion-item">
		<h2 class="accordion-header" id="headingTwo">
			<span class="accordion-button text-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				Picked-Up Orders <span class="badge bg-primary ms-2 rounded-circle"><?= count($shippedOrders) ?></span>
			</span>
		</h2>
		<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#deliveryStatus">
			<div class="accordion-body">
				<?php
				if ($shippedOrders) {
					?>
					<table class="w-100">
						<tbody>
						<?php
						$i = 0;
						foreach($shippedOrders as $row) {
							//debug($row);
							$i++;
							?>
							<tr>
								<td class="py-3">
									<div class="bg-light rounded p-2 py-3">
										<div class="fs-5">
											<a class="text-decoration-none link-primary" data-bs-toggle="collapse" href="#orderNo<?= $row['Order']['id'] ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
												<div class="d-flex justify-content-between">
													<div>
														<?= $i ?>. Order No. <span class="fw-bold">#<?= $row['Order']['id'] ?></span>
														  - <span class="text-orange"><?= $this->App->price($row['Order']['total_order_amount']) ?></span>
													</div>
													<span class="small"><i class="fa fa-circle-chevron-down ms-2 small"></i></span>
												</div>
											</a>
										</div>

										<div class="collapse mt-3 small" id="orderNo<?= $row['Order']['id'] ?>">
											<?php
											if ($row['OrderProduct']) {
												?>
												<div class="card card-body p-2 mb-3">
													<table class="table table-sm table-striped">
														<thead>
														<tr>
															<th>Product Name</th>
															<th>Supplier</th>
															<th style="width: 60px;" class="text-center">Qty</th>
														</tr>
														</thead>
														<tbody>
														<?php
														foreach($row['OrderProduct'] as $orderProduct) {
															$orderProductId = $orderProduct['id'];
															$orderProductSupplierId = $orderProduct['supplier_id'];
															?>
															<tr>
																<td><?= $orderProduct['product_name'] ?></td>
																<td>
																	<?php
																	echo $this->Form->create('OrderProduct', ['url' => '/deliveries/updateOrderProductSupplier', 'id' => 'OrderProductAdminDetailsForm' . $orderProductId]);
																	echo $this->Form->hidden('id', ['value' => $orderProductId]);
																	echo $this->Form->select('supplier_id', $suppliers, [
																			'empty' => '- Select Supplier -',
																			'class' => 'form-select form-select-sm ' . ($orderProductSupplierId ? 'text-success border-success' : 'text-danger border-danger'),
																			'onchange' => 'updateSupplier(this.value, "' . $orderProductId . '")',
																			'default' => $orderProductSupplierId,
																	]);
																	echo $this->Form->end();
																	?>
																</td>
																<td class="text-center"><?= $orderProduct['quantity'] ?></td>
															</tr>
															<?php
														}
														?>
														</tbody>
													</table>

													<div class="mt-2 small">
														<span class="fw-bold">Delivery Details</span>
														<hr class="my-1">
														<div class="">
															<table class="table-sm">
																<tbody>
																<tr>
																	<td>Name</td>
																	<td class="fw-bold"><?= $row['Order']['customer_name'] ?></td>
																</tr>
																<tr>
																	<td>Phone</td>
																	<td class="fw-bold"><?= $row['Order']['customer_phone'] ?></td>
																</tr>
																<tr>
																	<td>Address</td>
																	<td class="fw-bold"><?= $row['Order']['customer_address'] ?></td>
																</tr>
																<tr>
																	<td>Message</td>
																	<td class="fw-bold"><?= $row['Order']['customer_message'] ?></td>
																</tr>
																</tbody>
															</table>
														</div>
													</div>

													<div class="text-center mt-4 mb-2">														
														<button
																onclick="showConfirmPopup('/deliveries/updateOrderStatusDelivered/<?= base64_encode($row['Order']['id']) ?>', 'Order No. #<?= $row['Order']['id'] ?>', 'Are you sure you have delivered this order?', 'Yes'); return false;"
																class="btn btn-success btn-md"
																title="Order #<?= $row['Order']['id'] ?> - Click if you have delivered the order to customer"
														>Delivered</button>
														
														<!-- <button
																onclick="orderIsDelivered('<?= base64_encode($row['Order']['id']) ?>', '<?= $row['Order']['id'] ?>')"
																class="btn btn-success btn-md"
																title="Order #<?= $row['Order']['id'] ?> - Click if you have delivered the order to customer"
														>Delivered</button> -->
													</div>
												</div>
												<?php
											} else {
												echo '<span class="text-muted small">No products found.</span>';
											}
											?>
										</div>
									</div>
								</td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
					<?php
				} else {
					echo '<span class="text-muted small">All good. No Orders found.</span>';
				}
				?>
			</div>
		</div>
	</div>
</div>
