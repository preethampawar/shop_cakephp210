<?php
App::uses('Category', 'Model');
$categoryModel = new Category;
$categories = $categoryModel->getCategories();
if(!empty($categories)) {
?>
<div id="desktopCategoriesMenuDiv">
	<h2>Categories</h2>
	<nav>
		<ul>
			<li><?php echo $this->Html->link('Show All', '/products/showAll', array('title'=>'Show all products', 'style'=>'font-style:italic;'));?></li>
			<?php
			foreach($categories as $row) {
				$categoryID = $row['Category']['id'];
				$categoryName = Inflector::humanize($row['Category']['name']);
				$categoryNameSlug = Inflector::slug($row['Category']['name'], '-');			
			?>
				<li><?php echo $this->Html->link($categoryName, '/products/show/'.$categoryID.'/'.$categoryNameSlug, array('title'=>$categoryName));?></li>			
			<?php
			}
			?>
		</ul>
	</nav>
</div>

<div id="mobileCategoriesMenuDiv">
	<div onclick="$('#mobileCategoryMenu').animate({height: 'toggle'});" style="z-index:10000; cursor:pointer;">
		<div style="float:left;">
			<?php echo $this->Html->image('/img/cat_menu.png', array('width'=>'40', 'height'=>'40', 'alt'=>'Category Menu'));?>
		</div>
		<div style="float:left; font-size:20px; margin:7px 0 0 10px;">
			Categories Menu
		</div>
		<div style="clear:both;"></div>
	</div>
	<div id="mobileCategoryMenu" style="display:none; padding-left:5px;">
		<div style="border-bottom: 1px dotted #999; padding:5px 0px;"><?php echo $this->Html->link('Show All', '/products/showAll', array('title'=>'Show all products', 'style'=>'font-style:italic;'));?></div>
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
	<br><br>
</div>

<?php
}
?>
<!-- /nav -->