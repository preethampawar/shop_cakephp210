<?php
$assetDomainUrl = Configure::read('AssetDomainUrl');
$type = 'square';
$width = 200;
$height = 200;

if ($this->Session->check('ProductImage')) {
	$type = $this->Session->read('ProductImage.type');
	$width = $this->Session->read('ProductImage.width');
	$height = $this->Session->read('ProductImage.height');
}

$viewportWidth = 200;
$viewportHeight = 200;

if ($type == 'rectangle') {
	$viewportWidth = 200;
	$viewportHeight = 150;
}
if ($type == 'vertical') {
	$viewportWidth = 150;
	$viewportHeight = 200;
}
?>

<link rel="stylesheet" href="/croppie/croppie.css"/>
<script src="/croppie/croppie.js"></script>

<script>
	$(document).ready(function () {
		let updateProductImage;
		const productImageUpdateUrl = '/admin/products/updateImage';
		const imageUploadUrl = "<?php echo $assetDomainUrl; ?>upload.php";
		const imageUploadRelPathDefault = '/<?php echo $this->Session->read('Site.id');?>/unknown';
		let imageUploadProductId = "";


		updateProductImage = function (productId, imagePath, type, commonId, reload = false) {
			if (!commonId) {
				commonId = getRndInteger(1, 10000);
			}

			$.ajax({
				url: productImageUpdateUrl + '/' + productId,
				type: "PUT",
				data: {
					"imagePath": imagePath,
					"imageType": type,
					"commonId": commonId
				},
				success: function (data) {
					console.log('image uploaded - ' + imagePath);

					if (reload) {
						location.reload();
					}
				}
			});
		};


		$image_crop = $('#image_preview').croppie({
			enableExif: true,
			enableResize: false,
			viewport: {
				width: <?= $viewportWidth ?>,
				height: <?= $viewportHeight ?>,
				type: 'square' //circle
			},
			boundary: {
				width: 300,
				height: 300
			},
		});

		$('#upload_image').on('change', function () {
			var reader = new FileReader();
			reader.onload = function (event) {
				$image_crop.croppie('bind', {
					url: event.target.result
				}).then(function () {
					console.log('jQuery bind complete');
				});
			}
			reader.readAsDataURL(this.files[0]);
			$('#uploadimageModal').modal('show');
		});


		$('.crop_image').click(function (event) {
			let imageUploadRelPath = imageUploadRelPathDefault;
			let commonId = getRndInteger(1, 10000);


			if ($('#upload_image').data('imageRelPath')) {
				imageUploadRelPath = $('#upload_image').data('imageRelPath');
			}

			if ($('#upload_image').data('productId')) {
				imageUploadProductId = $('#upload_image').data('productId');
			}

			$("#imageUploadProcessingDiv").removeClass("d-none");
			$("#imageUploadProcessingDiv span").text("1. Uploading large image");

			$(".imageUploadError").addClass("d-none");
			$(".imageUploadError").text("");

			$image_crop.croppie('result', {
				type: 'canvas',
				size: 'original', // can be "viewport" or {"width":800, "height":500},
				format: 'webp',
			}).then(function (response) {
				$.ajax({
					url: imageUploadUrl,
					type: "POST",
					data: {
						"image": response,
						"type": "ori",
						"image_name": imageUploadProductId,
						"relative_path": imageUploadRelPath
					},
					success: function (data) {
						let responseImagePath = data.imagePath;

						updateProductImage(imageUploadProductId, responseImagePath, "ori", commonId);

						$("#imageUploadProcessingDiv span").text("2. Uploading thumbnail image");

						$image_crop.croppie('result', {
							type: 'canvas',
							size: {"width": <?= $width ?>, "height": <?= $height ?>},
							format: 'png',
							quality: 1
						}).then(function (response) {
							$.ajax({
								url: imageUploadUrl,
								type: "POST",
								data: {
									"image": response,
									"type": "thumb",
									"image_name": imageUploadProductId,
									"relative_path": imageUploadRelPath
								},
								success: function (data) {
									responseImagePath = data.imagePath;
									updateProductImage(imageUploadProductId, responseImagePath, "thumb", commonId, true);

									$("#imageUploadProcessingDiv").addClass("d-none");
									$("#imageUploadProcessingDiv span").text("");


									$('#uploadimageModal').modal('hide');
									$('#uploaded_image').html(data);
								}
							});
						})
					},
					error: function (jqXHR, exception) {
						var msg = '';
						if (jqXHR.status === 0) {
							msg = 'Not connect.\n Verify Network.';
						} else if (jqXHR.status == 404) {
							msg = 'Requested page not found. [404]';
						} else if (jqXHR.status == 500) {
							msg = 'Internal Server Error [500].';
						} else if (exception === 'parsererror') {
							msg = 'Requested JSON parse failed.';
						} else if (exception === 'timeout') {
							msg = 'Time out error.';
						} else if (exception === 'abort') {
							msg = 'Request aborted.';
						} else {
							msg = 'Uncaught Error.\n' + jqXHR.responseText;
						}

						$("#imageUploadProcessingDiv").addClass("d-none");
						$("#imageUploadProcessingDiv span").text("");

						$("#imageUploadError").removeClass("d-none");
						$("#imageUploadError").text(msg);
					}

				});
			})
		});

	});
</script>
