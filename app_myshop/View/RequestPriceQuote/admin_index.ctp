<section>
	<article>
		<header><h2>Requested Price Quotes</h2></header>

		<div class="pagesContent">
			<?php
			if (!empty($priceQuotes)) {

				?>
				<?php
				// prints X of Y, where X is current page and Y is number of pages
				echo 'Page ' . $this->Paginator->counter();
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';

				// Shows the next and previous links
				echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
				// Shows the page numbers
				echo $this->Paginator->numbers();

				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
				echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
				?>
				<hr>
				<?php
				$i = 0;
				foreach ($priceQuotes as $row) {
					$i++;
					?>
					<div style="margin:10px 0 10px 0;">
						<table style="width:900px;">
							<tr>
								<td style="font-weight:bold; text-decoration:underline;"><?php echo date('dS M Y', strtotime($row['ShoppingCart']['created'])); ?></td>
								<td style="text-align:right;">
									<?php echo $this->Form->postLink('Delete Price Quote', ['controller' => 'RequestPriceQuote', 'action' => 'delete', $row['ShoppingCart']['id']], ['title' => 'Delete price quote', 'confirm' => 'Are you sure you want to delete this price quote?']); ?>
								</td>
							</tr>
							<tr>
								<td style="width:125px;">Customer Name:</td>
								<td><?php echo $row['ShoppingCart']['name']; ?></td>
							</tr>
							<tr>
								<td>Email Address:</td>
								<td><?php echo $row['ShoppingCart']['email']; ?></td>
							</tr>
							<tr>
								<td>Phone No:</td>
								<td><?php echo $row['ShoppingCart']['phone']; ?></td>
							</tr>
							<tr>
								<td>Address:</td>
								<td><?php echo $row['ShoppingCart']['address']; ?></td>
							</tr>
							<tr>
								<td>Message:</td>
								<td><?php echo $row['ShoppingCart']['message']; ?></td>
							</tr>
							<tr>
								<td>Products:</td>
								<td>
									<?php
									if (!empty($row['ShoppingCartProduct'])) {
										?>
										<table class="table" style="width:700px;">
											<thead>
											<tr>
												<th>Category</th>
												<th>Item</th>
												<th>Quantity</th>
												<th>Size</th>
												<th>Age</th>
											</tr>
											</thead>
											<tbody>
											<?php
											foreach ($row['ShoppingCartProduct'] as $product) {
												?>
												<tr>
													<td><?php echo $product['category_name']; ?></td>
													<td><?php echo $product['product_name']; ?></td>
													<td><?php echo $product['quantity']; ?></td>
													<td><?php echo $product['size']; ?></td>
													<td><?php echo $product['age']; ?></td>
												</tr>
												<?php
											}
											?>
											</tbody>
										</table>
										<?php
									} else {
										echo 'No products';
									}
									?>
								</td>
							</tr>
						</table>
						<hr>
					</div>

					<?php
				}

				// prints X of Y, where X is current page and Y is number of pages
				echo 'Page ' . $this->Paginator->counter();
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';

				// Shows the next and previous links
				echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
				// Shows the page numbers
				echo $this->Paginator->numbers();

				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
				echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';

			} else {
				echo "No requests found";
			}
			?>
		</div>
	</article>
</section>

