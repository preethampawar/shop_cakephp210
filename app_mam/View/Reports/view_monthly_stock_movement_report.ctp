<?php 
if($place == 'godown') {
	$place = 'Godown';
}
else {
	$place = 'Shop';
}
echo $this->Form->create();
?>			

<?php echo $this->Html->link('Show Godown Stock Movement Report', '/reports/viewMonthlyStockMovementReport/godown', array('class'=>'small button grey'));?>	
&nbsp;&nbsp;|&nbsp;&nbsp;
<?php echo $this->Html->link('Show Shop Stock Movement Report', '/reports/viewMonthlyStockMovementReport/shop', array('class'=>'small button grey'));?>	

<br><br>

<div id="search" class="corner setBackground" style=" padding:10px 10px 0px 10px;">
	
	<div class="floatLeft" style="width:250px; padding:0px; margin-right:15px;">
		<?php		
		echo $this->Form->input('Report.category_id', array('label'=>'Select Category', 'options'=>$categoryOptions, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>		

	<div style='float:left; margin-right:15px;'>
	<?php	echo $this->Form->input('month', array('type'=>'month', 'empty'=>false, 'label'=>'Select Month', 'default'=>$month));?>
	</div>
	<div style='float:left; margin-right:15px;'>
	<?php	
	$y = date('Y');
	$months = array();
	for($i=$y;$i>($y-50);$i--) {
		$months[$i] = $i;
	}
	echo $this->Form->input('year', array('empty'=>false, 'label'=>'Select Year', 'options'=>$months, 'default'=>$year));
	?>
	</div>
	<div style='float:left; margin-top:8px; '>
	<?php	echo $this->Form->Submit('Generate '.$place.' Stock Report');?>
	</div>
	<div class="clear" style="margin:0px; padding:0px;"></div>
<?php echo $this->Form->end();?>
</div>	

<?php
$categoriesReport = array();
?>
<br>
<b>Legend: </b><br><br>
<span style="background-color:red;">&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span class="debit">Stock in hand</span> 
&nbsp;&nbsp;&nbsp;&nbsp;
<span style="background-color:green;">&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span class="credit">Stock moved out</span>
&nbsp;&nbsp;&nbsp;&nbsp;
<span style="background-color:#888;">&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp;<span class="damaged">Damaged Stock</span><br><br><br>



<h2><?php echo $place;?> Stock Movement Report: <?php echo date('M - Y', strtotime($year.'-'.$month.'-01'));?></h2>

<table>
	<thead>
		<th>Product Name</th>
		<?php
		for($i=1; $i<=$no_of_days; $i++) {
			?>
			<th><?php echo $i;?></th>
			<?php			
			
			if($day) {
				if($day == $i) {
					break;
				}
			}	
		}
		?>	
	</thead>
	<tbody>
		<?php
		// debug($monthlyStockInfo);
		foreach($categories as $categoryID=>$categoryName) {		
		?>
		<tr>
			<td style="vertical-align:middle;"><?php echo $categoryName;?></td>
			<?php
			for($i=1; $i<=$no_of_days; $i++) {
				?>
				<td style="vertical-align:middle; text-align:center;">
					<?php
					$stockIn = $monthlyStockInfo[$i][$categoryID]['stockIn'];
					$stockOut = $monthlyStockInfo[$i][$categoryID]['stockOut'];
					$stockDamaged = $monthlyStockInfo[$i][$categoryID]['stockDamaged'];
					
					if(!empty($stockIn) or !empty($stockOut) or !empty($stockDamaged)) {
					?>
					<span class='debit' style="font-weight:bold;">
						<?php echo ($stockIn) ? $stockIn : '0';?>
					</span> <br>
					<span class='credit' style="font-weight:bold;">
						<?php echo ($stockOut) ? $stockOut : '0';?>
					</span> <br>
					<span class='damaged' style="font-weight:bold;">
						<?php echo ($stockDamaged) ? $stockDamaged : '0';?>
					</span>
					<?php
					}
					else {
						echo '<span style="color:#ccc;">-</span>';
					}
					?>
				</td>
				
				
				<?php				
				if($day) {
					if($day == $i) {
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

<br><br>
<h2><?php echo $place;?> Stock Movement - Balance Report: <?php echo date('M - Y', strtotime($year.'-'.$month.'-01'));?></h2>
<?php
if(!empty($categoryStockMovement)) {	
	?>
	<table style="width:800px;">
		<thead>
			<th>Product Name</th>
			<th style="width:100px;">Opening Stock</th>
			<th style="width:100px;">Stock Added</th>
			<th style="width:110px;">Stock Moved Out</th>
			<th style="width:100px;">Damaged Stock</th>
			<th style="width:100px;">Closing Stock</th>
		</thead>
		<tbody>
			<?php
			foreach($categories as $categoryID=>$categoryName) {			
				$openingStock = $categoryStockMovement[$categoryID]['openingStock'];
				$closingStock = $categoryStockMovement[$categoryID]['closingStock'];
				$stockIn = $categoryStockMovement[$categoryID]['stockIn'];
				$stockOut = $categoryStockMovement[$categoryID]['stockOut'];
				$stockDamaged = $categoryStockMovement[$categoryID]['stockDamaged'];
				?>
				<tr>
					<td style="vertical-align:middle;"><?php echo $categoryName;?></td>					
					<td style="vertical-align:middle; text-align:center;" class='debit'>
						<?php
							if($openingStock or $stockIn or $stockOut or $stockDamaged) {								
								echo ($openingStock) ? $openingStock : '0';
							} else {								
								echo ($openingStock) ? $openingStock : '-';
							}
						?>
					</td>
					<td style="vertical-align:middle; text-align:center;" class='debit'><?php echo ($stockIn) ? $stockIn : '-';?></td>
					<td style="vertical-align:middle; text-align:center;" class='credit'><?php echo ($stockOut) ? $stockOut : '-';?></td>
					<td style="vertical-align:middle; text-align:center;" class='damaged'><?php echo ($stockDamaged) ? $stockDamaged : '-';?></td>
					<td style="vertical-align:middle; text-align:center;" class='debit'>
						<?php 
							if($openingStock or $stockIn or $stockOut or $stockDamaged) {
								echo ($closingStock) ? $closingStock : '0';
							} else {
								echo ($closingStock) ? $closingStock : '-';								
							}
						?>
					</td>
				</tr>
				<?php			
			}	
			?>
		</tbody>
	</table>
	<?php
}
?>

	


