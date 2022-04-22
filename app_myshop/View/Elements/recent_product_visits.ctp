<?php
App::uses('ProductVisit', 'Model');
$this->ProductVisit = new ProductVisit;
$products = $this->ProductVisit->getRecentProductViewsByUser();

if (!empty($products)) {
	?>
	<section>
		<h2>Recent Visits</h2>
		<div>
			<?php
			foreach ($products as $row) {
				$productID = $row['Product']['id'];
				$productName = $row['Product']['name'];
				$productNameSlug = Inflector::slug($productName, '-');

				$categoryID = $row['Category']['id'];
				$categoryName = $row['Category']['name'];
				$categoryNameSlug = Inflector::slug($categoryName, '-');

				$imageID = (isset($row['Image']['id'])) ? $row['Image']['id'] : 0;
				$imageCaption = $categoryName . ' &raquo; ' . $productName;
				?>
				<div style="float:left; margin:0 5px 5px 0px; padding:4px; border:1px solid #ddd;">
					<?php
					$image = $this->Img->showImage('img/images/' . $imageID, ['height' => '60', 'width' => '60', 'type' => 'crop', 'quality' => '50', 'filename' => $productNameSlug], ['style' => '', 'alt' => $productName, 'title' => $imageCaption, 'escape' => false]);
					$link = '/products/details/' . $categoryID . '/' . $productID . '/' . $categoryNameSlug . '/' . $productNameSlug;

					echo $imageLink = $this->Html->link($image, $link, ['escape' => false, 'title' => $categoryName . ' &raquo; ' . $productName . ': ' . $imageCaption]);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<div style="clear:both;"></div>
	</section>
	<?php
}
?>
