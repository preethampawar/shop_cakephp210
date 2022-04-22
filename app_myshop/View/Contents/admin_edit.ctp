<?php
$this->set('enableTextEditor', true);
echo $this->element('message');
?>
<section>
	<p>
		<?php echo $this->Html->link('&laquo; Back', '/admin/contents/', ['escape' => false]); ?>
	</p>
	<article>
		<header><h2>Edit Page: <?php echo $contentInfo['Content']['title']; ?></h2></header>
		<div class="pageContent">
			<?php
			echo $this->Form->create();
			echo $this->Form->input('Content.active', ['type' => 'checkbox', 'label' => 'Active']);
			echo '<br>';
			echo $this->Form->input('Content.top_nav_menu', ['type' => 'checkbox', 'label' => 'Show in top navigation menu']);
			echo '<br>';
			echo $this->Form->input('Content.footer_menu', ['type' => 'checkbox', 'label' => 'Show in footer']);
			echo '<br>';
			echo $this->Form->input('Content.title', ['label' => 'Page Title']);
			echo '<br>';
			echo $this->Form->input('Content.description', ['type' => 'textarea', 'rows' => 20, 'label' => 'Page Description', 'class' => 'tinymce', 'default' => $this->data['Content']['description']]);
			echo '<br>';
			echo $this->Form->input('Content.meta_keywords', ['label' => 'Keywords', 'type' => 'text']);
			echo '<br>';
			echo $this->Form->input('Content.meta_description', ['label' => 'Meta Description', 'type' => 'textarea', 'rows' => 1]);
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
