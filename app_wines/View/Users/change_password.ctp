<h1>Change Password</h1>
<br>
<div class="row">
    <?php
    echo $this->Form->create('User');
    echo $this->Form->input('name', array('label'=>'Full Name', 'placeholder'=>'Enter full name', 'class'=>'form-control input-sm', 'disabled'=>true));
    echo $this->Form->input('email', array('label'=>'Email Address', 'placeholder'=>'Enter email address', 'type'=>'text', 'class'=>'form-control input-sm', 'disabled'=>true));
    echo $this->Form->input('password', array('label'=>'New Password', 'placeholder'=>'Enter new password', 'type'=>'password', 'required'=>true, 'class'=>'form-control input-sm'));
    echo $this->Form->input('confirmPassword', array('label'=>'Re-enter New Password', 'placeholder'=>'Re-enter password', 'type'=>'password', 'required'=>true, 'class'=>'form-control input-sm'));
    ?>
    <button type="submit" class="btn btn-primary btn-sm">Update</button>

    <?php
    echo $this->Form->end();
    ?>
</div>
