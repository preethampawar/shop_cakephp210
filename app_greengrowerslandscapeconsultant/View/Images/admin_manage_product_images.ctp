<?php echo $this->element('message');?>
<div id="manageImages">
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
		</p>
		<br>
		<header>
			<h2>Manage Images: <?php echo $productInfo['Product']['name'];?></h2>
		</header>
		<div style="width:600px;">
		<?php 
		echo $this->Form->create(null, array('type'=>'file'));
		echo $this->Form->input('Image.file', array('type'=>'file', 'label'=>'Select Image'));
		echo '<br>';
		echo '<br>';
		echo $this->Form->input('Image.highlight', array('label'=>'Highlight Image (This image will be set as profile image for the product)'));
		echo '<br>';
		echo $this->Form->input('Image.caption', array('label'=>'Caption'));
		echo '<br>';
		echo $this->Form->submit('Submit &nbsp;&raquo;', array('escape'=>false));
		echo $this->Form->end();
		?>
		</div>	
	</section>
	<br><hr><br>
	<section>
		<header><h2><?php echo $productInfo['Product']['name'];?>: Image List</h2></header>
		<div>			
			<?php 
			if(!empty($productImages)) {
				?>
				<table class='table'>
					<thead>
						<tr>
							<th style='width:30px;'>Sl.No.</th>
							<th style='width:150px;'>Image</th>
							<th>Caption</th>
							<th style='width:130px;'>Actions</th>
						</tr>
					</thead>
					<tbody>
				<?php
				$i=0;
				foreach($productImages as $row) {
					$i++;
					$imageID = $row['Image']['id'];
					$imageCaption = $row['Image']['caption'];
					$imageHighlight = $row['Image']['highlight'];
					$productID = $row['Product']['id'];
					$productName = $row['Product']['name'];
					$productName = ucwords($row['Product']['name']);
					$productNameSlug = Inflector::slug($productName, '-');
					?>
					<tr>
						<td><?php echo $i;?>.</td>
						<td>							
							<?php echo $this->Img->showImage('img/images/'.$imageID, array('height'=>'150','width'=>'150','type'=>'crop', 'quality'=>'75', 'filename'=>$productNameSlug), array('style'=>'', 'height'=>'150','width'=>'150', 'alt'=>$productName, 'id'=>'image'.$categoryID.'-'.$imageID));?>
						</td>
						<td style="vertical-align:top; padding-left:20px;">
							<p><strong>Caption:</strong></p>
							<p><?php echo $imageCaption;?></p>
						</td>
						<td style="text-align:center;">
							<?php
							if(!$imageHighlight) {
								echo $this->Html->link('Highlight', '/admin/images/highlight/'.$imageID.'/'.$productID.'/'.$categoryID);
								echo '&nbsp;|&nbsp;';
							}
							echo $this->Html->link('Delete', '/admin/images/deleteImage/'.$imageID, array(), 'Are you sure you want to delete this image?');							
							?>
						</td>
					</tr>	
				<?php			
				}		
				?>
					</tbody>
				</table>	
				<?php
			}
			else {
				echo 'No Images Found';
			}
			
			?> 
		</div>
	</section>
</div>