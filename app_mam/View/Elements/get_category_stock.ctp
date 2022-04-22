<script type="text/javascript">
var categoryStock = new Array();
var categoryPrice = new Array();
var selectedCategoryID;
</script>

<!-- Product Stock in hand -->
<?php
App::uses('Inventory', 'Model');
$this->Inventory = new Inventory;
$conditions = array('Inventory.company_id'=>$this->Session->read('Company.id'));
$group = array('Inventory.category_id', 'Inventory.type');
$fields = array('SUM(`Inventory`.`quantity`) as Quantity', 'Inventory.category_id', 'Inventory.type');
$categoriesStock = $this->Inventory->find('all', array('conditions'=>$conditions, 'fields'=>$fields, 'group'=>$group, 'order'=>array('Inventory.id asc'), 'recursive'=>'-1'));
$categoryStock = array();
$balanceStock = array();

if(!empty($categoriesStock)) {
	foreach($categoriesStock as $row) {
		if($row['Inventory']['type'] == 'in') {
			$categoryStock[$row['Inventory']['category_id']]['in'] = $row[0]['Quantity'];
		}
		if($row['Inventory']['type'] == 'out') {
			$categoryStock[$row['Inventory']['category_id']]['out'] = $row[0]['Quantity'];
		}
		if($row['Inventory']['type'] == 'damaged') {
			$categoryStock[$row['Inventory']['category_id']]['damaged'] = $row[0]['Quantity'];
		}
	}
	
	foreach($categoryStock as $categoryID=>$row) {
		$stockin = (isset($row['in']) and (!empty($row['in']))) ? $row['in'] : 0;
		$stockout = (isset($row['out']) and (!empty($row['out']))) ? $row['out'] : 0;
		$stockdamaged = (isset($row['damaged']) and (!empty($row['damaged']))) ? $row['damaged'] : 0;
		$balanceStock[$categoryID] = $stockin - $stockout - $stockdamaged;
	}	
}

if(!empty($balanceStock)){
?>
	<script type="text/javascript">
	<?php
	foreach($balanceStock as $categoryID=>$stock) {	
	?>
		categoryStock['<?php echo $categoryID;?>'] = '<?php echo $stock;?>';
	<?php	
	}
	?>
	</script>
<?php	
}
?>


<!-- Product unit price -->
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
// product stock
function getCategoryStock(categoryID) {
	$('#category').focus();	
	selectedCategoryID = categoryID;
	if(categoryStock[categoryID]) {
		return categoryStock[categoryID];
	}
	else {
		return null;
	}
}

function showCategoryStock(elementID) {
	var categoryID = $('#'+elementID).val();	
	var stockinhand = getCategoryStock(categoryID);	
	$('#StockInHand').attr('value', stockinhand);	
	if(stockinhand > 0) {
		$('#Quantity').attr('placeholder', 'Qty. should be less than "'+stockinhand+'"');	
	}
	else {
		$('#Quantity').attr('placeholder', 'Product is out of stock');		
	}
	setSaleQuantity();
}

function setSaleQuantity() {
	quantity = $('#Quantity').val();		
	if(quantity != '') {
		var numericExpression = /^[0-9]+$/;
		if(quantity.match(numericExpression)) {		
			stockinhand = getCategoryStock(selectedCategoryID);
			
			stockinhand = parseInt(stockinhand);
			quantity = parseInt(quantity);
			
			if(stockinhand <= quantity) {
				alert('Available stock cannot be greater than or equal to Stock in hand');
				$('#Quantity').val(0);
				$('#Quantity').focus();
				return false;	
			}
			
			if(stockinhand > 0) {				
				salestock = stockinhand-quantity;				
				$('#SaleStock').val(salestock);
			}
			else {
				alert('Product is already out of stock.')
			}
		} else {
			alert('Invalid Quantity Entered');
			$('#Quantity').val(0);
			$('#Quantity').focus();
			return false;			
		}
	}
}

// product price
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
	$('#UnitRate').attr('value', unitrate);	
	setTotalAmount();
}

function setTotalAmount() {
	quantity = $('#SaleStock').val();		
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
</script>