<?php
// conditions to hide advert: if shopping cart is not empty, if in request price quote page, if user is logged in
$shoppingCartIsEmpty = true;
$userIsNotLoggedIn = true;
$inRequestPriceQuotePage = false;
$inLoginPage = false;
$show_ads = false;
$show_landing_page = false;

App::uses('ShoppingCart', 'Model');
$shoppingCartModel = new ShoppingCart;
$shoppingCart = $shoppingCartModel->getShoppingCartProducts();

if (isset($shoppingCart['ShoppingCartProduct']) and !empty($shoppingCart['ShoppingCartProduct'])) {
	$shoppingCartIsEmpty = false;
}

if ($this->Session->read('userLoggedIn')) {
	$userIsNotLoggedIn = false;
}

if ($this->request->params['controller'] == 'RequestPriceQuote') {
	$inRequestPriceQuotePage = true;
}


if (($this->request->params['controller'] == 'users') and ($this->request->params['action'] == 'login')) {
	$inLoginPage = true;
}

if ($shoppingCartIsEmpty and $userIsNotLoggedIn and (!$inRequestPriceQuotePage) and (!$inLoginPage)) {
	if ($this->Session->read('Site.show_ads')) {
		$show_ads = true;
	}
}

if (isset($this->request->params['pass'][0]) and ($this->request->params['pass'][0] == 'home')) {
	if ($this->Session->read('Site.show_landing_page')) {
		$show_landing_page = true;
	}
}

// product details page
$is_product_details_page = false;
if (($this->request['controller'] == 'products') and ($this->request['action'] == 'details')) {
	$is_product_details_page = true;
}

// blog post page
$is_blog_post_page = false;
if (($this->request['controller'] == 'blog') and ($this->request['action'] == 'show')) {
	$is_blog_post_page = true;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	echo $this->fetch('meta');
	echo (isset($customMeta)) ? $customMeta : null;
	echo (isset($facebookMetaTags)) ? $facebookMetaTags : null;
	?>
	<meta property="fb:admins" content="530846121"/>
	<!-- <meta property="fb:admins" content="103762946445270" /> -->

	<title>
		<?php
		if (!empty($title_for_layout)) {
			echo $title_for_layout . ' - ' . $this->Session->read('Site.title');
		} else {
			$siteCaption = $this->Session->read('Site.caption');
			$title_for_layout = $this->Session->read('Site.title');
			$title_for_layout .= (!empty($siteCaption)) ? ' - ' . $siteCaption : '';
			echo $title_for_layout;
		}
		?>
	</title>


	<?php
	echo $this->fetch('css');
	echo $this->fetch('script');
	?>

	<?php
	if ($show_landing_page or $is_product_details_page or $is_blog_post_page) {
		?>
		<link rel="stylesheet" type="text/css"
			  href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" async/>
		<link rel="stylesheet" type="text/css" href="/slick-1.8.0/slick/slick-theme.css" async/>
		<?php
	}
	?>

	<?php
	echo $this->Html->css('styles/layout.min');
	echo $this->Html->css('styles/custom');

	if ($this->Session->read('Site.id') == 70) {
		echo '<link rel="shortcut icon" href="physio2.ico" type="image/x-icon">';
		echo '<style type="text/css">
				.row1 {
					background: darkblue url("/physio_bg2.jpg") repeat scroll 0 0;
					color: #000;
				}
			</style>';
	} else if ($this->Session->read('Site.service_type') == 'Health Service') {
		echo '<link rel="shortcut icon" href="health.ico" type="image/x-icon">';
	}
	?>

	<?php
	echo '<!--[if lt IE 9]>' . $this->Html->script('scripts/html5shiv') . '<![endif]-->';
	?>


	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"
		  async>

	<?php
	if ($show_ads) {
		?>
		<script data-ad-client="ca-pub-1985514378863670" async
				src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

		<!-- <script data-ad-client="ca-pub-1985514378863670" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
		<?php
	}
	?>
</head>
<body>

<style type="text/css">
	#desktopCategoriesMenuDiv {
		display: block;
	}

	#mobileCategoriesMenuDiv {
		display: none;
	}

	pre {
		font-weight: inherit;
		line-height: inherit;
		margin: 3px 0;
	}

	.slidingDiv {
		background-color: #25488f;;
		border-radius: 15px;
		text-align: center;
		padding: 10px 5px;
		color: #ffffff;

	}

	.sliding table {
		width: 100%;
	}

	.sliding {
		#padding: 15px 5px 25px 5px;
	}

	.sliding table td {
		font-size: 15px;
		#font-weight: bold;
		text-align: center;
		vertical-align: middle;
	}

	.row3, .row3 a {
		background-color: inherit;
	}
</style>

<div class="wrapper row1">
	<?php
	echo $this->element('header');
	?>
</div>

