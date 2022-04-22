<section>
	<article>
		<header><h2>Blog</h2></header>
		<p>
			<?php echo $this->Html->link('+ Add New Article', '/admin/blog/add', array('escape'=>false, 'class'=>''));?>	
		</p>
		<div class="pagesContent">
		<?php 
		if(!empty($contents))
		{
			$i=1;
		?>
		<table class="table">
			<thead>
				<tr>
					<th style="width:30px">Sl.No.</th>
					<th>Article Title</th>
					<th style="width:150px;">Created on</th>
					
					<th style="width:100px">Status</th>		
					<th style="width:100px">Actions</th>
				</tr>
			</thead>
			<tbody>				
			<?php 
			foreach($contents as $row) {
				
				$blogID = $row['Blog']['id'];
				$blogTitle = $row['Blog']['title'];
				$blogCreatedOn = date('D, d-m-Y', strtotime($row['Blog']['created']));
				$blogActive = $row['Blog']['active'];
				
				$class = ($blogActive) ? 'colorGreen' : 'colorRed';
				?>
				<tr>
					<td style="text-align:center;"><?php echo $i;?>.</td>
					<td>
						<?php 	
						echo $this->Html->link("<strong>$blogTitle</strong>", '/admin/blog/edit/'.$blogID, array('escape'=>false, 'style'=>'text-decoration:none;'));			
						?>	
					</td>
					<td><?php echo $blogCreatedOn;?></td>
					<td style="text-align:center;" class="<?php echo $class;?>">
						<?php 					
						if($blogActive)
						{
							echo $this->Html->link('Active', '/admin/blog/activate/'.$blogID.'/false', array('escape'=>false, 'style'=>'color:green'), 'Are you sure you want to deactivate this article? Deactivating will hide this article from public.');
						}
						else
						{
							echo $this->Html->link('Inactive', '/admin/blog/activate/'.$blogID.'/true', array('escape'=>false, 'style'=>'color:red;'), 'Are you sure you want to make this article to public?');
						}						
						?>
					</td>
					
					<td style="text-align:center;">
						<?php 	
						echo $this->Html->link('Edit', '/admin/blog/edit/'.$blogID, array('escape'=>false, 'style'=>'text-decoration:none;', 'title'=>'Edit page'));			
						echo '&nbsp;|&nbsp;';
						echo $this->Html->link('X', '/admin/blog/delete/'.$blogID, array('escape'=>false, 'style'=>'color:red', 'title'=>'Delete Page'), 'Are you sure you want to remove this article?');			
						?>		
					</td>
				</tr>
				<?php
				$i++;
			}
			?>
			</tbody>
		</table>
			<?php
		}
		else{ 
			echo "<br> - No articles found";
		}
		?>
		</div>
	</article>
</section>

