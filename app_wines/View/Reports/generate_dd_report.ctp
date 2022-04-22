<?php 
$title_for_layout = 'DD Report';
$this->set('title_for_layout',$title_for_layout);
?>
<h1><?php echo $title_for_layout;?></h1>

<?php
debug($result);
debug($openingBalDD);
debug($closingBalDD);
?>
<?php 
if(!empty($result)) {
?>
	<table class='table'>
		<thead>
			<tr>
				<th>Sl.No.</th>
				<th>DD No.</th>
				<th>Opening Balance</th>
				<th>DD Amount</th>
				<th>Purchase Amount</th>
				<th>Balance</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td></td>
				<td></td>
				<td><?php echo ($openingBalDD) ? ($openingBalDD[0][0]['dd_amount']-$openingBalDD[0][0]['dd_purchase']) : 0;?></td>
				<td></td>
				<td></td>
			</tr>
			<?php
			$k=0;
			foreach($result as $row) {
				$k++;
			?>
			<tr>
				<td><?php echo $k;?></td>
				<td><?php echo $result['Dd']['dd_no'];?></td>
				<td><?php echo $result['Dd']['dd_no'];?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
<?php
}
?>	
		
