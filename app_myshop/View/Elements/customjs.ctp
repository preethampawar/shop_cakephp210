<?php
// this element should contain only php dependent scripts

App::uses('Order', 'Model');
$showLocationPopup = $showLocationPopup ?? false;

$defaultLat = 17.6140;
$defaultLng = 78.0816;
$defaultAddress = 'Sangareddy';
$zoomLevel = 15;
?>

<script>
	var map;
	var geocoder;
	var infoWindow;
	var defaultLat = <?= !empty($defaultLat) ? $defaultLat : 17.6140 ?>;
	var defaultLng =  <?= !empty($defaultLng) ? $defaultLng : 78.0816 ?>;
	var defaultAddress = '<?= !empty($defaultAddress) ? $defaultAddress : 'Sangareddy' ?>';
	var zoomLevel = <?= !empty($zoomLevel) ? $zoomLevel : 15 ?>;
</script>

<script defer>
	// check payment method
	function checkPaymentMethod(element) {
		let paymentMethod = element.value

		if (paymentMethod == '<?= Order::PAYMENT_METHOD_COD ?>') {
			$('#paymentReferenceNoDiv').addClass('disabledElement')
			$('#paymentReferenceNo').removeAttr('required')
			$('#paymentReferenceNo').val('')
		} else {
			$('#paymentReferenceNoDiv').removeClass('disabledElement')
			$('#paymentReferenceNo').attr('required', true)
		}
	}

	$(document).ready(function () {
		<?php
		if($showLocationPopup) {
		?>
		selectLocation();

		if (localStorage.getItem('location')) {
			$('#locationTitleSpan').text(localStorage.getItem('location'))
		}
		<?php
		}
		?>

		<?php if ($this->Session->read('Site.shopping_cart')): ?>
		try {
			loadShoppingCartHeader();
			loadShoppingCart()
		} catch (err) {
			console.log('Error - Shopping cart top nav header: ', err.message);
		}
		<?php endif; ?>

		<?php
		/*
	// show slideshow only in homepage
	$slideshowEnabled = (int)$this->Session->read('Site.show_banners') === 1;

	if($slideshowEnabled && $this->request->params['action'] === 'display' && $this->request->params['pass'][0] === 'home') {
	?>
		try {
			showSlideShowInHomePage();
		} catch (err) {
			console.log('Error - Slide show: ', err.message);
		}
	<?php
	}

	*/
		?>
	});
</script>

