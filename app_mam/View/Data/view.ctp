<?php echo $this->Html->link('Add Post', array('controller' => 'posts', 'action' => 'add')); ?>

<h1><?php echo $post['Post']['title']?></h1>

<p><small>Created: <?php echo $post['Post']['created']?></small></p>

<p><?php echo $post['Post']['body']?></p>