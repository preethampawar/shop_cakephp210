<?php
if($this->Session->read('Site.show_products')) {
?>
	<aside id="left_column">

		<?php
		if($this->Session->read('Site.request_price_quote')) {
			echo $this->element('myshoppinglist_left_menu');
		}
		echo $this->element('categories_menu');

		if($this->Session->read('isMobile') == false) {

			// disable ads if user is logged in
			if(!$this->Session->check('User.id'))
			{
			/*
			?>
				<div style="margin:0px 0px 0px 0px; padding:0px;">
					<!-- Left menu full width AD - *.enursery.in -->
					<ins class="adsbygoogle"
						 style="display:inline-block;width:300px;height:250px"
						 data-ad-client="ca-pub-1985514378863670"
						 data-ad-slot="2473820142"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
					<br><br>
				</div>
			<?php
			*/
			}

			echo $this->element('get_in_contact');
			echo $this->element('route_map');
			// echo $this->element('recent_product_visits');
			// echo $this->element('recent_blog_posts');
		}

		?>

		<!--
		<section>
			<article>
			  <h2>Lorem ipsum dolor</h2>
			  <p>Nuncsed sed conseque a at quismodo tris mauristibus sed habiturpiscinia sed.</p>
			  <ul>
				<li><a href="#">Lorem ipsum dolor sit</a></li>
				<li>Etiam vel sapien et</li>
				<li><a href="#">Etiam vel sapien et</a></li>
			  </ul>
			  <p>Nuncsed sed conseque a at quismodo tris mauristibus sed habiturpiscinia sed. Condimentumsantincidunt dui mattis magna intesque purus orci augue lor nibh.</p>
			  <p class="more"><a href="#">Continue Reading &raquo;</a></p>
			</article>
		</section>
		-->
	</aside>

<?php
}
?>
