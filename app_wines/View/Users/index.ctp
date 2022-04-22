<?php echo $this->Session->flash('auth'); ?>
<div>
	<h1>Users List</h1><br>
    <a href="/users/add">+ Add New User</a><br>
    <?php
    if($users) {
        ?>
        <br>
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>Sl.No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Store access for multiple users</th>
                <th>Store admin password</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($users as $index => $row) {
                ?>
                <tr>
                    <td>
                        <?php echo $index+1;?>
                    </td>
                    <td><a href="/users/edit/<?php echo $row['User']['id'];?>"><?php echo $row['User']['name'];?></a></td>
                    <td><a href="/users/edit/<?php echo $row['User']['id'];?>"><?php echo $row['User']['email'];?></a></td>
                    <td><?php echo $row['User']['feature_store_access_passwords'] ? 'Yes' : 'No';?></td>
                    <td><?php echo $row['User']['store_password'];?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <?php
    } else {
        echo 'No users found';
    }
    ?>

</div>