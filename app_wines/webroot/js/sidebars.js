"use strict";

function sanitizeBillQty(element) {
	let qty = element.value;

	if (qty <= 0) {
		element.value = 1
	}
}

function validateBillQuantity(selectedQuantity, available_qty) {
	let qtyErrorMsg = '';

	switch (available_qty) {
		case 0:
			qtyErrorMsg = 'Error! Out of stock.';
			break;
		case (selectedQuantity > available_qty):
			qtyErrorMsg = 'Error! Selected quantity exceeds available stock.';
			break;
		default:
			break;
	}

	return qtyErrorMsg;
}

function validateBillProductUnitSellingPrice() {
	let unitPriceElement;
	let unitSellingPrice;

	unitPriceElement = document.getElementById('billProductUnitPrice');
	unitSellingPrice = parseInt(unitPriceElement.value);

	if (unitSellingPrice > 0) {
		return true;
	}

	alert('Error! Invalid Unit Selling Price.');
	return false;
}

function setBillDefaults() {
	let selectedProductElement = document.getElementById('billProductId');
	let selectedProduct = selectedProductElement.value;

	if (selectedProduct.length > 0) {
		let qtyElement = document.getElementById('billQty');
		let unitPriceElement = document.getElementById('billProductUnitPrice');
		let productAmountElement = document.getElementById('billProductAmount');

		sanitizeBillQty(qtyElement);

		// get selected product details
		let product = billProductsInfo[selectedProduct];
		let available_qty = product.available_qty;
		let unitSellingPrice = product.unit_selling_price > 0 ? product.unit_selling_price : 0;
		let selectedQuantity = qtyElement.value;
		let totalAmount = unitSellingPrice * selectedQuantity;

		unitPriceElement.value = unitSellingPrice
		productAmountElement.value = totalAmount;

		if (billsAutoSubmitEnabled) {
			saveBill();
		}
	} else {
		// alert('Please select a product.');
	}

	return;
}

function calculateBillAmount() {
	let selectedProductElement = document.getElementById('billProductId');
	let selectedProduct = selectedProductElement.value;

	if (selectedProduct.length > 0) {
		let product = billProductsInfo[selectedProduct];
		let available_qty = product.available_qty;
		let qtyElement = document.getElementById('billQty');
		let unitPriceElement = document.getElementById('billProductUnitPrice');
		let productAmountElement = document.getElementById('billProductAmount');
		let qtyErrorMsg = false;
		let isUnitSellingPriceValid = false;
		productAmountElement.value = 0;
		let selectedQuantity = qtyElement.value;
		let unitSellingPrice = unitPriceElement.value;

		qtyErrorMsg = validateBillQuantity(selectedQuantity, available_qty);
		isUnitSellingPriceValid = validateBillProductUnitSellingPrice();

		if (qtyErrorMsg.length > 0) {
			alert(qtyErrorMsg);
			return false;
		}

		if (!isUnitSellingPriceValid) {
			alert('Error! Invalid Unit Selling Price.');
			return false;
		}

		productAmountElement.value = unitSellingPrice * selectedQuantity;
	} else {
		alert('Please select a product.');
		return false;
	}

	return true;
}

function saveBill() {
	if (!calculateBillAmount()) {
		return;
	}

	$('#BillEditForm').submit();
}

/* global bootstrap: false */
(function () {
  'use strict'
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl)
  })


	// In your Javascript (external .js resource or <script> tag)

	if ($('.autoSuggest').length) {
		$('.autoSuggest').select2();
	}

	if ($('.autoSuggestAutoOpen').length) {
		$('.autoSuggestAutoOpen').select2();
	}


	if ($('table.search-table').length) {
		$('table.search-table').tableSearch({
			searchText: '', searchPlaceHolder: 'Search...', caseSensitive: false
	  	});
	}
})();

$(document).on('select2:open', () => {
	document.querySelector('.select2-search__field').focus();
});

(function () {
	$('.autoSuggestAutoOpen').select2('open');
})();

