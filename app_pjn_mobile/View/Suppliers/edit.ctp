<div>
	<?php
	echo $this->Form->create();
	echo $this->Form->input('Supplier.name', ['label' => 'Supplier Name', 'required' => true, 'title' => 'Enter Supplier Name', 'placeholder' => 'Enter Supplier Name']);
	echo $this->Form->input('Supplier.phone', ['label' => 'Phone No.', 'type' => 'text', 'title' => 'Phone No', 'placeholder' => 'Enter Phone No.']);
	echo $this->Form->input('Supplier.email', ['label' => 'Email Address', 'type' => 'email', 'title' => 'Email Address', 'placeholder' => 'Enter Email Address']);
	echo $this->Form->input('Supplier.address', ['label' => 'Contact Address', 'type' => 'textarea', 'rows' => '2', 'title' => 'Contact Address', 'placeholder' => 'Enter Contact Address']);
	echo $this->Form->submit('Update Supplier');
	echo $this->Form->end();
	?>
</div>
