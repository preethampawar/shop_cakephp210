<h1>Update User Account</h1>
<br>
<div class="row">
    <?php
    echo $this->Form->create('User');
    echo $this->Form->input('name', array('label'=>'Full Name', 'placeholder'=>'Enter full name', 'class'=>'form-control input-sm'));
    echo $this->Form->input('email', array('label'=>'Email Address', 'placeholder'=>'Enter email address', 'type'=>'text', 'class'=>'form-control input-sm'));
    echo $this->Form->input('password', array('label'=>'New Password', 'placeholder'=>'Enter new password', 'required'=>false, 'class'=>'form-control input-sm'));
    echo $this->Form->input('store_password', array('label'=>'Store Admin Password', 'placeholder'=>'Enter store admin password', 'class'=>'form-control input-sm'));
    echo $this->Form->input('feature_store_access_passwords', array('type'=>'checkbox', 'label'=>'Enable store access for multiple users', 'class'=>''));
    ?>
    <button type="submit" class="btn btn-primary btn-sm">Update User Information</button>

    <?php
    echo $this->Form->end();
    ?>
</div>