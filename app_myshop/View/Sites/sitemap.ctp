<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<!-- home page -->
	<url>
		<loc><?php echo $this->Html->url('/', true); ?></loc>
		<changefreq>daily</changefreq>
	</url>
	<!-- categories and products list -->
	<?php
	if (!empty($categoryProducts)) {
		$categoriesCount = count($categoryProducts);
		foreach ($categoryProducts as $row) {
			$categoryID = $row['Category']['id'];
			$categoryName = ucwords($row['Category']['name']);
			$categoryNameSlug = Inflector::slug($categoryName, '-');
			?>
			<url>
				<loc><?php echo $this->Html->url('/products/show/' . $categoryID, true); ?></loc>
				<changefreq>daily</changefreq>
			</url>
			<?php
			if (!empty($row['CategoryProducts'])) {
				foreach ($row['CategoryProducts'] as $row2) {
					$productID = $row2['Product']['id'];
					$productName = ucwords($row2['Product']['name']);
					$productNameSlug = Inflector::slug($productName, '-');

					$imageID = 0;
					if (!empty($row2['Images'])) {
						$imageID = (isset($row2['Images'][0]['Image']['id'])) ? $row2['Images'][0]['Image']['id'] : 0;
					}
					?>
					<url>
						<loc><?php echo $this->Html->url('/products/getDetails/' . $categoryID . '/' . $productID . '/' . $productNameSlug, true); ?></loc>
						<changefreq>daily</changefreq>
					</url>
					<?php
				}
			}
		}
	}
	?>
	<!-- content pages -->
	<?php
	App::uses('Content', 'Model');
	$contentModel = new Content;
	$pages = $contentModel->getAllContent();

	if (!empty($pages)) {
		foreach ($pages as $row) {
			$contentID = $row['Content']['id'];
			$contentTitle = $row['Content']['title'];
			$contentTitleSlug = Inflector::slug($row['Content']['title'], '-');
			?>
			<url>
				<loc><?php echo $this->Html->url('/contents/show/' . $contentID . '/' . $contentTitleSlug, true); ?></loc>
				<changefreq>daily</changefreq>
			</url>
			<?php
		}
	}
	?>
	<!-- blog pages -->
	<?php
	App::uses('Blog', 'Model');
	$blogModel = new Blog;
	$conditions = ['Blog.site_id' => $this->Session->read('Site.id')];
	$fields = ['Blog.id', 'Blog.title'];

	$blogs = $blogModel->find('all', ['conditions' => $conditions, 'fields' => $fields, 'order' => ['Blog.created DESC']]);
	$i = 0;
	if (!empty($blogs)) {
		foreach ($blogs as $row) {
			$i++;
			$blogID = $row['Blog']['id'];
			$blogTitle = $row['Blog']['title'];
			$blogTitleSlug = Inflector::slug($blogTitle, '-');
			?>
			<url>
				<loc><?php echo $this->Html->url('/blog/show/' . $blogID . '/' . $blogTitleSlug, true); ?></loc>
				<changefreq>daily</changefreq>
			</url>
			<?php
		}
	}
	?>
	
	<url>
		<loc><?php echo $this->Html->url('/products/showFeatured', true); ?></loc>
		<changefreq>weekly</changefreq>
	</url>
	<url>
		<loc><?php echo $this->Html->url('/products/showAll', true); ?></loc>
		<changefreq>weekly</changefreq>
	</url>
	<url>
		<loc><?php echo $this->Html->url('/sites/about', true); ?></loc>
		<changefreq>weekly</changefreq>
	</url>
	<url>
		<loc><?php echo $this->Html->url('/sites/contact', true); ?></loc>
		<changefreq>weekly</changefreq>
	</url>
	<url>
		<loc><?php echo $this->Html->url('/sites/tos', true); ?></loc>
		<changefreq>monthly</changefreq>
	</url>	
	<url>
		<loc><?php echo $this->Html->url('/sites/privacy', true); ?></loc>
		<changefreq>monthly</changefreq>
	</url>	
	<url>
		<loc><?php echo $this->Html->url('/testimonials', true); ?></loc>
		<changefreq>monthly</changefreq>
	</url>
</urlset>
