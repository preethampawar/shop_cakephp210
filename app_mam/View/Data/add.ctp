<?php echo $this->element('message');?>

<div class="pageContent">
	<h1>Add New Article</h1><hr>
	<?php 
	echo $this->Form->create(null, array('controller'=>'data', 'action'=>'add/'.$encodedCategoryID,  'class'=>'form'));
	?>
	<table width="100%" cellspacing='12'>						
		<tr>
			<td width='200'><label for="PostTitle">Page Title*</label></td>
			<td><?php echo $this->Form->input('Post.title', array('label'=>false, 'required'=>true));?></td>
		</tr>			
		<tr>
			<td>
				<label for="PostKeyword">Link Keyword*</label><br>
				<span class='checkBoxLabelProfAssocProfAssoc'>(Only 'alphanumeric' and '-' characters are allowed)</span>
			</td>
			<td>
				<?php echo $this->Form->input('Post.keyword', array('label'=>false, 'required'=>true));?>				
				<span class='checkBoxLabelProfAssocProfAssoc'>Page Url: <b><?php echo $this->Html->url('/posts/keyword', true);?></b></span>
			</td>
		</tr>		
			
		
		<tr>
			<td><label for="PostBody">Status*</label></td>
			<td>
				<table>
					<tr>
						<td width='50'><?php echo $this->Form->input('Post.active', array('label'=>false, 'empty'=>false, 'default'=>'1')); ?></td>
						<td><label for="PostActive"><span>Active</span></label></td>
					</tr>
				</table>
			</td>
		</tr>		
		<tr>
			<td colspan='2'><label for="PostBody">Page Description*</label>
			<br>
			<?php echo $this->Form->input('Post.body', array('label'=>false, 'type'=>'textarea', 'style'=>'height:500px;'));?></td>
		</tr>
		<tr>
			<td>
				<label for="PostMetaKeywords">Tags</label><br>
				<span class='checkBoxLabelProfAssocProfAssoc'>(Only 'alphanumeric' and ',' characters are allowed)</span>
			</td>
			<td>
				<?php echo $this->Form->input('Post.tags', array('label'=>false, 'type'=>'text'));?>	
			</td>
		</tr>
		<!-- 
		<tr>
			<td>
				<label for="PostMetaKeywords">Meta Keywords</label><br>
				<span class='checkBoxLabelProfAssocProfAssoc'>(Only 'alphanumeric' and ',' characters are allowed)</span>
			</td>
			<td>
				<?php echo $this->Form->input('Post.meta_keywords', array('label'=>false, 'type'=>'text'));?>	
			</td>
		</tr>		
		<tr>
			<td>
				<label for="PostMetaDescription">Meta Description</label><br>
				<span class='checkBoxLabelProfAssocProfAssoc'>(Only 'alphanumeric' and ',' characters are allowed)</span>
			</td>
			<td>
				<?php echo $this->Form->input('Post.meta_description', array('label'=>false, 'type'=>'text'));?>	
			</td>
		</tr>			
		-->
		<tr>
			<td colspan='2'>
				<br>
				<?php
					echo $this->Form->button('Create Article &nbsp;&raquo;', array('class'=>'button grey large'));
				?>
			</td>
		</tr>	
	</table>	

</br></br>
</div>
