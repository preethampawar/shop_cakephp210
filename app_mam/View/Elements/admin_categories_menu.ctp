<?php
$showAddButton = false;
$showActions = false;
$showDeleteAction = false;
switch($this->Session->read('UserCompany.user_level')) {
	case '2':
		$showAddButton = true;
		$showActions = true;		
		break;
	case '3':
	case '4':
		$showAddButton = true;
		$showActions = true;
		$showDeleteAction = true;
		break;
	default:
		break;
}

$showAddButton = false;

?>

<?php
	App::uses('Category', 'Model');
	$this->Category = new Category;
	$categories = $this->Category->generateTreeList(array('Category.company_id'=>$this->Session->read('Company.id')), null, null, '####');
	if(!empty($categories)) {
?>
<script type="text/javascript">
function showCategoryActionsDiv(categoryID) {
	var divID = 'categoryActionDiv'+categoryID;
	hideAllCategoryActionDiv(categoryID);
	$('#'+divID).css('display', 'block');
	$('#categoryLink'+categoryID).css('text-decoration', 'underline');
	$('#row'+categoryID).css('background-color', '#f9f9f9');
}	
function hideAllCategoryActionDiv(categoryID) {
	$('.categoryActionDiv').css('display', 'none');
	
	$('.categoryLink').css('text-decoration', 'none');
	$('.divrow').css('background-color', '#fff');
}

</script>
<?php /* <div class="floatRight" id="rightCategoriesMenu" onmouseout="hideAllCategoryActionDiv()"> */ ?>
<div class="floatRight" id="rightCategoriesMenu">
	<h3>All Categories</h3>
	<hr><br>
	<?php
	$style = '&bull;';
	?>
	
	<?php
	if($showAddButton) {
	?>
	<div class="floatLEft">
		<?php echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));?>
		<br><br>
	</div>	
	<?php
	}
	?>
	<div class="clear"></div>	
	<?php
	foreach($categories as $id=>$name)
	{
		$categoryName = explode('####', $name);
		$spaceCount = count($categoryName)-1;
		$categoryName = $categoryName[$spaceCount];
		$space=null;
		if($spaceCount) {
			$style = '&bull;';
			for($i=0; $i<$spaceCount; $i++) {
				$space.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'	;
			}
		}
		else {
			$style = '';
		}
	?>		
		<div>
			<div style="padding:2px 5px;">
				<?php echo $space.$style.$this->Html->link($categoryName, '/reports/generateCategoryReport/'.$id, array('style'=>'text-decoration:none;', 'escape'=>false, 'id'=>'categoryLink'.$id, 'class'=>'categoryLink'));?>
			</div>
			
		</div>			
	<?php	
	}			
	?>
	<br>
	
	
</div>	
<?php
}
else {	
	?>
	<div class="floatRight" id="rightCategoriesMenu">
	<h3>Categories</h3>
		<div id="right" style="margin:0px -15px 0px 0px">
			<div class="block block-login_help" id="block-login_help">
				<div class="content">
					<div class="floatLeft">No Categories Found</div>
					<br><br>
					<div class="clear">
						<?php echo $this->Html->link('Create New Category &nbsp;&raquo;', '/categories/add', array('style'=>'text-decoration:none;', 'escape'=>false, 'class'=>'button small grey', 'title'=>'Create New Category'));?>
					</div>	
				</div>
			</div>
		</div>	
	</div>
<?php
}	
?>
<div class="clear"></div>
