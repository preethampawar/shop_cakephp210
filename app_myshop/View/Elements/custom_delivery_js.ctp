<?php
App::uses('Order', 'Model');
?>
<script>
	var handleError = function (err) {
		alert('Network error. Please check the internet connection and try again.')
		return new Response(JSON.stringify({
			code: 400,
			message: 'Network Error'
		}));
	};

	// Implementation of JS async calls to consume APIs

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
</script>

<script>
	// page reload
	function refreshPage() {
		location.reload();
	}

	// price formatter
	function formatPrice($value) {
		return '&#8377;'.$value;
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
		$('#confirmPopupSpinner').addClass('d-none');

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

	function showAlert(msg, title) {
		title = title ? title : 'Alert!'

		$('#alertModalLabel').html(title)
		$('#alertModalContent').html(msg)
		alertModal.show()
	}

	function hideAlert() {
		alertModal.hide()
	}

	function htmlEncodeString(rawStr) {
		if (typeof(rawStr) === 'undefined' || rawStr.length <= 0) {
			return ""
		}

		//This code will replace all characters in the given range (unicode 00A0 - 9999, as well as ampersand, greater & less than) with their html entity equivalent
		return rawStr.replace(/[\u00A0-\u9999<>\&]/g, function(i) {
			return '&#'+i.charCodeAt(0)+';';
		});
	}

	function orderIsShipped(encodedOrderId, orderNo) {
		let confirmed = confirm("Order No. #"+orderNo+". Are you sure you have picked up this order?");

		if (confirmed) {
			let orderEmailUrl = '/orders/sendOrderEmail/' + encodedOrderId + '/SHIPPED';
			getData(orderEmailUrl).then(function (resp) {
				alert('Message is sent to the customer.');
			}).finally(function() {
				refreshPage();
			})
		}
	}
	
	function orderIsDelivered(encodedOrderId, orderNo) {
		let confirmed = confirm("Order No. #"+orderNo+". Click 'OK' if you have delivered this order.");

		if (confirmed) {
			let orderDeliveredUrl = '/deliveries/updateOrderStatusDelivered/' + encodedOrderId + '/1';
			getData(orderDeliveredUrl).then(function (resp) {
				alert('Message is sent to the customer.');
			}).finally(function() {
				refreshPage();
			})
		}
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

</script>

<script>
	var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
		keyboard: false
	});

	// scripts executed after the page load
	$(document).ready(function () {
		try {
			showServerToastMessages();
		} catch (err) {
			console.log('Error - Toast messages: ', err.message);
		}
	});
</script>

