<section>
	<div class="text-end">
		<a href="/admin/groups/" class="btn btn-outline-warning btn-sm">Cancel</a>
	</div>
	<article>

		<h2>Select Group</h2>
		<?php
		echo $this->Form->select('Group.name', $groupsList, [
					'empty' => '- Show All -',
					'onchange' => 'window.location = "/admin/groups/products/"+this.value',
					'default' => $groupId,
					'class' => 'form-select form-select-sm'
				]);
		?>


		<hr class="mt-4">
		<header><h2><?= $groupsList[$groupId] ?? 'All' ?> - Products</h2></header>
		<?= $this->Form->create(null, []) ?>
		<div class="table-responsive">
			<table class="table table-sm table-striped">
			<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Product Name</th>
				<th>Group</th>
				<th class="text-center">Relation type</th>
				<th class="text-center">Relative Base Price</th>
				<th class="text-center">Allow Price Update</th>
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
				$allowRelativePriceUpdate = $row['Product']['allow_relative_price_update'];
				$mrp = $row['Product']['mrp'];
				$discount = $row['Product']['discount'];
				$sale = $mrp - $discount;
				?>
					<tr>
						<td class="text-center"><?= $i ?>.</td>
						<td>
							<?= $productName ?><br>
							<span class="small text-muted">
								[MRP=<?= (float)$mrp ?>, Discount=<?= (float)$discount ?>, Sale= <?= $sale ?>]
							</span>
							<input type="hidden" name="data[Group][<?= $i ?>][Product][id]" value="<?= $productId ?>">
						</td>
						<td>
							<?php
							echo $this->Form->select('Group.' . $i . '.Product.group_id', $groupsList, [
									'empty' => '- Select Group -',
									'default' => $row['Product']['group_id'],
									'class' => 'form-select form-select-sm'
							]);
							?>
						</td>
						<td class="text-center">
							<?php
							echo $this->Form->select('Group.' . $i . '.Product.relative_price_relation', ['+' => '+ Plus', '-' => '- Minus', '*'=>'* Multiply by'], [
									'empty' => false,
									'default' => $row['Product']['relative_price_relation'],
									'class' => 'form-select form-select-sm'
							]);
							?>
						</td>
						<td class="text-center">
							<input type="text" value="<?= $relativeBasePrice ?>" name="data[Group][<?= $i ?>][Product][relative_base_price]" class="form-control form-control-sm">
						</td>
						<td class="text-center">
							<div class="form-check form-switch d-flex justify-content-center">
								<input type="hidden" name="data[Group][<?= $i ?>][Product][allow_relative_price_update]" value="0">
								<input
									name="data[Group][<?= $i ?>][Product][allow_relative_price_update]"
									value="1"
									class="form-check-input"
									type="checkbox"
									role="switch"
									id="flexSwitchCheckDefault"
									<?= $allowRelativePriceUpdate == 1 ? 'checked' : '' ?>
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

	</article>
</section>
<br><br>
