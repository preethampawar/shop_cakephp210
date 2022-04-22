<script type="text/javascript">
var selectedCategoryID;
var categoryQtyPerCase = new Array();
var categoryCP = new Array();
var categorySP = new Array();
</script>
<?php
App::uses('Category', 'Model');
$this->Category = new Category;
$conditions = array('Category.company_id'=>$this->Session->read('Company.id'), 'Category.is_product'=>'1');
$categories = $this->Category->find('all', array('conditions'=>$conditions, 'order'=>array('Category.id asc'), 'recursive'=>'-1'));
if(!empty($categories)) {
?>
	<script type="text/javascript">
	<?php
	foreach($categories as $row) {	
	?>
		categoryQtyPerCase['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['qty_per_case'];?>';
		categoryCP['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['cost_price'];?>';
		categorySP['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['selling_price'];?>';
	<?php	
	}
	?>	
	</script>
<?php	
}
?>
<script type="text/javascript">
function setSelectedCategory(categoryID) {
	selectedCategoryID = categoryID;
}
</script>