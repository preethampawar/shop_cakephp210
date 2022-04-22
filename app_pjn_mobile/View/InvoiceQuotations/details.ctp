<div class="flex p-5 noprint">
	<?php
	echo $this->Form->button('&laquo; back', ['onclick' => 'history.back()', 'escape' => false, 'class' => 'btn btn-default btn-sm']);
	?>
	<button class="btn btn-purple btn-sm ml-5" onclick="printDiv()">Print</button>
</div>

<div class="mx-auto" id="printableTable">

	<?php
	//debug($quotationInfo);
	if ($quotationInfo) {
		$quotationID = $quotationInfo['InvoiceQuotation']['id'];
		$type = $quotationInfo['InvoiceQuotation']['type'];

		$header = $quotationInfo['InvoiceQuotation']['header'];

		$from_label = $quotationInfo['InvoiceQuotation']['from_label'];
		$from = $quotationInfo['InvoiceQuotation']['from'];
		$from_date_label = $quotationInfo['InvoiceQuotation']['from_date_label'];
		$from_date = $quotationInfo['InvoiceQuotation']['from_date'] ? Date('d-m-Y', strtotime($quotationInfo['InvoiceQuotation']['from_date'])) : null;

		$for_label = $quotationInfo['InvoiceQuotation']['for_label'];
		$for = $quotationInfo['InvoiceQuotation']['for'];
		$for_date_label = $quotationInfo['InvoiceQuotation']['for_date_label'];
		$for_date = $quotationInfo['InvoiceQuotation']['for_date'] ? Date('d-m-Y', strtotime($quotationInfo['InvoiceQuotation']['for_date'])) : null;

		$instructions = $quotationInfo['InvoiceQuotation']['instructions'];

		$footer = $quotationInfo['InvoiceQuotation']['footer'];

		$discount = $quotationInfo['InvoiceQuotation']['discount'];
		$cgst = $quotationInfo['InvoiceQuotation']['cgst'];
		$sgst = $quotationInfo['InvoiceQuotation']['sgst'];
		$igst = $quotationInfo['InvoiceQuotation']['igst'];
		$subtotal = $quotationInfo['InvoiceQuotation']['subtotal'];
		$total_amount = $quotationInfo['InvoiceQuotation']['total_amount'];
		$quotationTitle = (($type == 'invoice') ? 'Invoice No: ' : 'Quotation No: ') . $quotationID;
		$this->set('title_for_layout', $quotationTitle);

		$entityItems = $quotationInfo['EntityItem'];
		?>
		<table class="w-full">
			<?php if (!empty($header)): ?>
				<thead>
				<tr>
					<td class="font-bold p-2">
						<div class="mb-5">
							<?php echo $header; ?>
						</div>
					</td>
				</tr>
				</thead>
			<?php endif; ?>

			<tbody>
			<tr>
				<td>
					<!-- invoice no -->
					<div class="text-left border-b-2 py-4 font-bold">
						<?php
						echo ($type == 'invoice') ? 'Invoice No: ' : 'Quotation No: ';
						echo $quotationID;
						?>
					</div>

					<!-- company 1 -->
					<?php if (!empty($from) || !empty($from_date)): ?>
						<table class="w-full border-b-2 mt-4">
							<tr>
								<td class="align-top">
									<div class="font-bold"><?php echo $from_label; ?></div>
									<?php echo $from; ?>
								</td>
								<?php if (!empty($from_date)) : ?>
									<td class="w-1/4 align-top">
										<div class="font-bold"><?php echo $from_date_label; ?></div>
										<?php echo $from_date; ?>
									</td>
								<?php endif; ?>
							</tr>
						</table>
					<?php endif; ?>

					<!-- company 2 -->
					<?php if (!empty($for) || !empty($for_date)): ?>
						<table class="w-full mt-5">
							<tr>
								<td class="align-top">
									<div class="font-bold"><?php echo $for_label; ?></div>
									<?php echo $for; ?>
								</td>

								<?php if (!empty($for_date)) : ?>
									<td class="w-1/4 align-top">
										<div class="font-bold"><?php echo $for_date_label; ?></div>
										<?php echo $for_date; ?>
									</td>
								<?php endif; ?>
							</tr>
						</table>
					<?php endif; ?>
				</td>
			</tr>

			<!-- items -->
			<?php
			if (!empty($entityItems)) {
				?>
				<tr class="border-b-2">
					<td>
						<div class="small mt-5">
							<table class="table-auto w-full table table-striped table-condensed mb-0 border-t-2">
								<tr class="border-b-2">
									<th class="text-center">No.</th>
									<th class="text-left">Item/Type</th>
									<th class="text-left">HSN/ACS</th>
									<th class="text-center">Qty/Hrs.</th>
									<th class="text-right">Unit Rate (<?php echo 'Rs.'; ?>)</th>
									<th class="text-right lg:w-1/6 md:w-1/4 sm:w-2/6">Amount (<?php echo 'Rs.'; ?>)</th>
								</tr>
								<?php
								$i = 0;
								foreach ($quotationInfo['EntityItem'] as $row) {
									$i++;
									$item = $row['item'];
									$description = $row['description'];
									$quantity = $row['quantity'];
									$unitrate = $row['unitrate'];
									$amount = $row['amount'];
									?>
									<tr>
										<td class="text-center"><?php echo $i; ?>.</td>
										<td class=""><?php echo $item; ?></td>
										<td class="text-left"><?php echo $description; ?></td>
										<td class="text-center"><?php echo $quantity; ?></td>
										<td class="text-right"><?php echo $unitrate; ?></td>
										<td class="text-right"><?php echo $amount; ?></td>
									</tr>
									<?php
								}
								?>
								<tr>
									<td class="text-right" colspan='5'><b>Subtotal (Rs.)</b></td>
									<td class="text-right">
										<div class="font-bold">
											<?php echo $subtotal; ?>
										</div>
									</td>
								</tr>
								<tr>
									<td class="text-right" colspan='5'>Discount (%)</td>
									<td class="text-right lg:w-1/6 md:w-1/4 sm:w-2/6">
										<?php echo ($discount) ? $discount : '0'; ?>
									</td>
								</tr>
								<tr>
									<td class="text-right" colspan='5'>CGST (%)</td>
									<td class="text-right">
										<?php echo ($cgst) ? $cgst : '0'; ?>
									</td>
								</tr>
								<tr>
									<td class="text-right" colspan='5'>SGST (%)</td>
									<td class="text-right">
										<?php echo ($sgst) ? $sgst : '0'; ?>
									</td>
								</tr>
								<tr>
									<td class="text-right" colspan='5'>IGST (%)</td>
									<td class="text-right">
										<?php echo ($igst) ? $igst : '0'; ?>
									</td>
								</tr>
								<tr>
									<td class="text-right" colspan='5'>
										<div class="font-bold">Total (Rs.)</div>
									</td>
									<td class="text-right">
										<div class="font-bold">
											<?php echo $total_amount; ?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<?php
			}
			?>

			<!-- instructions -->
			<?php if (!empty($instructions)): ?>
				<tr class="border-b-2">
					<td>
						<div class="mt-5">
							<?php echo $instructions; ?>
						</div>
					</td>
				</tr>
			<?php endif; ?>

			</tbody>

			<!-- footer -->
			<?php if (!empty($footer)): ?>
				<tfoot>
				<tr>
					<td>
						<div class="mt-5">
							<?php echo $footer; ?>
						</div>
					</td>
				</tr>
				</tfoot>
			<?php endif; ?>

		</table>
		<?php
	}
	//$template = (isset($quotationInfo['Quotation']['template'])) ? $quotationInfo['Quotation']['template'] : 'default';
	//switch($template) {
	//	case 'default':
	//			echo $this->element('Quotations/default_template');
	//			break;
	//	case 'nursery':
	//			echo $this->element('Quotations/nursery_template');
	//			break;
	//	default:
	//			echo $this->element('Quotations/default_template');
	//			break;
	//}
	?>
</div>
<br>
