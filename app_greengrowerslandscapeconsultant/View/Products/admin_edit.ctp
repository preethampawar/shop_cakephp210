<?php 	
	echo $this->element('text_editor');
	echo $this->element('message');
?>

<div id="adminAddContent">	
	<section>
		<p>
		<?php 
		if(!empty($categoryID)) {
			echo $this->Html->link('&laquo; Back', '/admin/categories/showProducts/'.$categoryID, array('escape'=>false));
		}
		else {
			echo $this->Html->link('&laquo; Back', '/admin/products/', array('escape'=>false));
		}
		?>	
		&nbsp;|&nbsp;
		<?php echo $this->Html->Link('+ Add New Category', '/admin/categories/add', array('title'=>'Add new category'));?>
		&nbsp;|&nbsp;
		<?php echo $this->Html->Link('+ Add New Product', '/admin/products/add', array('title'=>'Add new product'));?>
		</p>
		<br>
		<h2>Edit Product: <?php echo $productInfo['Product']['name'];?></h2>		
		
		<?php
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categories = $this->Category->admin_getCategoryList();
		if(!empty($categories)) {					
			$categoryOptions = array();
			foreach($categories as $catID=>$categoryName) {				
				$categoryOptions[$catID] = ucwords($categoryName);
			}
			asort($categoryOptions);
			
			echo $this->Form->create();
			echo '<h2>Category Information</h2>';
			echo $this->Form->input('Category.id', array('label'=>false, 'type'=>'select', 'multiple'=>'checkbox', 'options'=>$categoryOptions, 'selected' => $selectedCategories, 'title'=>'Select Category', 'style'=>'', 'div'=>false));
			echo '<br><br><h2>Product Information</h2>';
			?>
			
			<div>			
				<?php 
				if(!empty($productImages)) {
					foreach($productImages as $row) {						
						$imageID = $row['Image']['id'];
						$imageCaption = $row['Image']['caption'];
						$imageHighlight = $row['Image']['highlight'];
						$productID = $row['Product']['id'];
						$productName = $row['Product']['name'];
						$productName = ucwords($row['Product']['name']);
						$productNameSlug = Inflector::slug($productName, '-');
						//echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop'), array('style'=>'margin-right:10px;', 'alt'=>''));
						echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop', 'quality'=>'75', 'filename'=>$productNameSlug), array('style'=>'margin-right:10px;', 'height'=>'150','width'=>'150', 'alt'=>$productName, 'id'=>'image'.$categoryID.'-'.$imageID));
					}
				}
				else {
					echo 'No Images Found';
				}
				
				?> 
			</div>
			<p><b>
			<?php echo $this->Html->link('Click here to Add/Remove Images', '/admin/images/manageProductImages/'.$productInfo['Product']['id'].'/'.$categoryID, array('title'=>'Mange Product Images: '.$productInfo['Product']['name']));?>
			</b></p>
	
			
			<br>
			<?php
			echo $this->Form->input('Product.active', array('label'=>'Is Active', 'title'=>'Status Active', 'style'=>'width:10px; margin-right:5px;', 'after'=>'<br>'));
			echo $this->Form->input('Product.featured', array('label'=>'Featured Product', 'title'=>'Featured Product', 'style'=>'width:10px; margin-right:5px;', 'after'=>'<br>'));
			echo '<br><strong>Product Name</strong>';
			echo $this->Form->input('Product.name', array('label'=>false, 'title'=>'Add new product', 'style'=>'width:800px; margin-right:20px;'));
			
			echo '<br><strong>Product Description</strong>';
			echo $this->Form->input('Product.description', array('label'=>false, 'title'=>'Add new product', 'rows'=>'2', 'style'=>'width:800px; margin-right:20px;', 'class'=>'tinymce'));
			
			echo '<br><strong>Meta Keywords</strong>';
			echo $this->Form->input('Product.meta_keywords', array('label'=>false, 'title'=>'Add new product', 'type'=>'text', 'style'=>'width:800px; margin-right:20px;'));

			echo '<br><strong>Meta Description</strong>';
			echo $this->Form->input('Product.meta_description', array('label'=>false, 'title'=>'Add new product', 'rows'=>'2', 'style'=>'width:800px; margin-right:20px;'));	
			
			
			echo '<br>';
			echo $this->Form->submit('Update &raquo;', array('class'=>'floatLeft', 'escape'=>false));
			echo $this->Form->end();
		?>
		<div class='clear'>&nbsp;</div>
		
		<?php
		}
		else {
		?>
			You need to create a category before you add any product. click <?php echo $this->Html->Link('here', '/admin/categories/add', array('title'=>'Add new category'));?> to create a <?php echo $this->Html->Link('new category', '/admin/categories/add', array('title'=>'Add new category'));?>. 
		<?php
		}
		?>
		
	</section>
</div>