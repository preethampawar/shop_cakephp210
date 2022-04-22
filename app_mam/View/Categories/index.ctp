<?php
$inventory = false;
$isWineStore = false;
$visibility = false;
$showSP = false;
$showCP = false;

if($this->Session->check('UserCompany')) {	
	switch($this->Session->read('Company.business_type')) {
		case 'personal':			
			break;			
		case 'general':
			$visibility = true;
			break;			
		case 'inventory':
			$inventory = true;
			$visibility = true;
			break;	
		case 'wineshop':
			$inventory = true;
			$isWineStore = true;
			$visibility = true;
			$showCP = true;
			$showSP = true;
			break;			
		case 'finance':
			break;		
		case 'default':
			break;
	}	
}
?>
<script type="text/javascript">
function setTableRowBackground(categoryID) {
	$('.CategoryRow').css('background-color', 'transparent');
	$('#CategoryRow'+categoryID).css('background-color', '#C8F7BE');
}
function highlightTableRowBackground(categoryID) {	
	var bgColor = $('#CategoryRow'+categoryID).css('background-color');
	if(bgColor != '#C8F7BE') {
		$('#CategoryRow'+categoryID).css('background-color', '#FDFFC9');
	}
}
function removeHighlightTableRowBackground(categoryID) {
	var bgColor = $('#CategoryRow'+categoryID).css('background-color');
	if(bgColor != '#C8F7BE') {
		$('#CategoryRow'+categoryID).css('background-color', 'transparent');
	}
}
</script>

<div style="width:950px;">
	<div class="floatLeft">
		<h1>Categories</h1>
	</div>
	<?php echo $this->Html->link('+ Create New Category', array('controller'=>'categories', 'action'=>'add'), array('class'=>'button grey medium floatRight'));?>
	<div class="clear"></div>
	<br>
	<?php //echo $this->Html->link('Reorder All Categories', '/categories/reorder', array('style'=>'float:right;', 'class'=>'button green small'), 'Reordering may take sometime. Please be patient and do not press cancel or back button while the request is in process.');?>	
	<?php
	if(!empty($categories)) {
	?>
	<table cellspacing='0' cellpadding='1'>
		<thead>
			<tr>
				<th style='border-right:1px solid #efefef;'>Category Name</th>				
				<?php echo ($isWineStore) ? "<th width='60' style='border-right:1px solid #efefef;'>Qty/Case</th>" : null;?>			
				<?php echo ($showCP) ? "<th width='100' style='border-right:1px solid #efefef;'>Price/Case</th>" : null;?>			
				<?php echo ($showSP) ? "<th width='80' style='border-right:1px solid #efefef;'>Unit Price" : null;?>			
							
				<?php echo ($visibility) ? "<th width='180' style='border-right:1px solid #efefef;'>Visibility</th>" : null;?>			
				<th width="160">Actions</th>	
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($categories as $id=>$name)
			{
				$categoryName = explode('####', $name);
				$spaceCount = count($categoryName)-1;
				$categoryName = ucwords($categoryName[$spaceCount]);
				$space=null;
				if($spaceCount) {
					$style = $this->Html->image('sub_arrow1.gif').'&nbsp;';
					for($i=0; $i<$spaceCount; $i++) {
						$space.='&nbsp;&nbsp;&nbsp;&nbsp;';
					}
				}
				else {
					$style = '';
				}
				$viewin = '-';		
				$hasInventory = '-';
				$isProduct = false;
				$topLevelCategory = false;
				$spPrice = null;
				$cpPrice = null;
				$qty_per_case = null;
				foreach($allCategories as $cat) { 
					if($cat['Category']['id'] == $id) {
						$visibility1 = array();	
						$topLevelCategory = ($cat['Category']['parent_id']) ? false : true;
						$hasInventory = ($cat['Category']['is_product']) ? 'Yes' : '-';						
						
						if($cat['Category']['is_product']) {
							$spPrice = $cat['Category']['selling_price'];
							$spPrice = ($spPrice > 0) ? $this->Number->currency($spPrice, $CUR) : '-';
							$cpPrice = $cat['Category']['cost_price'];
							$cpPrice = ($cpPrice > 0) ? $this->Number->currency($cpPrice, $CUR) : '-';
						}
						
						if($cat['Category']['show_in_sales']) {
							$visibility1[] = 'Sales';
						}
						if($cat['Category']['show_in_purchases']) {
							$visibility1[] = 'Purchases';
						}
						if($cat['Category']['show_in_cash']) {
							$visibility1[] = 'Cash';
						}
						$viewin = implode(' | ', $visibility1);	
						
						if($cat['Category']['is_product']) {
							$isProduct = true;
						}
						break;
					}
				}
				$qty_per_case = ($cat['Category']['is_product']) ? $cat['Category']['qty_per_case'] : '';
			?>		
				<tr id="CategoryRow<?php echo $id;?>" class="CategoryRow" onclick="setTableRowBackground('<?php echo $id;?>')">
					<td style="border-right:1px solid #efefef;">
						<?php 
						echo $space.$style;
						if(!$isProduct) {
							//echo $this->Html->link($categoryName, '/reports/index/category_id:'.$id, array('style'=>'text-decoration:none;', 'escape'=>false));
							//echo $categoryName;
							echo $this->Html->link($categoryName, array('controller'=>'categories', 'action'=>'edit/'.$id), array('title'=>'Edit Category - '.$categoryName));	
							?>
							&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('category.png', array('style'=>'height:14px; width:14px;', 'alt'=>'+', 'title'=>'Add new category under - '.$categoryName)), '/categories/add/'.$id, array('escape'=>false));?>
							<?php 
						}
						else {
							// echo $this->Html->link($categoryName, '/reports/index/category_id:'.$id, array('style'=>'text-decoration:none; font-weight:normal;', 'escape'=>false));	
							echo $this->Html->link($categoryName, array('controller'=>'categories', 'action'=>'edit/'.$id), array('title'=>'Edit Category - '.$categoryName));							
						}
						?>
					</td>
					<?php echo ($isWineStore) ? "<td style='border-right:1px solid #efefef;'>".$qty_per_case."</td>" : null ?>
					<?php echo ($showCP) ? "<td style='border-right:1px solid #efefef;'>".$cpPrice."</td>" : null ?>
					<?php echo ($showSP) ? "<td style='border-right:1px solid #efefef;'>".$spPrice."</td>" : null ?>
					
					<?php echo ($visibility) ? "<td style='border-right:1px solid #efefef;'>$viewin</td>" : null ?>
					
					<td style="text-align:right;">
						<?php
						if(!$isProduct) {
							// if($topLevelCategory) 
							{
								echo $this->Html->link('Sort', array('controller'=>'categories', 'action'=>'reorder/'.$id), array('title'=>'Reorder all categories and products'), 'Reordering may take sometime. Please be patient and do not press cancel or back button while the request is in process.');						
								echo '&nbsp;|&nbsp;';
							}
						}
						echo $this->Html->link('Edit', array('controller'=>'categories', 'action'=>'edit/'.$id), array('title'=>'Edit Category - '.$categoryName));
						echo '&nbsp;|&nbsp;';
						echo $this->Html->link('Delete', array('controller'=>'categories', 'action'=>'delete/'.$id), array('title'=>'Delete Category - '.$categoryName), "Deleting this category will also remove it's child categories. Are you sure you want to continue?");
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
	else {
		echo '&nbsp;No Categories Found.';
	}
	?>	
</div>
<br><br>
