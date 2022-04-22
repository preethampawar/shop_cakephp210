// POST method implementation:
async function postData(url = '', data = {}) {
	// Default options are marked with *
	const response = await fetch(url, {
		method: 'POST', // *GET, POST, PUT, DELETE, etc.
		mode: 'cors', // no-cors, *cors, same-origin
		cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
		credentials: 'same-origin', // include, *same-origin, omit
		headers: {
			'Content-Type': 'application/json'
			// 'Content-Type': 'application/x-www-form-urlencoded',
		},
		redirect: 'follow', // manual, *follow, error
		referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
		body: JSON.stringify(data) // body data type must match "Content-Type" header
	}).catch(handleError);

	if (response.ok) {
		return response.json();
	} else {
		return Promise.reject(response);
	}
}

// GET page implementation:
async function getPage(url = '', data = {}) {
	const response = await fetch(url + '?isAjax=1').catch(handleError);

	if (response.ok) {
		return response.text(); // parses into html
	} else {
		return Promise.reject(response);
	}
}

// GET data implementation:
async function getData(url = '', data = {}) {
	const response = await fetch(url).catch(handleError);

	if (response.ok) {
		return response.json();
	} else {
		return Promise.reject(response);
	}
}

function handleErrors(response) {
	if (!response.ok) {
		throw Error(response.statusText);
	}
	return response;
}

/* Utility Functions */

// page reload
function refreshPage() {
	location.reload();
}

// lazy load images
function lazyLoadImages() {
	// $("img.lazy").Lazy({
	// 	attribute: "data-original",
	// 	threshold: 200,
	// });

	const images = document.querySelectorAll('.lazy[data-original]');
	const config = {
		rootMargin: '0px 0px 50px 0px',
		threshold: 0
	};

	let observer = new IntersectionObserver(function (entries, self) {
		entries.forEach(entry => {
			if (entry.isIntersecting) {
				// console.log(`Image ${entry.target.src} is in the viewport!`);
				preloadImage(entry.target);
				// Stop watching and load the image
				self.unobserve(entry.target);
			}
		});
	}, config);

	images.forEach(image => {
		observer.observe(image);
	});

	function preloadImage(img) {
		const src = img.getAttribute('data-original');
		if (!src) { return; }
		img.src = src;

		img.removeAttribute('data-original');
	}
}

function delayImagesAfterPageLoad() {
	$('img.delay-loading').each(function () {
		var imagex = $(this);
		var imgOriginal = imagex.data('original');
		$(imagex).attr('src', imgOriginal);
	});
}

// init bootstrap tooltips
function initTooltips() {
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	});
}

// show toast messages
function showToastMessages() {
	var toastElList = [].slice.call(document.querySelectorAll('.toast-js'))
	var toastList = toastElList.map(function (toastEl) {
		return new bootstrap.Toast(toastEl)
	});

	toastList.forEach(toast => toast.show());
}

// show server toast messages
function showServerToastMessages() {
	var toastElList = [].slice.call(document.querySelectorAll('.toast-php'))
	var toastList = toastElList.map(function (toastEl) {
		return new bootstrap.Toast(toastEl)
	});

	toastList.forEach(toast => toast.show());
}

// hide toast messages
function hideToastMessages() {
	var toastElList = [].slice.call(document.querySelectorAll('.toast'))
	var toastList = toastElList.map(function (toastEl) {
		return new bootstrap.Toast(toastEl)
	});

	toastList.forEach(toast => toast.hide());
}

// load shopping cart in top nav
function loadShoppingCartHeader() {
	let topNavCartHeaderUrl = '/shopping_carts/loadTopNavCartHeader';
	const data = getPage(topNavCartHeaderUrl);
	data.then(function (response) {
		if (response.length > 0) {
			$("#topNavShoppingCart").html(response);
		}
	});

	return data;
}

// load shopping cart in top nav
function loadShoppingCart() {
	let topNavCartUrl = '/shopping_carts/loadTopNavCart';
	const data = getPage(topNavCartUrl);

	data.then(function (response) {
		if (response.length > 0) {
			$("#myShoppingCartBody").html(response);
			lazyLoadImages();
		}
	})

	return data;
}

