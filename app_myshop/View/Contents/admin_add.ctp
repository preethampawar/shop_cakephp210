<?php
$this->set('enableTextEditor', true);
echo $this->element('message');
?>
<section>
	<p>
		<?php echo $this->Html->link('&laquo; Back', '/admin/contents/', ['escape' => false]); ?>
	</p>
	<article>
		<header><h2>Add New Page</h2></header>
		<div class="pageContent">
			<?php
			echo $this->Form->create();
			echo $this->Form->input('Content.active', ['type' => 'checkbox', 'label' => 'Active', 'checked' => 'checked']);
			echo '<br>';
			echo $this->Form->input('Content.top_nav_menu', ['type' => 'checkbox', 'label' => 'Show in top navigation menu', 'checked' => 'checked']);
			echo '<br>';
			echo $this->Form->input('Content.footer_menu', ['type' => 'checkbox', 'label' => 'Show in footer', 'checked' => 'checked']);
			echo '<br>';
			echo $this->Form->input('Content.title', ['label' => 'Page Title', 'placeholder' => 'Enter page title']);
			echo '<br>';
			echo $this->Form->input('Content.description', ['type' => 'textarea', 'rows' => 20, 'label' => 'Page Description', 'class' => 'tinymce', 'default' => (isset($this->data['Content']['description'])) ? $this->data['Content']['description'] : '']);
			echo '<br>';
			echo $this->Form->input('Content.meta_keywords', ['label' => 'Keywords - Max 10 words seperated by comma "," without any special characters. (Required for Search Engines. Eg: Google, Bing, Yahoo)', 'type' => 'text', 'placeholder' => 'ex: keyword1, keyword2, keyword3,...']);
			echo '<br>';
			echo $this->Form->input('Content.meta_description', ['label' => 'Meta Description - Max 150 characters without any special characters. (Required for Search Engines. Eg: Google, Bing, Yahoo)', 'type' => 'textarea', 'rows' => 1, 'placeholder' => 'Enter short description']);
			echo '<br>';
			echo $this->Form->input('Content.priority', ['type' => 'number', 'default' => $pagePriority, 'label' => 'Set Priority  <br>', 'style' => 'width:100px;']);
			echo '(1,2,3,...etc. "1": highest priority, "2": lower priority than "1", "3": lower priority than "2". Priority is set in ascending order.)';
			echo '<br><br>';
			echo 'Note: Top navigation links are sorted based on priority. Page with priority="1" is given highest priority and is shown 1st. Page with priority="2" is shown second in the navigation.';
			echo '<br><br>';
			echo $this->Form->submit('Save changes');
			echo $this->Form->end();
			?>
		</div>
	</article>
</section>

