<?php
$this->set('enableTextEditor', true);
?>

<h1>Edit Store - <?php echo $storeInfo['Store']['name'];?></h1><br>

<?php
echo $this->Form->create();
?>
<table class="table table-sm">
    <thead>
    <tr>
        <th colspan="2"><?php echo $storeInfo['Store']['name'];?></th>
    </tr>
    </thead>
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
		<td>Print Header</td>
		<td>
			<textarea
					id="StorePrintHeader"
					name="data[Store][print_header]"
					class="form-control form-control-sm tinymce"
					placeholder="Set print page header"
			><?php echo $this->data['Store']['print_header']; ?></textarea>
		</td>
	</tr>
	<tr>
		<td>Print Footer</td>
		<td>
			<textarea
					id="StorePrintFooter"
					name="data[Store][print_footer]"
					class="form-control form-control-sm tinymce"
					placeholder="Set print page footer"
			><?php echo $this->data['Store']['print_footer']; ?></textarea>
		</td>
	</tr>
    <tr>
        <td></td>
        <td style="text-align: center;">
            <br>
            <button type="submit" class="btn btn-primary">Update Store</button>
            <br><br>
            <a href="/stores/" class="btn btn-warning btn-sm">Cancel</a>
        </td>
    </tr>
    </tbody>
</table>
<?php
echo $this->Form->end();
?>
