<?php
$this->set('enableTextEditor', true);
echo $this->element('message');
?>
<section>
	<p>
		<?php echo $this->Html->link('&laquo; Back', '/admin/blog/', ['escape' => false]); ?>
	</p>
	<article>
		<header><h2>Add New Page</h2></header>
		<div class="pageContent">
			<?php
			echo $this->Form->create();
			echo $this->Form->input('Blog.active', ['type' => 'checkbox', 'label' => 'Active']);
			echo '<br>';
			echo $this->Form->input('Blog.title', ['label' => 'Page Title*', 'placeholder' => 'Enter page title', 'required' => true]);
			echo '<br>';
			echo $this->Form->input('Blog.description', ['type' => 'textarea', 'rows' => 20, 'label' => 'Page Description*', 'class' => 'tinymce', 'default' => (isset($this->data['Blog']['description'])) ? $this->data['Blog']['description'] : '']);
			echo '<br>';
			echo $this->Form->input('Blog.tags', ['label' => 'Tags - Search keywords seperated by comma "," without any special characters.<br>', 'placeholder' => 'Enter tags(search keywords)', 'type' => 'text']);
			echo '<br>';
			echo $this->Form->input('Blog.meta_keywords', ['label' => 'Keywords - Max 10 words seperated by comma "," without any special characters. (Required for Search Engines. Eg: Google, Bing, Yahoo)', 'type' => 'text', 'placeholder' => 'ex: keyword1, keyword2, keyword3,...']);
			echo '<br>';
			echo $this->Form->input('Blog.meta_description', ['label' => 'Meta Description - Max 150 characters without any special characters. (Required for Search Engines. Eg: Google, Bing, Yahoo)', 'type' => 'textarea', 'rows' => 1, 'placeholder' => 'Enter short description']);

			echo '<br><br>';
			echo $this->Form->submit('Save changes');
			echo $this->Form->end();
			?>
		</div>
	</article>
</section>

