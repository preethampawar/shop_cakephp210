<?php 
App::uses('ProductVisit', 'Model');
$this->ProductVisit = new ProductVisit;
$products = $this->ProductVisit->getMostViewedProducts();

if(!empty($products)) {
?>
<section  class="two_quarter lastbox">
	<h2 class="title">Most Viewed Products</h2>
	<div>
		
		
		<?php
		foreach($products as $row) {				
			$visitCount = $row['ProductVisit']['visit_count'];
			$productID = $row['Product']['id'];
			$productName = $row['Product']['name'];
			$productNameSlug = Inflector::slug($productName, '-');
			
			$categoryID = $row['Category']['id'];
			$categoryName = $row['Category']['name'];
			$categoryNameSlug = Inflector::slug($categoryName, '-');
						
			$imageID = (isset($row['Image']['id'])) ? $row['Image']['id'] : 0;							
			$imageCaption = $categoryName.' &raquo; '.$productName.' : '.$visitCount.' view(s)';
		?>
			<div style="float:left; margin:0 5px 8px 0;">
				<?php
				$productImageUrl = $this->Html->url($this->Img->showImage('img/images/'.$imageID, array('height'=>'60','width'=>'60','type'=>'crop', 'quality'=>'75', 'filename'=>$productNameSlug), array('style'=>'', 'height'=>'60' ,'width'=>'60', 'alt'=>$productName, 'title'=>$imageCaption, 'escape'=>false), true), true);
				$imageTagId = 'image'.$categoryID.'-'.$imageID;
				$image = '<img 
						data-original="'.$productImageUrl.'" 
						height="60" 
						width="60" 
						class="lazy" 
						alt="'.$productName.'"
						title="'.$productName.'"
						id="'.$imageTagId.'" 
					/>';
				$link = '/products/details/'.$categoryID.'/'.$productID.'/'.$categoryNameSlug.'/'.$productNameSlug;
				
				echo $imageLink = $this->Html->link($image, $link, array('escape'=>false, 'title'=>$categoryName.' &raquo; '.$productName.': '.$imageCaption));
				?><br>
				<div style="text-align:center; font-size:80%;">(<?php echo $visitCount;?>)</div>
			</div>
		<?php
		}
		?>
	</div>	
</section>
<?php
}
?>