// price formatter
function formatPrice(value) {
	return '&#8377;' + value;
}

// generate random number
function getRndInteger(min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

// show confirm popup
function showConfirmPopup(url, title = '', content = '', okText = '') {
	let confirmPopup;

	title = title ? title : '';
	content = content ? content : 'Are you sure?';
	okText = okText ? okText : 'Ok';

	$("#confirmPopup .modal-content .modal-header .modal-title").html(title);
	$("#confirmPopup .modal-content .modal-body .content").html(content);
	$("#confirmPopup .modal-footer .ok").html(okText);
	$('#confirmPopupBuyerSpinner').addClass('d-none');

	$("#confirmPopup .modal-content .modal-header").show();
	if (title == '') {
		$("#confirmPopup .modal-content .modal-header").hide();
	}

	if ('#' !== url) {
		$("#confirmPopup .modal-content .actionLink").attr('href', url);
		$("#confirmPopup .modal-content .actionLink").removeClass('d-none');
		$("#confirmPopup .modal-content .cancelButton").removeClass('d-none');
		$("#confirmPopup .modal-content .actionLinkButton").addClass('d-none');
	} else {
		$("#confirmPopup .modal-content .actionLink").addClass('d-none');
		$("#confirmPopup .modal-content .cancelButton").addClass('d-none');
		$("#confirmPopup .modal-content .actionLinkButton").removeClass('d-none');
	}

	confirmPopup = new bootstrap.Modal(document.getElementById('confirmPopup'));
	confirmPopup.show();

	return false;
}

// show delete popup
function showDeleteImagePopup(deleteImageUrl, deleteImageActionUrl, title = '', content = '', okText = '') {
	var deletePopup;
	title = title ? title : '';
	content = content ? content : 'Are you sure you want to delete it?';
	okText = okText ? okText : 'Ok';

	$("#deleteImagePopup .modal-content .modal-header .modal-title").html(title);
	$("#deleteImagePopup .modal-content .modal-body .content").html(content);
	$("#deleteImagePopup .modal-footer .ok").html(okText);

	if (title == '') {
		$("#deleteImagePopup .modal-content .modal-header").hide();
	}

	// $("#deleteImagePopup .modal-content .deleteLink").attr('href', deleteUrl);

	$('#deleteImagePopup .modal-footer .deleteLink').on('click', function (event) {
		getData(deleteImageUrl).then(
			function (response) {
				if (response.error) {
					alert('error');
					return;
				}

				// $("#deleteImagePopup .modal-content .deleteLink").attr('href', deleteUrl);
				window.location.href = deleteImageActionUrl;


				console.log(response);
			})
	})

	deletePopup = new bootstrap.Modal(document.getElementById('deleteImagePopup'));
	deletePopup.show();
}

// show delete product from cart popup
function showDeleteProductFromCartPopup(shoppingCartProductId, productName) {
	if (!deleteProductFromCartPopup) {
		deleteProductFromCartPopup = new bootstrap.Modal(document.getElementById('deleteProductFromCartPopup'), {
			keyboard: false
		});
	}
	// myShoppingCart.hide()

	deleteProductFromCartPopup.show();

	$('#deleteProductFromCartPopupProductName').html(productName);
	$('#deleteProductFromCartPopupProductName').data('delete-shopping-cart-product-id', shoppingCartProductId);
}

function deleteProductFromCart() {
	const shoppingCartProductId = $('#deleteProductFromCartPopupProductName').data('delete-shopping-cart-product-id')
	const url = '/ShoppingCarts/deleteShoppingCartProduct/' + shoppingCartProductId + '/1'

	const result = getData(url)

	result.then(function (response) {
		if (response.error) {
			alert('error');
			return;
		}

		loadShoppingCart()
		loadShoppingCartHeader()
	})
	result.finally(function () {
		deleteProductFromCartPopup.hide();
		myShoppingCart.show()
	})

	return result
}

function productAddToCart(categoryId, productId, productQty = 1, ele) {
	const buttonContent = ele.innerHTML;
	const setSpinner = function () {
		ele.innerHTML = "<i class='fas fa-spinner fa-pulse'></i>";
	}
	const resetSpinner = function () {
		ele.innerHTML = buttonContent;
	}

	setSpinner();

	const response = addToCart(categoryId, productId, productQty, null);

	response.then(function (data) {
		$('#ToastMessage').removeClass('d-none');
		$('#toastDiv').removeClass('bg-primary');
		$('#toastDiv').removeClass('bg-danger');
		$('#toastDiv').removeClass('bg-notice');

		if (data.success == 1) {
			let cartData = loadShoppingCart();
			cartData.finally(function () {
				resetSpinner()
			})

			loadShoppingCartHeader();

			$('#toastDiv').addClass('bg-primary');
			$("#toastDiv .toast-body").html("<i class='fa fa-check-circle'></i> Product successfully added to cart.");
		} else {
			$('#toastDiv').addClass('bg-danger');
			$("#toastDiv .toast-body").html("<i class='fa fa-exclamation-circle'></i> " + data.errorMessage);
		}

		showToastMessages();
	})
	response.finally(function () {
		resetSpinner()
	})

	return response;
}

// show Order Summary
function showOrderSummary() {
	loadOrderSummary()
	orderPaymentDetails.hide()
	orderSummary.show()
}

// show Order Delivery Details
function showOrderDeliveryDetails() {
	loadOrderDeliveryDetails()
	myShoppingCart.hide()
	orderDeliveryDetails.show()
}

// show Order Payment Details
function showOrderPaymentDetails() {
	loadOrderPaymentDetails()
	orderDeliveryDetails.hide()
	orderPaymentDetails.show()
}

// load order Summary
function loadOrderSummary() {
	let orderSummaryUrl = '/shopping_carts/loadOrderSummary';
	const data = getPage(orderSummaryUrl);
	$("#orderSummaryBody").html(spinner);
	data.then(function (response) {
		$("#orderSummaryBody").html(response);
	})

	return data;
}

// load Order Delivery Details
function loadOrderDeliveryDetails() {
	let loadDeliveryDetailsUrl = '/shopping_carts/loadOrderDeliveryDetails';
	const data = getPage(loadDeliveryDetailsUrl);
	$("#orderDeliveryDetailsBody").html(spinner);
	data.then(function (response) {
		$("#orderDeliveryDetailsBody").html(response);
	})

	return data;
}

// load Order Payment Details
function loadOrderPaymentDetails() {
	let loadPaymentDetailsUrl = '/orders/loadOrderPaymentDetails';
	const data = getPage(loadPaymentDetailsUrl);
	$("#orderPaymentDetailsBody").html(spinner);
	data.then(function (response) {
		$("#orderPaymentDetailsBody").html(response);
	})

	return data;
}

function saveOrderDeliveryDetails() {
	const saveOrderDeliveryDetails = '/orders/saveOrderDeliveryDetails'
	let form = document.getElementById('ShoppingCartLoadOrderDeliveryDetailsForm')
	const formValid = form.checkValidity()
	form.classList.add('was-validated')

	$('#deliveryErrorAlert').addClass('d-none')

	if (formValid) {
		let formData = new FormData(document.getElementById('ShoppingCartLoadOrderDeliveryDetailsForm'));
		let customerName = formData.get('data[customer_name]')
		let customerPhone = formData.get('data[customer_phone]')
		let customerEmail = formData.get('data[customer_email]')
		let customerAddress = formData.get('data[customer_address]')
		let customerMessage = formData.get('data[customer_message]')
		let data = {
			'customerName': customerName,
			'customerPhone': customerPhone,
			'customerEmail': customerEmail,
			'customerAddress': customerAddress,
			'customerMessage': customerMessage,
		}

		setCustomerDeliveryDetails(data);

		const response = postData(saveOrderDeliveryDetails, data)

		let loader = spinner + '<div class="text-center small">Please wait.</div>'

		$('#orderDeliveryDetailsSpinner').html(loader);
		$('#saveOrderDeliveryDetailsButton').addClass('disabled');

		response.then(function (data) {
			if (data.error != 1) {
				showOrderPaymentDetails();
			} else {
				$('#deliveryErrorAlert').removeClass('d-none')
				$('#deliveryErrorAlert .content').html(data.errorMsg)
				$('#deliveryErrorAlert .btn-close').focus()
			}
		}).finally(function () {
			$('#orderDeliveryDetailsSpinner').html('');
			$('#saveOrderDeliveryDetailsButton').removeClass('disabled');
		})

	} else {
		$('#deliveryErrorAlert .content').html('There are some missing/invalid values in the form. Please fix those and try again.')
		$('#deliveryErrorAlert').removeClass('d-none')
		$('#deliveryErrorAlert .btn-close').focus()
	}
}

function saveOrderPaymentDetails() {
	const saveOrderPaymentUrl = '/orders/saveOrderPaymentDetails'
	let form = document.getElementById('ShoppingCartLoadOrderPaymentDetailsForm')
	const formValid = form.checkValidity()
	form.classList.add('was-validated')

	$('#paymentErrorAlert').addClass('d-none')

	if (formValid) {
		let formData = new FormData(document.getElementById('ShoppingCartLoadOrderPaymentDetailsForm'));
		let paymentMethod = formData.get('data[payment_method]')
		let paymentReferenceNo = formData.get('data[payment_reference_no]')
		let data = {
			'paymentMethod': paymentMethod,
			'paymentReferenceNo': paymentReferenceNo,
		}

		const response = postData(saveOrderPaymentUrl, data)

		let loader = spinner + '<div class="text-center small">Please wait.'

		$('#orderPaymentDetailsSpinner').html(loader);
		$('#placeOrderButton').addClass('disabled');

		response.then(function (data) {
			if (data.error != 1) {
				showOrderSummary()
			} else {
				$('#paymentErrorAlert').removeClass('d-none')
				$('#paymentErrorAlert .content').html(data.errorMsg)
				$('#paymentErrorAlert .btn-close').focus()
			}
		}).finally(function () {
			$('#orderPaymentDetailsSpinner').html('');
			$('#placeOrderButton').removeClass('disabled');
		})

	} else {
		$('#paymentErrorAlert .content').html('There are some missing/invalid values in the form. Please fix those and try again.')
		$('#paymentErrorAlert').removeClass('d-none')
		$('#paymentErrorAlert .btn-close').focus()
	}
}

function showPlaceOrderLoginPopup() {
	placeOrderLoginPopupModal.show()
}

function hidePlaceOrderLoginPopup() {
	placeOrderLoginPopupModal.hide()
}

function placeOrder(guest) {
	guest = typeof (guest) !== 'undefined' ? 1 : 0
	const placeOrderUrl = '/orders/create/' + guest
	let spinnerElementId = guest === 1 ? '#confirmOrderSpinnerGuest' : '#confirmOrderSpinner'
	let placeOrderButtonElementId = guest === 1 ? '#placeOrderLinkGuest' : '#placeOrderButton'
	let data = {
		'confirmed': 1,
	}
	let loader = spinner + '<div class="text-center small">Please wait.<br>Your order is in process. Do not press back button.</div>'
	$(spinnerElementId).html(loader);
	$(placeOrderButtonElementId).addClass('disabled');

	const response = postData(placeOrderUrl, data);

	response.then(function (data) {
		if (data.error !== true) {
			loadShoppingCart();
			loadShoppingCartHeader();
			orderSummary.hide()
			showAlert(data.successMsg, 'Success!')

			getData(data.orderEmailUrl).then(function (resp) {
				console.log(resp)
			})
		} else {
			// alert(data.errorMsg)
			//window.location = '/users/login'
			//orderSummary.hide()
			showPlaceOrderLoginPopup()
		}
	}).finally(function () {
		$(spinnerElementId).html('');
		$(placeOrderButtonElementId).removeClass('disabled');
		hidePlaceOrderLoginPopup()
	})
}

function showAlert(msg, title) {
	title = title ? title : 'Alert!'

	$('#alertModalLabel').html(title)
	$('#alertModalContent').html(msg)
	alertModal.show()
}

function hideAlert() {
	alertModal.hide()
}

// add product to cart
function addToCart(categoryId, productId, quantity, shoppingCartId) {
	const addToCartUrl = '/shopping_carts/addToCart';
	let qty = parseInt(quantity)

	if (isNaN(qty)) {
		qty = 1
	}

	qty = qty < 1 ? 1 : qty

	if (!shoppingCartId) {
		shoppingCartId = null
	}

	let data = {
		'ShoppingCartProduct': {
			'quantity': qty,
			'categoryId': categoryId,
			'productId': productId,
			'shoppingCartId': shoppingCartId,
		}
	}

	const response = postData(addToCartUrl, data);

	//
	// response.then(function (data) {
	// 	if (data.success == 1) {
	// 		loadShoppingCart();
	// 		loadShoppingCartHeader();
	// 	}
	// })

	return response
}

function updateProductQtyFromShoppingCart(categoryId, productId, quantity, shoppingCartId) {
	let qty = parseInt(quantity);

	if (isNaN(qty)) {
		qty = 1
	}

	qty = qty < 1 ? 1 : qty;

	$('#updatingCartSpinner' + shoppingCartId).removeClass('d-none');
	const response = addToCart(categoryId, productId, qty, shoppingCartId);

	response.then(function (data) {
		if (data.success == 1) {
			let cartData = loadShoppingCart();

			cartData.finally(function () {
				$('#updatingCartSpinner' + shoppingCartId).addClass('d-none');
			})

			loadShoppingCartHeader();
		} else {
			$('#updatingCartSpinner' + shoppingCartId).addClass('d-none');
		}
	})

	return response;
}

// load store banners in homepage
function showSlideShowInHomePage() {
	let slideShowUrl = '/banners/slideshow/1';
	const data = getPage(slideShowUrl);
	data.then(function (response) {
		$("#storeSlideShow").html(response);
	});

	return data;
}

function zoomInEffect(elementId) {
	$("#" + elementId).addClass('transition');
}

function zoomOutEffect(elementId) {
	$("#" + elementId).removeClass('transition');
}

function setRating(elementId, productId) {
	let rating = $("#" + elementId).data('rating')
	let ratingsDivId = '#ratingsDiv' + productId

	rating = rating < 0 ? 0 : rating
	rating = rating > 5 ? 5 : rating
	fillProductRatingStars(rating)

	let setProductRatingUrl = '/products/setRating/' + productId + '/' + rating
	const data = getPage(setProductRatingUrl);
	$(ratingsDivId).addClass('disabledElement');
	data.then(function (response) {
		// showAlert('Thank you so much for taking the time to leave us a rating.', 'You are Awesome :)')
	}).finally(function () {
		$(ratingsDivId).removeClass('disabledElement');
	});
}

function submitProductReview(categoryId, productId) {
	const productReviewUrl = '/products/submitProductReview/' + categoryId + '/' + productId
	const comments = $('<div />').text($('#productReview' + productId).val()).html()

	let data = {
		'categoryId': categoryId,
		'productId': productId,
		'comments': comments,
	}
	$('#submitReviewButton').addClass('disabledElement')
	const response = postData(productReviewUrl, data)
	response.then(function (data) {
		if (data.error != 1) {
			showAlert('Thank you so much for taking the time to leave us a rating.', 'You are Awesome :)')
		}
	}).finally(function () {
		$('#submitReviewButton').removeClass('disabledElement')
		location.reload()
	})

	response.then(function (response) {
		showAlert('Thank you so much for taking the time to leave us a rating.', 'You are Awesome :)')
	});
}

function fillProductRatingStars(ratingValue) {
	ratingValue = ratingValue < 0 ? 0 : ratingValue;
	ratingValue = ratingValue > 5 ? 5 : ratingValue;

	for (let i = 1; i <= 5; i++) {
		if (i <= ratingValue) {
			$('#starRating' + i).children(0).removeClass('far')
			$('#starRating' + i).children(0).addClass('fa')
		} else {
			$('#starRating' + i).children(0).removeClass('fa')
			$('#starRating' + i).children(0).addClass('far')
		}
	}
}

function htmlEncodeString(rawStr) {
	if (typeof (rawStr) === 'undefined' || rawStr.length <= 0) {
		return ""
	}

	//This code will replace all characters in the given range (unicode 00A0 - 9999, as well as ampersand, greater & less than) with their html entity equivalent
	return rawStr.replace(/[\u00A0-\u9999<>\&]/g, function (i) {
		return '&#' + i.charCodeAt(0) + ';';
	});
}

function applyPromoCode(availablePromoCode) {
	let promoCode = btoa($('#promoCodeVal').val().trim())

	if (availablePromoCode && availablePromoCode.length > 0) {
		promoCode = btoa(availablePromoCode);
	}

	if (promoCode.length < 1) {
		showAlert('Please enter promo code')

		return
	}

	const promoUrl = '/promo_codes/applyCode/' + promoCode
	const result = getData(promoUrl)

	result.then(function (response) {
		if (response.error) {
			showAlert(response.errorMsg)
			return
		}
		showAlert(response.successMsg, 'Success!')
		loadShoppingCart()
	})
	result.finally(function () {
		// myShoppingCart.show()
	})

	return result
}

function removePromoCode() {
	const promoUrl = '/promo_codes/removeCode'
	const result = getData(promoUrl)

	result.then(function (response) {
		if (response.error) {
			showAlert(response.errorMsg)
			return
		}
		showAlert(response.successMsg, 'Success!')
		loadShoppingCart()
	})
	result.finally(function () {
		// myShoppingCart.show()
	})

	return result
}

function hidePaymentAlertError() {
	$('#paymentErrorAlert').addClass('d-none')
}

function hideDeliveryAlertError() {
	$('#deliveryErrorAlert').addClass('d-none')
}

function disableButton(id) {
	$('#' + id).addClass('disabled')
	return false;
}

function verifyOtp() {
	let otp = $('#UserVerifyOtp').val().trim();
	if (otp === '' || otp.length != 4 || isNaN(otp) === true) {
		alert('Enter valid 4 digit OTP.');
		return false;
	}

	const url = '/users/otpVerification';
	let data = {
		'verifyOtp': otp,
	}
	let spinnerElementId = '#confirmOrderSpinnerGuest';
	let loader = spinner + '<div class="text-center small">Please wait.<br>OTP verification is in process. Do not press back button.</div>'

	$(spinnerElementId).html(loader);
	$('#orderVerifyOtpButton').addClass('disabled');

	const response = postData(url, data)

	response.then(function (data) {
		if (data.error !== true) {
			placeOrder(1);
		} else {
			$(spinnerElementId).html('');
			$('#orderVerifyOtpButton').removeClass('disabled');

			alert(data.msg)
		}
	}).finally(function () {
		$('#UserVerifyOtp').val('');
	})
}

function showRequestProcessingMsg(element) {
	$(element).addClass('disabled');
	$(element).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
}

function checkIfHuman(e) {

	let int1 = Math.floor((Math.random() * 10) + 1);
	let int2 = Math.floor((Math.random() * 10) + 1);

	var answer = prompt('What is ' + int1 + '+' + int2 + ' = ?', "");
	if (answer == (int1 + int2)) {
		return true;
	} else {
		e.preventDefault();
		alert('Invalid answer. Please try again.')
	}

	return false;
}

function shareThis(title, text, url, fileArray) {
	title = title ?? '';
	text = text ?? '';
	url = url ?? '';
	fileArray = fileArray ?? [];

	if (url !== '') {
		let url2 = new URL(url);
		url2.searchParams.append('emShareButton', 1);
		url = url2.href;
	}


	if (title != '') {
		const shareData = {
			title: title,
			text: text,
			url: url,
			files: fileArray
		}

		async function shareNow(shareData) {
			try {
				await navigator.share(shareData)
			} catch (err) {
				console.log('Error: ' + err)
			}
		}

		shareNow(shareData)
	}
}

function checkIfShareThisApiIsEnabled() {
	if (navigator.canShare) {
		$('.shareButton').removeClass('d-none');
	}
}

function showLoadingBar() {
	document.getElementById("topNavProgressBar").classList.remove('d-none')
}

function hideLoadingBar() {
	document.getElementById("topNavProgressBar").classList.add('d-none')
}

function setCustomerDeliveryDetails(data) {
	console.log('save delivery details', data);
	localStorage.setItem('customer_delivery_details', JSON.stringify(data));
}

function getCustomerDeliveryDetails() {
	let data = localStorage.getItem('customer_delivery_details');
	console.log('get delivery details', data);
	return JSON.parse(data);
}

function fillCustomerOrderDetailsFromLocalStorage() {
	let data = localStorage.getItem('customer_delivery_details');
	let deliveryDetails = JSON.parse(data);

	if (deliveryDetails) {
		if ($('#orderCustomerName')) {
			$('#orderCustomerName').val(deliveryDetails.customerName);
		}

		if ($('#orderCustomerEmail')) {
			$('#orderCustomerEmail').val(deliveryDetails.customerEmail);
		}

		if ($('#orderCustomerPhone')) {
			$('#orderCustomerPhone').val(deliveryDetails.customerPhone);
		}

		if ($('#orderCustomerAddress')) {
			$('#orderCustomerAddress').val(deliveryDetails.customerAddress);
		}

		if ($('#orderCustomerMessage')) {
			$('#orderCustomerMessage').val(deliveryDetails.customerMessage);
		}
	}
}

let imageCount = 0;

function fetchHomePageProducts() {
	console.log('loading', imageCount);
	window.scrollBy(300, 0);
	imageCount += 5;
};

function loadHomepageProducts() {

	let options = {
		root: null,
		rootMargin: "0px",
		threshold: 0.25
	};

	function handleIntersect(entries, observer) {
		entries.forEach((entry) => {
			console.log('observing entry.target', entry.target);
			if (entry.isIntersecting) {
				if (imageCount !== 20) {
					fetchHomePageProducts()
				} else {
					observer.unobserve(entry.target);
				}

			}
		});
	}

	let observer = new IntersectionObserver(handleIntersect, options);
	observer.observe(document.getElementById('showMoreDeals'));

}

// init site wide variables
var handleError = function (err) {
	// alert('Network error. Please check the internet connection and try again.')
	// return new Response(JSON.stringify({
	// 	code: 400,
	// 	message: 'Network Error'
	// }));
	return new Response('');
};
// set default variables
var cartInfo = null;
var deleteProductFromCartPopup = null;
var spinner = `<div class="text-center">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</div>`;

var myShoppingCartElement = document.getElementById('myShoppingCart');
var myShoppingCart = myShoppingCartElement ? (new bootstrap.Offcanvas(myShoppingCartElement)) : '';
var orderSummaryElement = document.getElementById('orderSummary');
var orderSummary = orderSummaryElement ? (new bootstrap.Offcanvas(orderSummaryElement)) : '';
var orderDeliveryDetailsElement = document.getElementById('orderDeliveryDetails');
var orderDeliveryDetails = orderDeliveryDetailsElement ? (new bootstrap.Offcanvas(orderDeliveryDetailsElement)) : '';
var orderPaymentDetailsElement = document.getElementById('orderPaymentDetails');
var orderPaymentDetails = orderPaymentDetailsElement ? (new bootstrap.Offcanvas(orderPaymentDetailsElement)) : '';

const placeOrderLoginPopup = document.getElementById('placeOrderLoginPopup');
var placeOrderLoginPopupModal = placeOrderLoginPopup ? (new bootstrap.Modal(placeOrderLoginPopup, {
	keyboard: false
})) : '';

const alertModalEle = document.getElementById('alertModal');
var alertModal = alertModalEle ? (new bootstrap.Modal(alertModalEle, {
	keyboard: false
})) : '';


