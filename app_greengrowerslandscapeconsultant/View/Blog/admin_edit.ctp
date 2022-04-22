<?php 
echo $this->element('text_editor');
echo $this->element('message');
?>
<section>
	<p>
		<?php echo $this->Html->link('&laquo; Back', '/admin/blog/', array('escape'=>false));?>	
	</p>
	<article>		
		<h2>Edit Article: <?php echo $contentInfo['Blog']['title'];?></h2></header>
		<div class="pageContent">
			<?php 
			echo $this->Form->create();
			echo $this->Form->input('Blog.active', array('type'=>'checkbox', 'label'=>'Active'));
			echo '<br>';
			echo $this->Form->input('Blog.title', array('label'=>'Page Title*', 'placeholder'=>'Enter page title', 'required'=>true));
			echo '<br>';
			echo $this->Form->input('Blog.description', array('type'=>'textarea','rows'=>20, 'label'=>'Page Description*', 'class'=>'tinymce', 'default'=>(isset($this->data['Blog']['description'])) ? $this->data['Blog']['description'] : ''));
			echo '<br>';
			echo $this->Form->input('Blog.tags', array('label'=>'Tags - Search keywords seperated by comma "," without any special characters.<br>', 'placeholder'=>'Enter tags(search keywords)', 'type'=>'text'));
			echo '<br>';
			echo $this->Form->input('Blog.meta_keywords', array('label'=>'Keywords - Max 10 words seperated by comma "," without any special characters. (Required for Search Engines. Eg: Google, Bing, Yahoo)', 'type'=>'text', 'placeholder'=>'ex: keyword1, keyword2, keyword3,...'));
			echo '<br>';
			echo $this->Form->input('Blog.meta_description', array('label'=>'Meta Description - Max 150 characters without any special characters. (Required for Search Engines. Eg: Google, Bing, Yahoo)', 'type'=>'textarea', 'rows'=>1, 'placeholder'=>'Enter short description'));
			
			echo '<br><br>';
			echo $this->Form->submit('Save changes');
			echo $this->Form->end();
			?>
		</div>
	</article>
</section>

