<?php $this->start('invoices_report_menu');?>
<?php echo $this->element('invoices_menu');?>
<?php echo $this->element('sales_purchases_report_menu');?>
<?php $this->end();?>

<h1>
	Invoice: <?php echo $invoiceInfo['Invoice']['name'];?>	(<?php echo $this->Html->link('Edit', array('controller'=>'invoices', 'action'=>'edit', $invoiceInfo['Invoice']['id']), array('title'=>'Edit '.$invoiceInfo['Invoice']['name']));	?>)
</h1>

<table class="table table-striped" style="width:100%">
	<tbody>
		<tr class="bold">
			<td width="80">Invoice No:</td>
			<td width="120"><?php echo $invoiceInfo['Invoice']['name'];?></td>		
			<td width="110">Invoice Date:</td>
			<td width="120"><?php echo date('d-m-Y', strtotime($invoiceInfo['Invoice']['invoice_date']));?></td>			
			<td width="110">DD Amount:</td>
			<td width="120"><?php echo $invoiceInfo['Invoice']['dd_amount'];?></td>
		</tr>
		<tr>
			<td>Invoice Value:</td>
			<td><?php echo $invoiceInfo['Invoice']['invoice_value'];?></td>			
			<td>MRP Rounding Up:</td>
			<td><?php echo $invoiceInfo['Invoice']['mrp_rounding_off'];?></td>						
			<td>Net Invoice Value:</td>
			<td><?php echo $invoiceInfo['Invoice']['invoice_value']+$invoiceInfo['Invoice']['mrp_rounding_off'];?></td>
		</tr>
        <tr>
            <td>Retail Shop Excise Turnover Tax:</td>
            <td><?php echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax'];?></td>
            <td>Special Excise Cess:</td>
            <td><?php echo $invoiceInfo['Invoice']['special_excise_cess'];?></td>
            <td></td>
            <td></td>
        </tr>
		<tr>			
			<td>TCS Value:</td>
			<td><?php echo $invoiceInfo['Invoice']['tcs_value'];?></td>			
			<td>Prev Credit:</td>
			<td><?php echo $invoiceInfo['Invoice']['prev_credit'];?></td>		
			<td>Credit Balance:</td>
			<td><?php echo $invoiceInfo['Invoice']['credit_balance'];?></td>	
		</tr>		
	</tbody>
</table>

<br>
<h2>Products list</h2>
	<?php echo $this->Html->link('Add/Remove Products', array('controller'=>'invoices', 'action'=>'selectInvoice', $invoiceInfo['Invoice']['id']), array('title'=>'Add/Remove products in this invoice - '.$invoiceInfo['Invoice']['name']));?>
<?php 
if($invoiceProducts) { 
?>
<table class='table' style="width:100%">
	<thead>
		<tr>
			<th style="width:10px;">S.No</th>
			<th style="width:100px;">Category Name</th>
			<?php echo $this->Session->read('Store.show_brands_in_products') ? '<th style="width:100px;">Brand</th>' : '';?>
			
			<th>Product Name</th>
			<th style="width:70px;">No. of Boxes</th>
			<th style="width:70px;">Unit Box Price</th>
			<th style="width:80px;">Total Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i=0;
		$totalBoxes = 0;
		$totalAmount = 0;		
		$totalSpecialMargin = 0;
		$totalMrpRoundingUp = $invoiceInfo['Invoice']['mrp_rounding_off'];
		$totalNoOfUnits = 0;
		$tax = $invoiceInfo['Invoice']['tax'];
		foreach($invoiceProducts as $row) {
			$i++;
			$totalBoxes+=$row['Purchase']['box_qty'];
			$totalAmount+=$row['Purchase']['total_amount'];			
			$totalSpecialMargin+=$row['Purchase']['total_special_margin'];			
			$totalUnits = $row['Purchase']['total_units'];
			$noOfBoxes = floor($row['Purchase']['total_units']/$row['Purchase']['units_in_box']);
			$unitInBox = $row['Purchase']['units_in_box'];
			$noOfUnits = ($totalUnits)-($noOfBoxes*$unitInBox);
			$totalNoOfUnits+=$noOfUnits;
			
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $row['Purchase']['category_name'];?></td>
			<?php
			if($this->Session->read('Store.show_brands_in_products')) {
			?>
				<td><?php echo isset($row['Product']['Brand']['name']) ? $row['Product']['Brand']['name'] : '';?></td>
			<?php
			}
			?>
			<td><?php echo $row['Purchase']['product_name'];?></td>
			<td style="text-align:center;"><?php echo $row['Purchase']['box_qty'];
				if($noOfUnits){
					echo "&nbsp;($noOfUnits)";
				}
			?></td>
			<td><?php echo $row['Purchase']['box_buying_price'];?></td>
			<td style="text-align:right;"><?php echo $row['Purchase']['total_amount'];?></td>
			</td>
		</tr>
		<?php
		}
		?>
		<tfoot>
			<tr>
				<td colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 4 : 3;?>'></td>
				<td style="text-align:center;"><?php echo $totalBoxes;
					if($totalNoOfUnits){
						echo "&nbsp;($totalNoOfUnits)";
					}
				?> Boxes</td>
				
				<td>&nbsp;</td>
				<td style="text-align:right;"><?php echo number_format($totalAmount, '2', '.', '');?></td>
			</tr>
			<tr>					
				<td style="text-align:right; color:red;" colspan='6'>&nbsp;</td>
			</tr>
			
			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>
					Invoice Value: <br>
					MRP Rounding Up: <br>
					Net Invoice Value: <br>
				</td>
				<td style="text-align:right;">
					<?php echo number_format($totalAmount, '2', '.', '');?> <br>
					<?php echo number_format($totalMrpRoundingUp, '2', '.', '');?> <br>
					<?php echo $invoiceInfo['Invoice']['invoice_value']+$invoiceInfo['Invoice']['special_margin']+$totalMrpRoundingUp;?> <br>
				</td>
			</tr>	
			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 7 : 6;?>'>&nbsp;</td>
			</tr>
			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>e-challan / DD Amount:</td>
				<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['dd_amount'];?></td>				
			</tr>	
			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Previous Credit:</td>
				<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['prev_credit'];?></td>				
			</tr>	
			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Sub Total:</td>
				<td style="text-align:right;"><?php echo number_format($invoiceInfo['Invoice']['dd_amount']+$invoiceInfo['Invoice']['prev_credit'], '2', '.', '');?></td>				
			</tr>	
			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>(-) Less this Invoice Value:</td>
				<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['invoice_value']+$invoiceInfo['Invoice']['special_margin'];?></td>				
			</tr>

            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Retail Shop Excise Turnover Tax:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['retail_shop_excise_turnover_tax'];?></td>
            </tr>
            <tr>
                <td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Special Excise Cess:</td>
                <td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['special_excise_cess'];?></td>
            </tr>


			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>TCS:</td>
				<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['tcs_value'];?></td>				
			</tr>
			<tr>					
				<td style="text-align:right;" colspan='<?php echo $this->Session->read('Store.show_brands_in_products') ? 6 : 5;?>'>Retailer Credit Balance:</td>
				<td style="text-align:right;"><?php echo $invoiceInfo['Invoice']['credit_balance'];?></td>				
			</tr>
		</tfoot>
		
	</tbody>
</table>

<?php } else { ?>
<p>No products found in Invoice "<?php echo $invoiceInfo['Invoice']['name'];?>".</p>
<?php } ?>
