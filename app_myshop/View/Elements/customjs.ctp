<script>
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
		});

		return response.json(); // parses JSON response into native JavaScript objects
	}

	// GET page implementation:
	async function getPage(url = '', data = {}) {
		const response = await fetch(url + '?isAjax=1');

		return response.text(); // parses into html
	}

	// GET data implementation:
	async function getData(url = '', data = {}) {
		const response = await fetch('http://example.com/movies.json');

		return response.json(); // parses JSON response into native JavaScript objects
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
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		});
	}

	// side bar menu to show categories
	function initSideBarMenu() {
		// Open navbarSide when button is clicked
		$('.productSideBar').on('click', function () {
			$('#navbarSide').addClass('reveal');
			$('.overlay').show();
		});

		// Close navbarSide when the outside of menu is clicked
		$('.overlay').on('click', function () {
			$('#navbarSide').removeClass('reveal');
			$('.overlay').hide();
		});
	}

	// show toast messages
	function showToastMessages() {
		var toastElList = [].slice.call(document.querySelectorAll('.toast'))
		var toastList = toastElList.map(function (toastEl) {
			return new bootstrap.Toast(toastEl)
		});

		toastList.forEach(toast => toast.show());
	}

	// load shopping cart in top nav
	function loadShoppingCart() {
		let topNavCartUrl = '/shopping_carts/loadTopNavCart';
		const data = getPage(topNavCartUrl);
		data.then(function (response) {
			$("#topNavShoppingCart").html(response);
		});
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
</script>

<script>
	// scripts executed after the page load
	$(document).ready(function () {
		try {
			initSideBarMenu();
		} catch (err) {
			console.log('Error - Sidebar categories menu', err.message);
		}

		<?php if ($this->Session->read('Site.shopping_cart')): ?>
		try {
			loadShoppingCart();
		} catch (err) {
			console.log('Error - Shopping cart top nav: ', err.message);
		}
		<?php endif; ?>

		try {
			lazyLoadImages();
		} catch (err) {
			console.log('Error - Lazy load images: ', err.message);
		}

		try {
			showToastMessages();
		} catch (err) {
			console.log('Error - Toast messages: ', err.message);
		}

		try {
			enableLightBoxForImages()
		} catch (err) {
			console.log('Error - Light box for images: ', err.message);
		}

		try {
			initTooltips();
		} catch (err) {
			console.log('Error - Bootstrap tooltips: ', err.message);
		}
	});
</script>

