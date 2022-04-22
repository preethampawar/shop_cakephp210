<?php
App::uses('Category', 'Model');
$categoryModel = new Category;
$categories = $categoryModel->getCategories();
if(!empty($categories)) {
?>


<section>		
	<div>
		<h2>Select Category</h2>
		<?php
		foreach($categories as $row) {
			$categoryID = $row['Category']['id'];
			$categoryName = Inflector::humanize($row['Category']['name']);
			$categoryNameSlug = Inflector::slug($row['Category']['name'], '-');			
		?>
			<div style="border-bottom: 1px dotted #999; padding:5px 0px;"><?php echo $this->Html->link($categoryName, '/products/show/'.$categoryID.'/'.$categoryNameSlug, array('title'=>$categoryName));?></div>			
		<?php
		}
		?>
	</div>	
	<div style="border-bottom: 1px dotted #999; padding:5px 0px; font-weight:bold;"><?php echo $this->Html->link('&raquo; Show All', '/products/showAll', array('title'=>'Show all products', 'escape'=>false));?></div>
</section>

<?php
}
?>
<!-- /nav -->