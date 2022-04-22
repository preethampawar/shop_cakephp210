<div>	
	<section id="ProductInfo">		
		<article>
			<header>
				<h2>Products List</h2>
			</header>
				
				
				<p class='floatLeft'><?php echo $this->Html->Link('+ Add New Product', '/admin/products/add');?></p>
				<div class='clear'></div>
				<?php
				if(!empty($products)) {
				?>
					<table class='table'>
						<thead>
							<tr>
								<th style="width:20px;">Sl.No.</th>
								<th>Product Name</th>
								<th style="width:350px;">Category</th>
								<th style="width:100px;">Featured</th>
								<th style="width:150px;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$categoryID = null;
							foreach($products as $row) {
							
								$i++;
								$productID = $row['Product']['id'];
								$productName = ucwords($row['Product']['name']) ;
								$productActive = ($row['Product']['active']) ? true : false;
								$productFeatured = ($row['Product']['featured']) ? true : false;
								
								$categoryLinks = array();
								if(!empty($row['CategoryProduct'])) {
									$categories = array();
									foreach($row['CategoryProduct'] as $row2) {
										if(isset($row2['Category']['id']) and !empty($row2['Category']['id'])) {
											$categoryID = $row2['Category']['id'];
											$categoryName = $row2['Category']['name'];
											$categories[$categoryID] = ucwords($categoryName);
										}
									}
									if(!empty($categories)) {
										asort($categories);
										foreach($categories as $categoryID=>$categoryName) {
											$categoryLinks[] = $this->Html->link($categoryName, '/admin/categories/showProducts/'.$categoryID);
										}
									}
								}
								$categoryLinks = implode(', ', $categoryLinks);
								
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
									echo $this->Html->link($productName, '/admin/products/edit/'.$productID, array('title'=>$productName));
									?>
								</td>
								<td>
									<?php echo $categoryLinks;?>
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
									<?php echo $this->Html->link('Edit', '/admin/products/edit/'.$productID, array('title'=>'Edit Product: '.$productName));?>
									|	
									<?php echo $this->Html->link('Images', '/admin/images/manageProductImages/'.$productID, array('title'=>'Mange Product Images: '.$productName));?>
									|
									<?php echo $this->Html->link($this->Html->image('error.png', array('alt'=>'active', 'title'=>'Click to remove this product')), '/admin/products/deleteProduct/'.$productID, array('escape'=>false, 'style'=>'color:red;', 'title'=>'Delete Product: '.$productName), 'This product may belong to different categories and will be deleted from all. Are you sure you want to delete this product - '.$productName.'?');?>
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
		</article>
	</section>
</div>	