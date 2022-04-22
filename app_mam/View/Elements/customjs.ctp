<script type="text/javascript">
$(document).ready(function($){
	$('.button').corner('2px');
	$('.corner').corner('3px');
	$('#logo').corner('5px');
	
	// $('#mega-menu-1').dcMegaMenu({
		// rowItems: '3',
		// speed: 0,
		// effect: 'slide',
		// event: 'click',
		// fullWidth: true
	// });
	// $('#mega-menu-2').dcMegaMenu({
		// rowItems: '1',
		// speed: 'fast',
		// effect: 'fade',
		// event: 'click'
	// });
	// $('#mega-menu-3').dcMegaMenu({
		// rowItems: '2',
		// speed: 'fast',
		// effect: 'fade'
	// });
	// $('#mega-menu-4').dcMegaMenu({
		// rowItems: '3',
		// speed: 'fast',
		// effect: 'fade'
	// });
	// $('#mega-menu-5').dcMegaMenu({
		// rowItems: '4',
		// speed: 'fast',
		// effect: 'fade'
	// });
	// $('#mega-menu-6').dcMegaMenu({
		// rowItems: '3',
		// speed: 'slow',
		// effect: 'slide',
		// event: 'click',
		// fullWidth: true
	// });
	// $('#mega-menu-7').dcMegaMenu({
		// rowItems: '3',
		// speed: 'fast',
		// effect: 'slide'
	// });
	// $('#mega-menu-8').dcMegaMenu({
		// rowItems: '3',
		// speed: 'fast',
		// effect: 'fade'
	// });
	// $('#mega-menu-9').dcMegaMenu({
		// rowItems: '3',
		// speed: 'fast',
		// effect: 'fade'
	// });
});

function checkPurchasePendingAmount(element) {
	if(element.checked == true) {
		$('#PurchasePendingAmount').attr('readonly', false);
		$('#PurchasePendingAmount').focus();
	}
	if(element.checked == false) {
		$('#PurchasePendingAmount').attr('readonly', true);
		$('#PurchasePendingAmount').attr('value', 0);
	}
}
function checkSalePendingAmount(element) {
	if(element.checked == true) {
		$('#SalePendingAmount').attr('readonly', false);
		$('#SalePendingAmount').focus();
	}
	if(element.checked == false) {
		$('#SalePendingAmount').attr('readonly', true);
		$('#SalePendingAmount').attr('value', 0);
	}
}
function checkCashPendingAmount(element) {
	if(element.checked == true) {
		$('#CashPendingAmount').attr('readonly', false);
		$('#SalePendingAmount').focus();
	}
	if(element.checked == false) {
		$('#CashPendingAmount').attr('readonly', true);
		$('#CashPendingAmount').attr('value', 0);
	}
}
function refreshPage(){
	location.reload();
}
function showHideProductPrice(elementID) {
	if($('#'+elementID).attr('checked') == true) {
		// $('#CategorySellingPrice').attr('required', 'true');	
		$('#productPrice').css('display', 'block');	
	}
	else {
		// $('#CategorySellingPrice').attr('required', false);	
		$('#productPrice').css('display', 'none');			
	}	
}

function setPurchaseFormFields() {
	// get category info
    var categoryID = selectedCategoryID;
    var categoryCaseQty = (categoryQtyPerCase[categoryID]) ? categoryQtyPerCase[categoryID] : 0;
    var categoryCostPrice = (categoryCP[categoryID]) ? categoryCP[categoryID] : 0;
    var categorySellingPrice = (categorySP[categoryID]) ? categorySP[categoryID] : 0;
	
	// calculate form values
	if(isWineStore) {
		var noOfCases = ($('#CaseQty').val()) ? $('#CaseQty').val() : 0;
		var qty = noOfCases*categoryCaseQty;
		var unitrate = categoryCostPrice;	
		var totalprice = unitrate*noOfCases;
		
		// set form values
		$('#Quantity').val(qty);
		$('#UnitRate').val(unitrate);
		$('#TotalAmount').val(totalprice.toFixed(2));
		$('#PaymentAmount').val(totalprice.toFixed(2));
	}
	else {
		var qty = $('#Quantity').val();
		var unitrate = categoryCostPrice;	
		var totalprice = unitrate*qty;
		
		// set form values
		$('#UnitRate').val(unitrate);
		$('#TotalAmount').val(totalprice.toFixed(2));
		$('#PaymentAmount').val(totalprice.toFixed(2));
	}
}

function calculatePurchaseAmount() {	
	if(isWineStore) { 
		var noOfCases = ($('#CaseQty').val()) ? $('#CaseQty').val() : 0;	
		
		// var categoryCaseQty = (categoryQtyPerCase[selectedCategoryID]) ? categoryQtyPerCase[selectedCategoryID] : 0;
		// var qty = noOfCases*categoryCaseQty;
		// $('#Quantity').val(qty);
		
		if(noOfCases != '') {
			var numericExpression = /^[0-9]+$/;
			if(noOfCases.match(numericExpression)) {		
				price = $('#UnitRate').val();
				if(price > 0) {
					totalprice = price*noOfCases;
					totalprice = totalprice.toFixed(2);
					$('#TotalAmount').val(totalprice);
					$('#PaymentAmount').val(totalprice);
				}
				else {
					
				}
			} else {
				alert('Invalid No. of cases');
				$('#CaseQty').val(0);
				$('#CaseQty').focus();
				return false;			
			}
		}
	}
	else {
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
				$('#Quantity').val(0);
				$('#Quantity').focus();
				return false;			
			}
		}
	}
}
</script>

