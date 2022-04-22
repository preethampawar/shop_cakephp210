<div id="search" class="corner setBackground" style=" padding:10px 10px 10px 10px;">
<?php echo $this->Form->create();?>
	<?php
	if($this->Session->read('UserCompany.user_level') != '2' and (1==0)) {
	?>
	<div class="floatLeft" style="width:200px; padding:0px; margin-right:10px;">		
		<?php
		echo $this->Form->input('Report.user_id', array('label'=>'Select User', 'div'=>true, 'options'=>$users, 'escape'=>false, 'empty'=>' - All - ', 'required'=>false));
		?>
	</div>
	<?php
	}
	?>		
	
	<div class="floatLeft" style="width:250px; padding:0px; margin-right:15px;">
		<?php		
		echo $this->Form->input('Report.category_id', array('label'=>'Select Category/Product', 'options'=>$categoryOptions, 'escape'=>false, 'empty'=>' - All -', 'required'=>false));
		?>
	</div>		
		
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:15px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#startdatepicker').focus()"));
		echo $this->Form->input('Report.startdate', array('label'=>'From Date', 'id'=>'startdatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	<div class="floatLeft" style="width:140px; padding:0px; margin-right:15px;">
		<?php
		$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#enddatepicker').focus()"));
		echo $this->Form->input('Report.enddate', array('label'=>'To Date', 'id'=>'enddatepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<div class="floatLeft" style="position:absolute;"><input type="text" id="alternate2" style="border:0px solid #fff; color:#FF0000; background-color:transparent;" disabled="disabled"></div>', 'readonly'=>true, 'placeholder'=>'Select Date', 'style'=>'width:100px;'));
		?>
	</div>
	
	
	<div style='float:left; margin-top:6px; '>
	<?php	echo $this->Form->Submit('Generate Stock Report');?>
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

	// end date picker
	$( "#enddatepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#enddatepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#enddatepicker" ).datepicker( "option", "altField", "#alternate2");
	$( "#enddatepicker" ).datepicker( "option", "altFormat", "d M, yy");	
	$( "#enddatepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Report']['enddate'])) {
	?>
	$( "#enddatepicker" ).attr( "value", "<?php echo $this->data['Report']['enddate'];?>" );
	<?php
	}
	else{
	?>
	$( "#enddatepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>	
});

</script>



<?php
$categoriesReport = array();
?>
<br><br>
<h2>Stock Balance Report: From <?php echo date('d M, Y', strtotime($startDate));?> - to - <?php echo date('d M, Y', strtotime($endDate));?></h2>
<?php
$k=0;
$temp = array();

foreach($monthResults as $row) {		
	$temp[$row['Inventory']['category_id']][$k] = $row['Inventory'];
	$temp[$row['Inventory']['category_id']][$k]['quantity'] = $row[0]['Quantity'];
	$k++;
}

$tmp = array();

foreach($temp as $category_id=>$row2) {
	$x['out_qty'] = 0;
	$x['in_qty'] = 0;
	$x['damaged_qty'] = 0;
	foreach($row2 as $row3) {
		if($row3['type']=='out') {
			$x['out_qty']+=$row3['quantity'];
		}
		if($row3['type']=='in') {
			$x['in_qty']+=$row3['quantity'];
		}
		if($row3['type']=='damaged') {
			$x['damaged_qty']+=$row3['quantity'];
		}	
	}
	$x['bal_qty'] = $x['in_qty']-$x['out_qty']-$x['damaged_qty'];
	$tmp[$category_id] = $x;	
}

	?>
	<table style="width:1000px;" cellpadding='0' cellspacing='0' class="stockTable">
		<tr>
			<th width='30'>Sl.No.</th>
			<th>Product</th>
			<th width='60'>Opening Stock</th>
			<th width='60'>Stock Added</th>			
			<th width='60'>Breakage Stock</th>
			<th width='60'>Closing Stock</th>
			<th width='80'>Stock Sale</th>
			<th width='80'>Unit Rate</th>
			<th width='80'>Sale Value</th>
			<th width='80'>Stock Value</th>
			
		</tr>
	<?php
	$i=0;
	$categorySalePrice = array();
	$categoryPurchasePrice = array();
	if(!empty($categoryDetails)) {
		foreach($categoryDetails as $row) {
			$categorySalePrice[$row['Category']['id']] = $row['Category']['selling_price'];
			$categoryPurchasePrice[$row['Category']['id']] = ($row['Category']['qty_per_case'] > 0) ? ($row['Category']['cost_price']/$row['Category']['qty_per_case']) : 0;			
		}
	}
	
	$totalSaleValue = 0;
	$totalStockValue = 0;
	foreach($categories as $categoryID=>$categoryName) {
		$i++;
		
		$in_stock = null;	
		$out_stock = null;	
		$damaged_stock = null;	
		$bal_stock = null;	
		if(isset($tmp[$categoryID])) {
			$stockInfo = $tmp[$categoryID];
			$in_stock = $stockInfo['in_qty'];	
			$out_stock = ($stockInfo['out_qty'] > 0) ? $stockInfo['out_qty'] : 0;	
			$damaged_stock = $stockInfo['damaged_qty'];	
			$bal_stock = $stockInfo['bal_qty'];	
		}
		
		$in_qty = 0;
		$out_qty = 0;
		$damaged_qty = 0;
		if(isset($prevStock[$categoryID]['in_stock'])) {
			$in_qty = $prevStock[$categoryID]['in_stock'];
		}
		if(isset($prevStock[$categoryID]['out_stock'])) {
			$out_qty = $prevStock[$categoryID]['out_stock'];
		}
		if(isset($prevStock[$categoryID]['damaged_stock'])) {
			$damaged_qty = $prevStock[$categoryID]['damaged_stock'];
		}
		$prev_bal_qty = $in_qty-$out_qty-$damaged_qty;
		$total_bal_stock = $prev_bal_qty+$bal_stock;
		$unitSaleRate = $categorySalePrice[$categoryID];
		$stockPrice = $categoryPurchasePrice[$categoryID]*$total_bal_stock;
		$salePrice = $categorySalePrice[$categoryID]*$out_stock;
		
		$totalSaleValue+=$salePrice; 
		$totalStockValue+=round($stockPrice, 4); 
		
		
	?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $categoryName;?></td>		
			<td style="font-weight:bold;"><?php echo ($prev_bal_qty) ? (($prev_bal_qty > 0) ? '<span class="debit">'.abs($prev_bal_qty).'</span>' : '<span class="credit">'.abs($prev_bal_qty).'</span>') : '-';?></td>
		
			<td class='debit'><?php echo ($in_stock) ? $in_stock : '-';?></td>
			<td class='damaged'><?php echo ($damaged_stock) ? $damaged_stock : '-';?></td>
			<td style="font-weight:bold;"><?php echo ($total_bal_stock) ? (($total_bal_stock > 0) ? '<span class="debit">'.abs($total_bal_stock).'</span>' : '<span class="credit">'.abs($total_bal_stock).'</span>') : '-';?></td>				
			<td class='credit'><?php echo ($out_stock) ? $out_stock : '-';?></td>
			<td><?php echo $unitSaleRate;?></td>
			<td><?php echo $salePrice;?></td>
			<td><?php echo round($stockPrice, 4);?></td>
		</tr>
		
	<?php	
	}	
	?>
		<tr style="font-weight:bold;">
			<td colspan='8' style="border-top:1px solid #aaa; text-align:right;">Total: </td>
			<td style="border-top:1px solid #aaa;"><?php echo $totalSaleValue;?></td>
			<td style="border-top:1px solid #aaa;"><?php echo $totalStockValue;?></td>
		</tr>
	</table>
	