<div class="wrapper row2">
	<?php echo $this->element('top_nav_menu'); ?>
</div>
<!-- content -->
<div class="wrapper row3" style='background:#ffffff repeat scroll 0 0;'>
	<div id="container" class="clear">
		<?php echo $this->Session->flash(); ?>

		<?php
		//debug($this->Session->read());
		// Show custom site search widget
		echo $this->element('search_box');
		?>

		<?php
		if ($this->Session->read('Site.description') and (!isset($this->request->params['admin'])) and (isset($this->request->params['pass'][0]) and ($this->request->params['pass'][0] == 'home'))) {
			?>
			<section class="siteDescriptionSection">
				<?php echo $this->Session->read('Site.description'); ?>
			</section>
			<?php
		}
		?>

		<?php
		if ($show_landing_page) {
			echo $this->element('show_landing_page_info');
		}
		?>

		<?php
		/* Show horizontal google ad */
		if ($show_ads) {
			?>
			<!--				<div id="siteHorizontalAdDiv">-->
			<!--					<section>-->
			<!-- site_horizontal_ad1 -->
			<!--						<ins class="adsbygoogle"-->
			<!--							 style="display:block"-->
			<!--							 data-ad-client="ca-pub-1985514378863670"-->
			<!--							 data-ad-slot="8828926812"-->
			<!--							 data-ad-format="auto"></ins>-->
			<!--						<script>-->
			<!--						(adsbygoogle = window.adsbygoogle || []).push({});-->
			<!--						</script>-->
			<!--					</section>-->
			<!--				</div>-->
			<?php
		}
		?>


		<?php
		$showLeftMenu = false;
		$hideLeftMenu = (isset($hideLeftMenu)) ? $hideLeftMenu : null;

		if (!isset($this->request->params['admin'])) {
			$showLeftMenu = true;

			if ($hideLeftMenu) {
				$showLeftMenu = false;
			}
		}
		?>

		<?php
		// hide in request price quote page
		if (!$inRequestPriceQuotePage) {
			?>
			<!-- show this cart when in mobile view port -->
			<div class="mobileShoppingCartDiv">
				<?php echo ($this->Session->read('Site.request_price_quote')) ? $this->element('myshoppinglist_left_menu') : null; ?>
			</div>
			<?php
		}
		?>

		<?php
		if ($showLeftMenu) {
			?>
			<!-- left menu -->
			<?php echo $this->element('left_menu'); ?>
			<!-- / left menu -->

			<!-- content body -->
			<div id="content">
				<?php echo $this->fetch('content'); ?>
			</div>
			<!-- / content body -->
			<?php
		} else {
			?>
			<!-- content body -->
			<?php echo $this->fetch('content'); ?>
			<!-- / content body -->
			<?php
		}
		?>

		<?php
		if (!$this->Session->read('isMobile')) {
			if (isset($this->request->params['pass'][0]) and ($this->request->params['pass'][0] == 'home')) {
				if (!$this->Session->check('User.id')) {
					?>
					<div style="clear:both;"></div>
					<div>
						<div style="float:right; margin:0 0 0 5px;">
							<!-- Your like button code -->
							<div class="fb-like" data-href="<?php echo $this->Html->url('/', true); ?>"
								 data-layout="button_count" data-action="like" data-show-faces="true"
								 data-share="true"></div>
						</div>
						<div style="float:right; margin:0 0 0 5px;">
							<g:plusone annotation="bubble" size="standard"></g:plusone>
						</div>
						<div style="clear:both;"></div>
					</div>
					<?php
				}
			}
		}
		?>

		<?php
		/* Show horizontal google ad */
		if ($show_ads) {
			?>
			<!--				<div style="clear:both;"></div>-->
			<!--				<div class="siteHorizontalAdDiv">-->
			<!-- horizontal - full width - site bottom - ad - *.enursery.in -->
			<!--					<ins class="adsbygoogle"-->
			<!--						 style="display:block"-->
			<!--						 data-ad-client="ca-pub-1985514378863670"-->
			<!--						 data-ad-slot="9250632010"-->
			<!--						 data-ad-format="auto"></ins>-->
			<!--					<script>-->
			<!--					(adsbygoogle = window.adsbygoogle || []).push({});-->
			<!--					</script>-->
			<!--				</div>-->
			<!--				<div style="clear:both;"></div>-->
			<?php
		}
		?>
	</div>
