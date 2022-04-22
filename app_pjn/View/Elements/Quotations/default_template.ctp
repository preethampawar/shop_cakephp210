<p style="text-align:center; font-weight:bold; padding-top:5mm;"><?php echo ($downloadType == 'quotation') ? 'QUOTATION' : 'INVOICE'; ?></p>
<br/>
<table style="border:1px solid #ccc; width:100%; margin:0px;">
	<tr>
		<td>
			<table class="heading" style="width:100%;">
				<tr>
					<td style="text-align:center;">
						<h1 class="heading"><?php echo $quotationInfo['InvoiceQuotation']['from_name']; ?></h1>
					</td>
				</tr>
			</table>
			<table style="width:100%;">
				<tr>
					<td style="width:90mm; " valign='top'>
						<pre><?php echo $quotationInfo['InvoiceQuotation']['from_address']; ?></pre>
					</td>
					<td>&nbsp;</td>
					<td style="width:60mm;">
						<table class="borderTable">
							<tr>
								<td style="width:25mm"><?php echo ($downloadType == 'quotation') ? 'Quotation No.' : 'Invoice No.'; ?></td>
								<td style="width:30mm"><b> <?php echo $quotationInfo['InvoiceQuotation']['id']; ?></b>
								</td>
							</tr>
							<tr>
								<td>Date</td>
								<td>
									<b> <?php echo date('d - M - Y', strtotime($quotationInfo['InvoiceQuotation']['date'])); ?></b>
								</td>
							</tr>
							<tr>
								<td><?php echo ($downloadType == 'quotation') ? 'Valid till' : 'Due date'; ?></td>
								<td>
									<b> <?php echo date('d - M - Y', strtotime($quotationInfo['InvoiceQuotation']['validity'])); ?></b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<b><?php echo ($downloadType == 'quotation') ? 'Quotation' : 'Bill'; ?> to: </b> <br/>
						<?php echo $quotationInfo['InvoiceQuotation']['to_name']; ?><br/>
						<pre><?php echo $quotationInfo['InvoiceQuotation']['to_address']; ?></pre>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br/>

			<div style="padding:2mm;">
				<table style="width:100%;" class='borderTable'>
					<tr>
						<th style='width:20pt;'>No.</th>
						<th style="width:110pt; text-align:left;">Item/Type</th>
						<th style=" text-align:left;">Description</th>
						<th style="width:40pt;">Qty/Hrs.</th>
						<th style="width:30mm;">Unit Rate (<?php echo 'Rs.'; ?>)</th>
						<th style="width:30mm;">Amount (<?php echo 'Rs.'; ?>)</th>
					</tr>
					<?php
					$i = 0;
					foreach ($quotationInfo['EntityItem'] as $row) {
						$i++;
						?>
						<tr>
							<td style="text-align:right;"><?php echo $i; ?>.</td>
							<td><?php echo $row['item']; ?></td>
							<td><?php echo $row['description']; ?></td>
							<td style="text-align:center;"><?php echo $row['quantity']; ?></td>
							<td style="text-align:right;"><?php echo $row['unitrate']; ?></td>
							<td style="text-align:right;"><?php echo $row['amount']; ?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td style="text-align:right; " colspan='5'><b>Subtotal (Rs.)</b></td>
						<td style="text-align:right; ">
							<b>
								<?php echo $quotationInfo['InvoiceQuotation']['subtotal']; ?>
							</b>
						</td>
					</tr>
				</table>

				<table style="width:100%;" class="smallPadding">
					<tr>
						<td style="text-align:right;">Discount (%):</td>
						<td style="width:25mm; text-align:right;">
							<?php echo ($quotationInfo['InvoiceQuotation']['discount']) ? $quotationInfo['InvoiceQuotation']['discount'] : '0'; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">CGST (%):</td>
						<td style="text-align:right;">
							<?php echo ($quotationInfo['Quotation']['cgst']) ? $quotationInfo['Quotation']['cgst'] : '0'; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">SGST (%):</td>
						<td style="text-align:right;">
							<?php echo ($quotationInfo['Quotation']['sgst']) ? $quotationInfo['Quotation']['sgst'] : '0'; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;"><b>Total (Rs.):</b></td>
						<td style="text-align:right;">
							<b>
								<?php echo $this->Session->read('Company.currency') . ' '; ?>
								<?php echo $quotationInfo['Quotation']['total_amount']; ?>
							</b>
						</td>
					</tr>
				</table>
				<b>Comments or Special instructions:</b> <br/>
				<pre><?php echo ($quotationInfo['Quotation']['comments']) ? $quotationInfo['Quotation']['comments'] : ' - None'; ?>	 <br/><br/></pre>
			</div>
		</td>
	</tr>
</table>
<br/>
<table style='width:100%'>
	<tr>
		<td style="text-align:center; font-weight:bold;">THANK YOU FOR YOUR BUSINESS!</td>
	</tr>
</table>
