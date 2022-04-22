<aside id="admin_left_column">	
	<?php echo $this->element('admin_categories_menu');?>		
	
</aside>
<div id="content" class='content'>
	<section>
		<article>
			<header>
				<h2>Category: <?php echo $categoryInfo['Category']['name'];?></h2>
			</header>
				
				<h3 class="floatLeft">Products List</h3>
				<p class='floatRight'><?php echo $this->Html->Link('+ Add New Product', '/admin/products/add');?></p>
				<div class='clear'></div>
				<?php
				if(!empty($categoryProducts)) {
				?>
					<table class='table'>
						<thead>
							<tr>
								<th style="width:20px;">Sl.No.</th>
								<th>Product Name</th>
								<th style="width:100px;">Featured</th>
								<th style="width:120px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$categoryID = $categoryInfo['Category']['id'];
							foreach($productsList as $productID=>$productName) {
								$i++;
								$productActive = $categoryProducts[$productID]['Product']['active'];
								$productFeatured = $categoryProducts[$productID]['Product']['featured'];
							?>
							<tr>
								<td><?php echo $i;?></td>
								<td>
									<?php 
										if($productActive){
											echo $this->Html->link($this->Html->image('round_button_green.jpg', array('alt'=>'active', 'title'=>'Click to deactivate', 'width'=>'16')), '/admin/products/setInactive/'.$productID, array('style'=>'color:green;', 'escape'=>false), 'Are you sure you want to deactivate this product? Deactivating will hide this product from public.');
										}
										else {
											echo $this->Html->link($this->Html->image('red_button.png', array('alt'=>'active', 'title'=>'Click to activate', 'height'=>'12', 'width'=>'12')), '/admin/products/setActive/'.$productID, array('escape'=>false, 'style'=>'color:red; margin:2px;'), 'Are you sure you want to activate this product? Activating will make this product available to public.');
										}
									?>		
									&nbsp;	
									<?php 	
									echo $this->Html->link($productName, '/admin/products/edit/'.$productID.'/'.$categoryID, array('title'=>$productName));
									?>
								</td>
								<td style="text-align:center;">
									<?php 
										if($productFeatured){
											echo $this->Html->link($this->Html->image('test-pass-icon.png', array('alt'=>'active', 'title'=>'Click to set this product as featured', 'width'=>'16')), '/admin/products/unsetFeatured/'.$productID, array('style'=>'color:green;', 'escape'=>false), 'Are you sure you want to remove this product from featured product list?');
										}
										else {
											echo $this->Html->link($this->Html->image('test-fail-icon.png', array('alt'=>'active', 'title'=>'Click to remove this product from featured product list', 'height'=>'12', 'width'=>'12')), '/admin/products/setFeatured/'.$productID, array('escape'=>false, 'style'=>'color:red; margin:2px;'), 'Are you sure you want to set this product as featured? Doing so, will show this product in featured list.');
										}
									?>	
								</td>
								<td style="text-align:center;">
									<?php echo $this->Html->link('Edit', '/admin/products/edit/'.$productID.'/'.$categoryID, array('title'=>'Edit Product: '.$productName));?>									
									|	
									<?php echo $this->Html->link('Images', '/admin/images/manageProductImages/'.$productID.'/'.$categoryID, array('title'=>'Mange Product Images: '.$productName));?>
									|
									<?php echo $this->Html->link($this->Html->image('error.png', array('alt'=>'active', 'title'=>'Click to remove this product')), '/admin/products/deleteProduct/'.$productID.'/'.$categoryID, array('escape'=>false, 'style'=>'color:red;', 'title'=>'Delete Product: '.$productName), 'Are you sure you want to delete this product - '.$productName.'?');?>
								</td>
							</tr>								
							<?php
							}
							?>
						</tbody>
					</table>
				
				<?php
				}
				?>
				<?php //debug($categoryProducts);?>					
		</article>
		
	</section>
</div>