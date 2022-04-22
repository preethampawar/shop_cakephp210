<?php
App::uses('Site', 'Model');

$theme = $this->Session->read('Theme');

$navbarTheme = $theme['navbarTheme'];
$secondaryMenuBg = $theme['secondaryMenuBg'];
$linkColor = $theme['linkColor'];
$cartBadgeBg = $theme['cartBadgeBg'];
$hightlightLink = $theme['hightlightLink'];
?>

<style type="text/css">
	.x-small {
		font-size: 0.75rem;
	}

	.cake-sql-log td {
		color: #666;
		font-size: 80%;
		padding: 5px;
		border-top: 1px solid dodgerblue;
	}

	.navbar-light .navbar-nav .nav-link {
		color: var(--cus-dark);
	}
	.nav-tabs .nav-link.active {
		color: var(--cus-dark);
	}

	<?php
	if ($theme['name'] == Site::THEME_WHITE_AND_RED) {
	?>
		h1,h2,h3,h4,h5,h6 {
			color: var(--cus-danger-dark);
		}
		a{
			color: var(--cus-danger-dark);
		}
		a:hover {
			color: var(--bs-danger);
		}
		.navbar-light .navbar-brand{
			color: var(--cus-danger-dark);
		}
		.navbar-light .navbar-brand:hover {
			color: var(--bs-danger);
		}
		.nav-link {
			color: var(--cus-danger-dark);
		}
		.nav-link:focus, .nav-link:hover {
			color: var(--bs-danger);
		}
		.nav-tabs .nav-link.active {
			color: var(--cus-danger-dark);
		}
		.navbar-light .navbar-nav .nav-link {
			color: var(--cus-danger-dark);
		}
		.navbar-light .navbar-nav .nav-link:hover {
			color: var(--bs-danger);
		}

		.navbar-light .navbar-nav .highlight-link {
			color: var(--cus-orange);
		}
	<?php
	}

	if ($theme['name'] == Site::THEME_PURPLE) {
		?>
		h1,h2,h3,h4,h5,h6 {
			color: var(--cus-purple);
		}
		<?php
	}

	if ($theme['name'] == Site::THEME_BLUE) {
		?>
		h1,h2,h3,h4,h5,h6 {
			color: var(--cus-primary-dark);
		}
		<?php
	}

	if ($theme['name'] == Site::THEME_YELLOW) {
		?>
		h1,h2,h3,h4,h5,h6 {
			color: var(--cus-warning-dark);
		}
		<?php
	}

	if ($theme['name'] == Site::THEME_GREEN) {
		?>
		h1,h2,h3,h4,h5,h6 {
			color: var(--cus-success-dark);
		}
		a{
			color: var(--cus-success-dark);
		}
		a:hover {
			color: var(--bs-success);
		}
		.nav-link {
			color: var(--cus-success-dark);
		}
		.nav-link:focus, .nav-link:hover {
			color: var(--bs-success);
		}
		.nav-tabs .nav-link.active {
			color: var(--cus-success-dark);
		}
		<?php
	}

	if ($theme['name'] == Site::THEME_RED) {
		?>
		h1,h2,h3,h4,h5,h6 {
			color: var(--cus-danger-dark);
		}
		a{
			color: var(--cus-danger-dark);
		}
		a:hover {
			color: var(--bs-danger);
		}
		.nav-link {
			color: var(--cus-danger-dark);
		}
		.nav-link:focus, .nav-link:hover {
			color: var(--bs-danger);
		}
		.nav-tabs .nav-link.active {
			color: var(--cus-danger-dark);
		}
		<?php
	}
?>

</style>
