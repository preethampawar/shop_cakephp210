<?php echo $this->Session->flash('auth'); ?>
<div style="margin:auto; width:350px;">
    <br><br>
    <h1>Log In</h1><br>
    <?php
    echo $this->Form->create('User');
    echo $this->Form->input('email', ['type' => 'email', 'required' => true, 'maxlength' => '40', 'label' => 'Email Address', 'autofocus' => true]);
    echo $this->Form->input('password', ['type' => 'password', 'required' => true, 'maxlength' => '40']);
    echo $this->Form->end(__('Login'));
    ?>
</div>