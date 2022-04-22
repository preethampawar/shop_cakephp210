<script type="text/javascript">
var selectedCategoryID;
var categoryQty = new Array();
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
		categoryQty['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['qty_per_case'];?>';
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
function getCategoryQty(categoryID) {
	selectedCategoryID = categoryID;
	if(categoryQty[categoryID]) {
		return categoryQty[categoryID];
	}
	else {
		return null;
	}
}
function getCategoryCP(categoryID) {
	selectedCategoryID = categoryID;
	if(categoryCP[categoryID]) {
		return categoryCP[categoryID];
	}
	else {
		return null;
	}
}

function showCategoryQty(elementID) {
	var categoryID = $('#'+elementID).val();	
	var qty = getCategoryQty(categoryID);	
	var cp = getCategoryCP(categoryID);	
	$('#Quantity').attr('value', qty);	
	$('#UnitRate').attr('value', cp);	
	setTotalQty();
}

function setTotalQty() {
	noOfCases = $('#CaseQty').val();
	if(noOfCases != '') {
		if(noOfCases.match(numericExpression)) {		
		var numericExpression = /^[0-9]+$/;
			categoryCaseQty = getCategoryQty(selectedCategoryID);	
			if(categoryCaseQty > 0) {
				totalQty = categoryCaseQty*noOfCases;
				$('#Quantity').val(totalQty);
			}
			else {
				
			}
		} else {
			alert('Invalid Case Quantity');
			$('#CaseQty').focus();
			return false;			
		}
	}
	setPurchaseTotalAmount();
}
</script>