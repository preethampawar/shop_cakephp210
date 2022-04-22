<section>
	<div class="text-end">
		<a href="/admin/suppliers/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>
		<h1>Supplier Product Rates</h1>
		<div class="text-muted small">Update Supplier product rates w.r.t paper rate</div>

		<div class="mt-3 card card-body bg-light">
			<h6>Select Supplier</h6>
			<?php
			echo $this->Form->select('Supplier.name', $suppliers, [
						'empty' => '- Select Supplier -',
						'onchange' => 'window.location = "/admin/suppliers/products/"+this.value',
						'default' => $supplierId,
						'class' => 'form-select form-select-sm'
					]);
			?>

			<?php
			if($supplierId) {
			?>

			<h6 class="mt-3">Select Group</h6>
			<?php
			echo $this->Form->select('Group.name', $groupsList, [
						'empty' => '- Show All -',
						'onchange' => 'window.location = "/admin/suppliers/products/' . $supplierId . '/"+this.value',
						'default' => $groupId,
						'class' => 'form-select form-select-sm'
					]);

			?>
		</div>
	
		
		<header class="mt-4"><h2><?= $suppliers[$supplierId] ?> - Products</h2></header>

		
		
		<?= $this->Form->create(null, []) ?>
		<div class="table-responsive">
			<table class="table table-sm table-striped small">
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Product Name</th>
				<th>Group</th>
				<th class="text-center">Relation</th>
				<th class="text-center">Relative Paper Price</th>
				
				<th class="text-center">Relation2</th>
				<th class="text-center">Relative Paper Price2</th>
				<th class="text-center">Enabled</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach($products as $row) {
				$i++;
				$productId = $row['Product']['id'];
				$productName = $row['Product']['name'];
				$priceRelation = $row['Product']['relative_price_relation'];
				$relativeBasePrice = $row['Product']['relative_base_price'] > 0 ? $row['Product']['relative_base_price'] : 0;

				// $priceRelation2 = $row['Product']['price_relation2'];
				// $relativeBasePrice2 = $row['Product']['relative_base_price2'] > 0 ? $row['Product']['relative_base_price2'] : 0;

				$allowRelativePriceUpdate = $row['Product']['allow_relative_price_update'];
				$mrp = $row['Product']['mrp'];
				$discount = $row['Product']['discount'];
				$sale = $mrp - $discount;

				$priceRelation = '';
				$relativeBasePrice = '';
				$priceRelation2 = '';
				$relativeBasePrice2 = '';
				$active = false;

				if (isset($supplierProducts[$productId])) {
					$supplierProduct = $supplierProducts[$productId]['SupplierProduct'];
					$priceRelation = $supplierProduct['price_relation'];
					$relativeBasePrice = $supplierProduct['relative_base_price'];
					$priceRelation2 = $supplierProduct['price_relation2'];
					$relativeBasePrice2 = $supplierProduct['relative_base_price2'];
					$active = (bool)$supplierProduct['active'];
				}

				?>
					<tr>
						<td class="text-center"><?= $i ?>.</td>
						<td>
							<?= $productName ?><br>
							<span class="small text-muted">
								[MRP=<?= (float)$mrp ?>, Discount=<?= (float)$discount ?>, Sale= <?= $sale ?>]
							</span>
							<input type="hidden" name="data[SupplierProduct][<?= $i ?>][product_id]" value="<?= $productId ?>">
						</td>
						<td>
							<?php
							if ($row['Product']['group_id'] && isset($groupsList[$row['Product']['group_id']])) {
								echo $groupsList[$row['Product']['group_id']];
							}
							?>
						</td>
						<td class="text-center">
							<?php
							echo $this->Form->select('SupplierProduct.' . $i . '.price_relation', ['+' => '+ Plus', '-' => '- Minus', '*'=>'* Multiply by'], [
									'empty' => false,
									'default' => $priceRelation,
									'class' => 'form-select form-select-sm'
							]);
							?>
						</td>
						<td class="text-center">
							<input type="text" value="<?= $relativeBasePrice ?>" name="data[SupplierProduct][<?= $i ?>][relative_base_price]" class="form-control form-control-sm">
						</td>

						<td class="text-center">
							<?php
							echo $this->Form->select('SupplierProduct.' . $i . '.price_relation2', ['+' => '+ Plus', '-' => '- Minus', '*'=>'* Multiply by'], [
									'empty' => false,
									'default' => $priceRelation2,
									'class' => 'form-select form-select-sm'
							]);
							?>
						</td>
						<td class="text-center">
							<input type="text" value="<?= $relativeBasePrice2 ?>" name="data[SupplierProduct][<?= $i ?>][relative_base_price2]" class="form-control form-control-sm">
						</td>


						<td class="text-center">
							<div class="form-check form-switch d-flex justify-content-center">
								<input type="hidden" name="data[SupplierProduct][<?= $i ?>][active]" value="0">
								<input
									name="data[SupplierProduct][<?= $i ?>][active]"
									value="1"
									class="form-check-input"
									type="checkbox"
									role="switch"
									id="flexSwitchCheckDefault"
									<?= $active == 1 ? 'checked' : '' ?>
								>
							</div>
						</td>
					</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		</div>
		<div class="mt-4">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>

		<?= $this->Form->end() ?>

		<?php
		}
		?>
	</article>
</section>
<br><br>
