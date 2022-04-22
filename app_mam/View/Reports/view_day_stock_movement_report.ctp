<?php
	if($place == 'godown') {
		$placeTitle = 'Godown';
	}
	else {
		$placeTitle = 'Shop';
	}
?>

<?php echo $this->Html->link('Godown Stock Movement Report', '/reports/viewDayStockMovementReport/godown', array('class'=>'small button grey'));?>	
&nbsp;&nbsp;|&nbsp;&nbsp;
<?php echo $this->Html->link('Shop Stock Movement Report', '/reports/viewDayStockMovementReport/shop', array('class'=>'small button grey'));?>	
<br><br>

<div id="search" class="corner setBackground" style=" padding:10px 10px 10px 10px;">
<?php echo $this->Form->create();?>	
	<div class="floatLeft" style="width:250px; padding:0px; margin-right:15px;">
		<?php		
		echo $this->Form->input('Report.category_id', array('label'=>'Select Category', 'options'=>$categoryOptions, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>		
		
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:15px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#startdatepicker').focus()"));
		echo $this->Form->input('Report.startdate', array('label'=>'Select Date', 'id'=>'startdatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	
	<div style='float:left; margin-top:6px; '>
	<?php	echo $this->Form->Submit('Generate '.$placeTitle.' Stock Movement Report');?>
	</div>
	<div class="clear" style="margin:0px; padding:0px;"></div>
<?php echo $this->Form->end();?>
</div>	
<script type="text/javascript">
$(function() {
	// start date picker
	$( "#startdatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#startdatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#startdatepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#startdatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
	$( "#startdatepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Report']['startdate'])) {
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo $this->data['Report']['startdate'];?>" );
	<?php
	}
	else{
	?>
	$( "#startdatepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>	
});

</script>




<br><br>
<?php
if($place == 'godown') {
?>
<div id="GodownReport">
	<h3>Godown Stock Movement Report: <?php echo date('d M, Y', strtotime($date));?></h3>
	<table style="width:950px; font-size:" cellpadding='0' cellspacing='0' class="stockTable">
		<tr>
			<th width='30'>Sl.No.</th>
			<th>Product Name</th>
			<th width='100'>Opening Stock</th>
			<th width='100'>Stock Added</th>			
			<th width='100'>Breakage Stock</th>
			<th width='140'>Stock Moved To Shop</th>
			<th width='100'>Closing Stock</th>
			
		</tr>
	<?php
	$i=0;
	foreach($categories as $categoryID=>$categoryName) {
		$i++;
		
		$openingStock = $godownStockMovementInfo[$categoryID]['openingStock']; 
		$closingStock = $godownStockMovementInfo[$categoryID]['closingStock']; 
		$damagedStock = $godownStockMovementInfo[$categoryID]['stockDamaged']; 
		$addedStock = $godownStockMovementInfo[$categoryID]['stockIn']; 
		$saleStock = $godownStockMovementInfo[$categoryID]['stockOut']; 		
	?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $categoryName;?></td>		
			<td style="font-weight:bold; text-align:center;"><?php echo ($openingStock) ? (($openingStock > 0) ? '<span class="debit">'.abs($openingStock).'</span>' : '<span class="credit">'.abs($openingStock).'</span>') : '-';?></td>
		
			<td class='debit' style="text-align:center;"><?php echo ($addedStock) ? $addedStock : '-';?></td>
			<td class='damaged' style="text-align:center;"><?php echo ($damagedStock) ? $damagedStock : '-';?></td>
			<td class='credit' style="text-align:center;"><?php echo ($saleStock) ? $saleStock : '-';?></td>
			<td style="font-weight:bold; text-align:center;"><?php echo ($closingStock) ? (($closingStock > 0) ? '<span class="debit">'.abs($closingStock).'</span>' : '<span class="credit">'.abs($closingStock).'</span>') : '-';?></td>
		</tr>		
	<?php	
	}	
	?>
	</table>

</div>	
<?php
}
?>

<?php
if($place=='shop') {
?>
<div id="ShopReport">
	<h3>Shop Stock Movement Report: <?php echo date('d M, Y', strtotime($date));?></h3>
	<table style="width:950px; font-size:" cellpadding='0' cellspacing='0' class="stockTable">
		<tr>
			<th width='30'>Sl.No.</th>
			<th>Product Name</th>
			<th width='100'>Opening Stock</th>
			<th width='100'>Stock Added</th>			
			<th width='100'>Breakage Stock</th>
			<th width='140'>Stock Sale</th>
			<th width='100'>Closing Stock</th>
			
		</tr>
	<?php
	$i=0;
	foreach($categories as $categoryID=>$categoryName) {
		$i++;
		
		$openingStock = $shopStockMovementInfo[$categoryID]['openingStock']; 
		$closingStock = $shopStockMovementInfo[$categoryID]['closingStock']; 
		$damagedStock = $shopStockMovementInfo[$categoryID]['stockDamaged']; 
		$addedStock = $shopStockMovementInfo[$categoryID]['stockIn']; 
		$saleStock = $shopStockMovementInfo[$categoryID]['stockOut']; 		
	?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $categoryName;?></td>		
			<td style="font-weight:bold; text-align:center;"><?php echo ($openingStock) ? (($openingStock > 0) ? '<span class="debit">'.abs($openingStock).'</span>' : '<span class="credit">'.abs($openingStock).'</span>') : '-';?></td>
		
			<td class='debit' style="text-align:center;"><?php echo ($addedStock) ? $addedStock : '-';?></td>
			<td class='damaged' style="text-align:center;"><?php echo ($damagedStock) ? $damagedStock : '-';?></td>
			<td class='credit' style="text-align:center;"><?php echo ($saleStock) ? $saleStock : '-';?></td>
			<td style="font-weight:bold; text-align:center;"><?php echo ($closingStock) ? (($closingStock > 0) ? '<span class="debit">'.abs($closingStock).'</span>' : '<span class="credit">'.abs($closingStock).'</span>') : '-';?></td>
		</tr>		
	<?php	
	}	
	?>
	</table>

</div>	

<?php
}
?>
