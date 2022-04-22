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

	// lazy load images
	function lazyLoadImages() {
		if ($("img.lazy").length) {
			$("img.lazy").lazyload({
				effect: "fadeIn"
			});
		}
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

	// enable light box for images
	function enableLightBoxForImages() {
		if ($('#photogallery').length) {
			$('#photogallery').attr('class', 'active');
		}

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

	function changeOrderStatus(orderId) {
		let status = $('#selectedOrderStatus').val();
		let paymentMethod = $('#selectedOrderPaymentMethod').val();
		let message = btoa($('#selectedOrderStatusMessage').val());

		if (message.length === 0) {
			message = 0;
		}

		if (status != '0') {
			let sendEmailToCustomer = $('#sendEmailToCustomer').prop('checked') ? 1 : 0
			let url = '/admin/orders/updateStatus/' + orderId + '/' + status + '/' + sendEmailToCustomer + '/' + message + '/' + paymentMethod
			let title = 'Update Status'
			let content = 'Are sure you sure you want to change the order status to <b>"'+status+'"</b>?'
			showConfirmPopup(url, title, content)
		}
	}


	let spinner = `<div class="text-center">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</div>`;

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

</script>

<script>
	// scripts executed after the page load
	$(document).ready(function () {

		try {
			lazyLoadImages();
		} catch (err) {
			console.log('Error - Lazy load images: ', err.message);
		}

		try {
			showServerToastMessages();
		} catch (err) {
			console.log('Error - Toast messages: ', err.message);
		}

		try {
			enableLightBoxForImages()
		} catch (err) {
			console.log('Error - Light box for images: ', err.message);
		}
	});
</script>

