<p style="text-align:center; font-weight:bold; padding-top:5mm;">QUOTATION</p>
<br/>
<table style="border:1px solid #ccc; width:100%; margin:0px;">
	<tr>
		<td>
			<table class="heading" style="width:100%;">
				<tr>
					<td style="text-align:center;">
						<h1 class="heading"><?php echo $quotationInfo['Quotation']['from_name']; ?></h1>
					</td>
				</tr>
			</table>
			<table style="width:100%;">
				<tr>
					<td style="width:90mm; " valign='top'>
						<pre><?php echo $quotationInfo['Quotation']['from_address']; ?></pre>
					</td>
					<td>&nbsp;</td>
					<td style="width:60mm;">
						<table class="borderTable">
							<tr>
								<td style="width:25mm">Quotation No.</td>
								<td style="width:30mm"><b> <?php echo $quotationInfo['Quotation']['id']; ?></b></td>
							</tr>
							<tr>
								<td>Dated</td>
								<td>
									<b> <?php echo date('d - M - Y', strtotime($quotationInfo['Quotation']['date'])); ?></b>
								</td>
							</tr>
							<tr>
								<td>Validity</td>
								<td>
									<b> <?php echo date('d - M - Y', strtotime($quotationInfo['Quotation']['validity'])); ?></b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<b>Quotation For: </b> <br/>
						<?php echo $quotationInfo['Quotation']['to_name']; ?><br/>
						<pre><?php echo $quotationInfo['Quotation']['to_address']; ?></pre>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br/>

			<div style="padding:2mm;">
				<table style="width:100%;" class='borderTable'>
					<tr>
						<th style='width:15pt;'>No.</th>
						<th style="width:90pt; text-align:left;">Item/Type</th>
						<th style=" text-align:left;">Description</th>
						<th style="width:40pt;">Size</th>
						<th style="width:50pt;">Age</th>
						<th style="width:25pt;">Qty/Hrs</th>
						<th style="width:15mm;">Rate</th>
						<th style="width:22mm;">Amount</th>
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
							<td style="text-align:center;"><?php echo $row['size']; ?></td>
							<td style="text-align:center;"><?php echo $row['age']; ?></td>
							<td style="text-align:center;"><?php echo $row['quantity']; ?></td>
							<td style="text-align:right;"><?php echo $row['unitrate']; ?></td>
							<td style="text-align:right;"><?php echo $row['amount']; ?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td style="text-align:right; " colspan='7'><b>Subtotal</b></td>
						<td style="text-align:right; ">
							<b>
								<?php echo $quotationInfo['Quotation']['subtotal']; ?>
							</b>
						</td>
					</tr>
				</table>

				<table style="width:100%;" class="smallPadding">
					<tr>
						<td style="text-align:right;">Discount:</td>
						<td style="width:23mm; text-align:right;">
							<?php echo ($quotationInfo['Quotation']['discount']) ? $quotationInfo['Quotation']['discount'] : '0'; ?>
							%
						</td>
					</tr>
					<tr>
						<td style="text-align:right;">Tax rate:</td>
						<td style="text-align:right;">
							<?php echo ($quotationInfo['Quotation']['tax_rate']) ? $quotationInfo['Quotation']['tax_rate'] : '0'; ?>
							%
						</td>
					</tr>
					<tr>
						<td style="text-align:right;"><b>Total:</b></td>
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
