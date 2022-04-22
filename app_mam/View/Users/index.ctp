<?php
echo $this->Html->css('smoothness/jquery-ui-1.8.18.custom', false);
echo $this->Html->script('jquery-ui-1.8.18.custom.min', array('inline'=>false));
?>

<?php
$showAddButton = false;
$showActions = false;
$showDeleteAction = false;
switch($this->Session->read('UserCompany.user_level')) {
	case '2':
		$showAddButton = true;
		$showActions = true;		
		break;
	case '3':
	case '4':
		$showAddButton = true;
		$showActions = true;
		$showDeleteAction = true;
		break;
	default:
		break;
}
?>

<div class="floatLeft"><h1>Users List</h1></div>
<?php echo ($showAddButton) ? $this->Html->link('+ Invite User', array('controller'=>'users', 'action'=>'inviteUser'), array('class'=>'button grey medium floatRight')) : '';?>
<div class="clear"></div>
<br>
<?php
if(!empty($users)) {
?>
	<table style="width:800px;" cellspacing='1' cellpadding='1'>
		<thead>
			<tr>
				<th>Sl.No.</th>
				<th>Name</th>
				<th>Email Address</th>
				<th>User Access</th>
				
				<?php echo ($showActions) ? '<th>Action</th>' : ''; ?>			
			</tr>
		</thead>
		<tbody>
			<?php
			$i=0;
			foreach($users as $row) {
				$i++;
				$userID = $row['User']['id'];
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row['User']['name'];?></td>
				<td><?php echo $row['User']['email'];?></td>
				<td>
					<?php 
						$userLevel = Configure::read('UserLevel.'.$row['UserCompany']['user_level']);
						echo ($row['Company']['user_id'] == $row['User']['id']) ? '<b>'.$userLevel.' - Owner</b>' : $userLevel;						
					?>
				</td>				
				<?php if($showActions): ?>	
				<td>
					<?php
					if($row['Company']['user_id'] != $row['User']['id']) {
					?>
					<div class="button grey small" onclick="javascript: $('#userID<?php echo $userID;?>').dialog({ modal: true, minWidth: 400, title: '<?php echo $row['User']['email'];?>' })" style="margin-right:15px; width:100px;">Modify Access &nbsp;&raquo;</div>
					<div id="userID<?php echo $userID;?>" style="display:none;">
						<?php
						echo $this->Form->create(null, array('controller'=>'users', 'action'=>'changeUserAccess/'.$userID));							
						echo $this->Form->input('UserCompany.user_level', array('label'=>'Access Level', 'required'=>true, 'empty'=>false, 'options'=>Configure::read('UserLevel'), 'default'=>$row['UserCompany']['user_level']));
						echo $this->Form->submit('Change User Access &nbsp;&raquo;', array('escape'=>false));
						echo $this->Form->end();
						?>
					</div>
					<?php
					}
					?>
				
					<?php
					//echo ($row['Company']['user_id'] == $row['User']['id']) ? '' : $this->Html->link('Edit', array('controller'=>'users', 'action'=>'edit/'.$row['User']['id']));
					//echo '&nbsp;&nbsp;&nbsp;&nbsp;';
					// echo ($row['Company']['user_id'] == $row['User']['id']) ? '' : (($showDeleteAction) ?  $this->Html->link('Delete', array('controller'=>'users', 'action'=>'delete/'.$row['User']['id'])) : '');
					echo ($row['Company']['user_id'] == $row['User']['id']) ? 'N/A' : (($showDeleteAction) ?  $this->Html->link('Remove', array('controller'=>'users', 'action'=>'remove/'.$row['User']['id']), array('class'=>'button small grey'), "'".$row['User']['name']."' will no longer be able to access this account. Are you sure you want to remove this user?") : 'N/A');						
					?>				
					
				</td>
				<?php endif;?>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	
	
<?php	
}
else {
	echo 'No User Found <br> <br>';
	echo ($showAddButton) ? $this->Html->link('Click here to add new user', array('controller'=>'users', 'action'=>'add')) : '';
}
?>