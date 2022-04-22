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
			echo $this->element('get_in_contact');	
			echo $this->element('route_map');	
			// echo $this->element('recent_product_visits');
			echo $this->element('recent_blog_posts');	
		}

		?>		
	</aside>

<?php
}
?>