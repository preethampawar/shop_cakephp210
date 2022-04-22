<div style="height:200px;">

	<h4>This website is under maintenance. Please check back soon.</h4>

	<p>
	<?php
	if ($this->Session->check('User.id') && $this->Session->read('User.type') === 'seller') {
		?>
			<a href="/users/setView/seller" class="btn btn-sm btn-orange mt-4">Switch to Seller View</a>
		<?php
	} else {
		?>
			If you are an admin then <a href="/users/login">click here to login</a>
		<?php
	}
	?>
	</p>

</div>
