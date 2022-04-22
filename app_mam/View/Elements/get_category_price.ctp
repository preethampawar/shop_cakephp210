<script type="text/javascript">
var categoryPrice = new Array();
var selectedCategoryID;
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
		categoryPrice['<?php echo $row['Category']['id'];?>'] = '<?php echo $row['Category']['selling_price'];?>';
	<?php	
	}
	?>
	</script>
<?php	
}
?>
<script type="text/javascript">
function getCategoryPrice(categoryID) {
	selectedCategoryID = categoryID;
	if(categoryPrice[categoryID]) {
		return categoryPrice[categoryID];
	}
	else {
		return null;
	}
}

function showCategoryPrice(elementID) {
	var categoryID = $('#'+elementID).val();	
	var unitrate = getCategoryPrice(categoryID);	
	if(unitrate == null) {
		unitrate = 0;
	}
	$('#UnitRate').attr('value', unitrate);		
	setTotalAmount();
}

function setTotalAmount() {
	quantity = $('#Quantity').val();	
	if(quantity == null) {
		quantity = 0;
	}
	
	if(quantity != '') {
		var numericExpression = /^[0-9]+$/;
		if(quantity.match(numericExpression)) {		
		price = getCategoryPrice(selectedCategoryID);	
			if(price > 0) {
				totalprice = price*quantity;
				totalprice = totalprice.toFixed(2);
				$('#TotalAmount').val(totalprice);
				$('#PaymentAmount').val(totalprice);
			}
			else {
				
			}
		} else {
			alert('Invalid Quantity Entered');
			$('#Quantity').focus();
			return false;			
		}
	}
}

function calculateTotal() {
	quantity = $('#Quantity').val();	
	if(quantity != '') {
		var numericExpression = /^[0-9]+$/;
		if(quantity.match(numericExpression)) {		
		price = $('#UnitRate').val();	
			if(price > 0) {
				totalprice = price*quantity;
				totalprice = totalprice.toFixed(2);
				$('#TotalAmount').val(totalprice);
				$('#PaymentAmount').val(totalprice);
			}
			else {
				
			}
		} else {
			alert('Invalid Quantity Entered');
			$('#Quantity').focus();
			return false;			
		}
	}
}
</script>