</div>
<!-- / content body -->
<!-- Footer -->
<?php
if (!isset($this->request->params['admin'])) {
	if (!$this->Session->read('Site.under_maintenance')) {
		?>
		<div class="wrapper row4">
			<div id="footer" class="clear">
				<?php
				echo $this->element('footer');
				?>
				<div class="clear"></div>
				<hr>
				<div>
					<div style="text-align:center;">
						<a href="<?php echo $this->Html->url('/privacypolicy.htm'); ?>" target='_blank'>Privacy
							Policy</a>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="<?php echo $this->Html->url('/tos.htm'); ?>" target='_blank'>Terms of Service</a>
					</div>
					<div style="text-align:center;">
						Copyright &copy; <?php echo date('Y'); ?> - All Rights
						Reserved <?php echo $this->Html->link($this->request->host(), $this->Html->url('/', true)); ?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<?php
	}
}
?>
<!-- / Footer -->
<?php /* Request Price Quote Form */ ?>
<div style="display:none; font-size:12px;" id="RPQF-Div">
	<h2 id="RPQF-ProductName" style="font-size:13px;"></h2>
	<br>
	<?php echo $this->Form->create(null, ['id' => 'RPQF']); ?>
	<div class="floatLeft" style="width:100px; margin:5px 10px 0 0;">
		<?php
		$qtyOptions = Configure::read('Product.quantity');
		echo $this->Form->input('ShoppingCartProduct.quantity', ['options' => $qtyOptions, 'empty' => false]);
		?>
	</div>
	<div class="floatLeft" style="width:100px; margin:5px 10px 0 0;">
		<?php
		$sizeOptions = Configure::read('Product.size');
		echo $this->Form->input('ShoppingCartProduct.size', ['options' => $sizeOptions, 'empty' => '-']);
		?>
	</div>
	<div class="floatLeft" style="width:150px; margin:5px 10px 0 0;">
		<?php
		$ageOptions = Configure::read('Product.age');
		echo $this->Form->input('ShoppingCartProduct.age', ['options' => $ageOptions, 'empty' => '-']);
		?>
	</div>
	<div class="floatLeft" style="margin:5px 10px 0 0;">
		<br>
		<?php echo $this->Form->submit('Submit &raquo;', ['escape' => false]); ?>
	</div>
	<div class='clear'></div>
	<?php echo $this->Form->end(); ?>
</div>
<?php /** --> End of Request Price Quote Form */ ?>


<!-- Copyright -->
<div class="wrapper row5">

	<footer id="copyright" class="clear">
		<p class="fl_right"><br>Powered
			by <?php echo $this->Html->link(Configure::read('BaseDomain'), Configure::read('BaseDomainUrl'), ['title' => Configure::read('BaseDomain'), 'style' => 'padding:2px; border:1px solid #888;', 'target' => '_blank', 'rel' => "noopener"]); ?>
		</p>
	</footer>
</div>
<!-- / Copyright -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<?php
if ($show_landing_page or $is_product_details_page or $is_blog_post_page) {
	?>
	<script type="text/javascript"
			src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
	<?php
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<?php
if (isset($enableLightbox) && $enableLightbox) {
	echo $this->Html->css('jquery.lightbox-0.5'); // jQuery Light box
	echo $this->Html->script('jquery.lightbox-0.5'); // jQuery Light box
	?>
	<script>
		$(document).ready(function () {
			if ($('#productImages a').length) {
				$('#productImages a').lightBox();
			}

			if ($('#photogallery').length) {
				$('#photogallery').attr('class', 'active');
			}

			if ($('#contentImages a').length) {
				$('#contentImages a').lightBox();
			}
		});
	</script>
	<?php
}
?>

<script type="text/javascript">
	$(document).ready(function () {
		if ($('.landing-page-slider').length) {
			$('.landing-page-slider').slick({
				dots: true,
				infinite: true,
				speed: 1000,
				slidesToShow: 1,
				centerMode: false,
				autoplay: true,
				autoplaySpeed: 3000,
				lazyLoad: 'ondemand',
				mobileFirst: true,
				fade: true,
				cssEase: 'linear'
			});
		}

		if ($('.sliding').length) {
			$('.sliding').slick({
				dots: false,
				infinite: true,
				speed: 1000,
				slidesToShow: 1,
				centerMode: false,
				autoplay: true,
				autoplaySpeed: 3000,
				lazyLoad: 'ondemand',
				mobileFirst: true,
				responsive: [
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							infinite: true,
						}
					},
					{
						breakpoint: 600,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
				]
			});
		}

		if ($("img.lazy").length) {
			$("img.lazy").lazyload({
				effect: "fadeIn"
			});
		}
	});
</script>

<?php
echo $this->element('customjs');
?>

<?php
// enable text editor
if (isset($enableTextEditor) && $enableTextEditor) {
	echo $this->element('text_editor');
}
?>

<!-- share this lib -->
<!-- <script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5aa8b3691323eb0013e8621c&product=sticky-share-buttons' async='async'></script> -->
<!--
<script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=5aa8b7a81323eb0013e8621f&product=sticky-share-buttons"></script>
-->
<?php
if (!$this->Session->check('User.id')) {
	echo $this->Session->read('Site.analytics_code');
}
?>
</body>
</html>

