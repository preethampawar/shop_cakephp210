<?php
$this->set('enableTextEditor', true);
echo $this->element('message');
?>
<section>
	<p>
		<?php echo $this->Html->link('&laquo; Back', '/admin/contents/', array('escape' => false)); ?>
	</p>
	<article>
		<header><h2>Edit <?php echo $contentInfo['Content']['title']; ?></h2></header>
		<div class="pageContent">
			<?php
			echo $this->Form->create();
			echo $this->Form->input('Content.active', array('type' => 'checkbox', 'label' => 'Active'));

			echo '<br>';
			echo $this->Form->input('Content.description', array('type' => 'textarea', 'rows' => 20, 'label' => 'Page Description', 'class' => 'tinymce', 'default' => $this->data['Content']['description']));
			echo '<br>';
			echo $this->Form->input('Content.meta_keywords', array('label' => 'Keywords', 'type' => 'text'));
			echo '<br>';
			echo $this->Form->input('Content.meta_description', array('label' => 'Meta Description', 'type' => 'textarea', 'rows' => 1));

			echo '<br><br>';
			echo $this->Form->submit('Save changes');
			echo $this->Form->end();
			?>
		</div>
	</article>
</section>
