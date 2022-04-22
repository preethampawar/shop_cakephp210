<h1>Add New Store</h1><br>

<?php
echo $this->Form->create();
?>
<table class="table table-striped table-condensed" style="width: 500px;">
    <tbody>
    <tr>
        <td>Status</td>
        <td><?php echo $this->Form->input('Store.active', array('label'=>'Is Active', 'title'=>'Enable / Disable this store temporarily'));?></td>
    </tr>
    <tr>
        <td>Store Name</td>
        <td><?php echo $this->Form->input('Store.name', array('label'=>false, 'required'=>true, 'type'=>'text', 'title'=>'Enter Store Name', 'class'=>'form-control input-sm')); ?></td>
    </tr>
    <tr>
        <td>User</td>
        <td><?php echo $this->Form->input('Store.user_id', array('label'=>false, 'required'=>true, 'type'=>'select', 'options'=>$userInfo, 'class'=>'form-control input-sm')); ?></td>
    </tr>
    <tr>
        <td>Expiry Date</td>
        <td><?php echo $this->Form->input('Store.expiry_date', array('label'=>false, 'required'=>true, 'type'=>'date')); ?></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center;">
            <br>
            <button type="submit" class="btn btn-primary">Create Store</button>
            <br><br>
            <a href="/stores/" class="btn btn-warning btn-sm">Cancel</a>
        </td>
    </tr>
    </tbody>
</table>
<?php
echo $this->Form->end();
?>
