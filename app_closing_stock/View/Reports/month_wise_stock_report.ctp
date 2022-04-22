<?php
if ($viewType != 'download') {
	?>

	<?php
	$title_for_layout = 'Monthly Stock Report';
	$this->set('title_for_layout', $title_for_layout);
	?>
	<h1><?php echo $title_for_layout; ?></h1>

	<?php if ($showForm) { ?>

		<?php $this->start('reports_menu'); ?>
		<?php echo $this->element('reports_menu'); ?>
		<?php $this->end(); ?>

		<?php echo $this->Form->create('Report', ['target' => '_blank']); ?>
		<div id="paramsDiv">
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('product_id', ['empty' => 'All', 'label' => 'Select Product', 'type' => 'select', 'options' => $productsList, 'escape' => false]); ?>
			</div>
			<div style="float:left; clear:none;">
				<?php echo $this->Form->input('month', ['label' => 'Month', 'type' => 'month', 'empty' => false, 'default' => $month]); ?>
			</div>
			<div style="float:left; clear:none;">
				<?php
				$options = [date('Y') => date('Y'), (date('Y') - 1) => (date('Y') - 1), (date('Y') - 2) => (date('Y') - 2), (date('Y') - 3) => (date('Y') - 3)];
				echo $this->Form->input('year', ['label' => 'Year', 'type' => 'select', 'empty' => false, 'options' => $options, 'default' => $year]);
				?>
			</div>
			<div style="float:left; clear:both;">
				<?php
				$options = ['print' => 'Print View', 'download' => 'Download'];
				echo $this->Form->input('view_type', ['empty' => 'Normal View', 'label' => 'Download/Select View', 'type' => 'select', 'options' => $options, 'escape' => false, 'default' => 'download']);
				?>
			</div>
			<div style="float:left; clear:none; padding-top:15px;">
				<?php echo $this->Form->submit('Get Report', ['id' => 'SubmitForm', 'title' => '', 'type' => 'submit', 'onclick' => 'return submitButtonMsg()']); ?>
			</div>
			<div style="clear:both;"></div>
		</div>
		<?php echo $this->Form->end(); ?>
	<?php } ?>

	<?php
	if (!empty($result)) {
		$startDate = $year . '-' . $month . '-01';
		$no_of_days = date('t', strtotime($startDate));

		if (date('m') == $month) {
			$no_of_days = date('d');
		}
		?>
		<h3>Product Stock Report: <?php echo date('M Y', strtotime($startDate)); ?></h3>
		<p>Legend - <span class="cBlue">OS: Opening Stock</span>, <span class="cRed">SA: Stock Added</span>, <span
				class="cGreen">SS: Stock Sale</span>, <span class="cRed">BS: Breakage Stock</span>, <span class="cBlue">CS: Closing Stock</span>
		</p>
		<?php echo $this->Form->input('showallproducts', ['type' => 'checkbox', 'checked' => true, 'label' => 'Show all products(with/without stock)', 'title' => 'Uncheck to show products having stock', 'onclick' => '$(".HideRow").toggle()']); ?>
		<table class='table table-striped table-condensed search-table' style="color:grey">
			<thead>
			<tr>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php
				for ($i = 1; $i <= $no_of_days; $i++) {
					?>
					<th><?php echo $i; ?></th>
					<?php
				}
				?>

			</tr>
			</thead>
			<tbody>
			<?php
			if (!empty($selectedProductID)) {
				$tmp[$selectedProductID] = $productsList[$selectedProductID];
				$productsList = $tmp;
			}
			foreach ($productsList as $productID => $productName) {
				$hasStock = false;
				?>
				<tr id="<?php echo 'RowClass' . $productID; ?>" class="HideRow">
					<td><?php echo $productName; ?></td>
					<td><span class="cBlue">OS</span><br><span class="cRed">SA</span><br><span
							class="cGreen">SS</span><br><span class="cRed">BS</span><br><span class="cBlue">CS</span>
					</td>
					<?php
					for ($i = 1; $i <= $no_of_days; $i++) {
						foreach ($result[$i] as $row) {
							if ($row['p']['id'] == $productID) {
								$openingStock = $row[0]['opening_stock'];
								$stockAdded = $row[0]['stock_added'];
								$stockSale = $row[0]['stock_sale'];
								$closingStock = $row[0]['closing_stock'];
								$breakageStock = $row[0]['stock_breakage'];
								?>
								<?php
								if ($this->data['Report']['view_type'] == '') {
									$osClass = ($openingStock) ? 'cBlue bold italic smallText' : null;
									$saClass = ($stockAdded) ? 'cRed bold' : null;
									$ssClass = ($stockSale) ? 'cGreen bold' : null;
									$bsClass = ($breakageStock) ? 'cRed bold italic smallText' : null;
									$csClass = ($closingStock) ? 'cBlue bold italic smallText' : null;

								if (!$hasStock AND ($openingStock OR $stockAdded OR $closingStock OR $stockSale)) {
									$hasStock = true;
									?>
									<script>
										$("#<?php echo 'RowClass' . $productID;?>").removeClass("HideRow");
										$("#<?php echo 'RowClass' . $productID;?>").addClass("ShowRow");
									</script>
								<?php
								}
								?>
									<td onmouseover="$('#infoDiv<?php echo $productID . '-' . $i; ?>').toggle()"
										onmouseout="$('#infoDiv<?php echo $productID . '-' . $i; ?>').toggle()">
										<span class="<?php echo $osClass; ?>"><?php echo $openingStock; ?></span><br>
										<span class="<?php echo $saClass; ?>"><?php echo $stockAdded; ?></span><br>
										<span class="<?php echo $ssClass; ?>"><?php echo $stockSale; ?></span><br>
										<span class="<?php echo $bsClass; ?>"><?php echo $breakageStock; ?></span><br>
										<span class="<?php echo $csClass; ?>"><?php echo $closingStock; ?></span><br>
										<div
											style="position:absolute; background-color:#eee; display:none; padding:5px; border:1px solid #888; font-size:11px;"
											id="infoDiv<?php echo $productID . '-' . $i; ?>">
											<b><?php echo $productName; ?></b><br>
											<b><?php echo $i; ?><?php echo date('M Y', strtotime($startDate)); ?></b><br>
											Opening Stock = <?php echo $openingStock; ?><br>
											Stock Added = <?php echo $stockAdded; ?><br>
											Stock Sale = <?php echo $stockSale; ?><br>
											Breakage Stock = <?php echo $breakageStock; ?><br>
											Closing Stock = <?php echo $closingStock; ?>

										</div>

									</td>
								<?php
								}
								else {
								?>
									<td>
										<span><?php echo $openingStock; ?></span><br>
										<span><?php echo $stockAdded; ?></span><br>
										<span><?php echo $stockSale; ?></span><br>
										<span><?php echo $breakageStock; ?></span><br>
										<span><?php echo $closingStock; ?></span>
									</td>
									<?php
								}
								break;
							}
						}
					}
					?>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	}
	?>
	<?php
} else { // download csv view

	// generate report in csv format
	$startDate = $year . '-' . $month . '-01';
	$no_of_days = date('t', strtotime($startDate));

	if (date('m') == $month) {
		$no_of_days = date('d');
	}

	$csv = 'Product Stock Report: ' . date('M Y', strtotime($startDate)) . "\r\n";
	$csv .= 'Legend: OS = Opening Stock | SA = Stock Added | SS = Stock Sale | BS = Breakage Stock | CS = Closing Stock' . "\r\n\r\n";

	if (!empty($result)) {
		$header[] = '';
		$header[] = '';
		for ($i = 1; $i <= $no_of_days; $i++) {
			$header[] = $i . ' - ' . date('M', strtotime($startDate));
		}
		$csv .= implode($header, ',') . "\r\n";

		if (!empty($selectedProductID)) {
			$tmp[$selectedProductID] = $productsList[$selectedProductID];
			$productsList = $tmp;
		}
		foreach ($productsList as $productID => $productName) {
			// opening stock
			$tempOS = [];
			$tempOS[] = $productName;
			$tempOS[] = '-- OS --';
			// stock added
			$tempSA = [];
			$tempSA[] = $productName;
			$tempSA[] = '-- SA --';
			// stock sale
			$tempSS = [];
			$tempSS[] = $productName;
			$tempSS[] = '-- SS --';
			// breakage stock
			$tempBS = [];
			$tempBS[] = $productName;
			$tempBS[] = '-- BS --';
			// closing stock
			$tempCS = [];
			$tempCS[] = $productName;
			$tempCS[] = '-- CS --';


			for ($i = 1; $i <= $no_of_days; $i++) {

				foreach ($result[$i] as $row) {
					if ($row['p']['id'] == $productID) {
						$openingStock = $row[0]['opening_stock'];
						$stockAdded = $row[0]['stock_added'];
						$stockSale = $row[0]['stock_sale'];
						$breakageStock = $row[0]['stock_breakage'];
						$closingStock = $row[0]['closing_stock'];
						$tempOS[] = $openingStock;
						$tempSA[] = $stockAdded;
						$tempSS[] = $stockSale;
						$tempBS[] = $breakageStock;
						$tempCS[] = $closingStock;


						break;
					}
				}
			}
			// opening stock
			$tempOS[] = '-- OS --';
			// stock added
			$tempSA[] = '-- SA --';
			// stock sale
			$tempSS[] = '-- SS --';
			// breakage stock
			$tempBS[] = '-- BS --';
			// closing stock
			$tempCS[] = '-- CS --';

			$csv .= implode($tempOS, ',') . "\r\n";
			$csv .= implode($tempSA, ',') . "\r\n";
			$csv .= implode($tempSS, ',') . "\r\n";
			$csv .= implode($tempBS, ',') . "\r\n";
			$csv .= implode($tempCS, ',') . "\r\n";
			$csv .= "\r\n";
		}
	}
	echo $csv;
}
?>
