<div>
	<?php
	echo $this->Form->create();
	echo $this->Form->input('Store.name', ['label' => 'Store Name', 'required' => true, 'type' => 'text', 'title' => 'Enter Store Name']);
	echo $this->Form->input('Store.user_id', ['label' => 'User', 'required' => true, 'type' => 'select', 'options' => $userInfo]);
	echo $this->Form->submit('Create Store');
	echo $this->Form->end();
	?>
</div>
