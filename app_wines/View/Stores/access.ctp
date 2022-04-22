<h1>Step2 - Store Access</h1>
<br>
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-5 col-lg-4">
        <?php echo $this->Form->create(); ?>

        <?php
        $selectedUserType = '';
        if(isset($this->data['Store']['user_type'])) {
            $selectedUserType = $this->data['Store']['user_type'];
        }
        ?>

        <div class="row">
            <label>Select User Type</label>
            <select name="data[Store][user_type]" class="form-control input-sm">
                <option value="admin" <?php echo $selectedUserType == 'admin' ? 'selected' : null; ?>>Admin</option>
                <option value="user"<?php echo $selectedUserType == 'user' ? 'selected' : null; ?>>Store User</option>
            </select>
        </div>
        <div class="row">
            <label>Store Password</label>
            <input type="password" name="data[Store][access_password]" placeholder="Enter store password" class="form-control input-sm" minlength="4" required>
        </div>
        <div class="row" style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary btn-md">Submit</button>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
</div>