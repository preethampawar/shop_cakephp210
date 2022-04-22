
<div class="notice"> 
<?php
if(empty($userCompany)) {	
	echo 'Need an account for your business needs? Register your business with us.';
	echo $this->Html->link('Click here to register', '/companies/requestNewAccount');
}
else {
	echo 'Need one more business account? ';
	echo $this->Html->link('Click here', '/companies/requestNewAccount', array('escape'=>false));
}
?>
</div>
<br>
<h1>Registered Business/Personal Accounts</h1>
<?php
if(!empty($userCompanies)) {
	?>
	<table style="" cellspacing='1' cellpadding='1'>
		<tr>
			<th width='40'>Sl.No.</th>
			<th>Company Name</th>
			<th width='150'>Business Type</th>
			<th width='70'>Is Owner</th>
			<th width='250'>Owner Email</th>
			<th width='180'>Access Level</th>
			<th width='100'>Expiry Date</th>
		</tr>
		
	<?php
	$i=0;
	foreach($userCompanies as $row) {		
		$i++;
		?>
		<tr>
			<td><?php echo $i;?></td>
			<td>
				<?php 
				echo ($row['Company']['active']) ? $this->Html->link($row['Company']['title'], array('controller'=>'companies', 'action'=>'switchCompany', base64_encode($row['Company']['id']))) : $row['Company']['title'].' (InActive)';				
				?>				
			</td>			
			<td>
				<?php echo Configure::read('BusinessAccounts.'.$row['Company']['business_type']);?> 
			</td>
			<td><?php echo ($row['Company']['user_id'] == $row['User']['id']) ? 'Yes' : 'No';?></td>
			<td><?php echo $row['Company']['User']['email'];?></td>
			<td>
				<?php 
					$userLevel = Configure::read('UserLevel.'.$row['UserCompany']['user_level']);
					$userLevel = ($userLevel) ? $userLevel : 'Admin';
					echo ($row['Company']['user_id'] == $row['User']['id']) ? '<b>'.$userLevel.'</b>' : $userLevel;						
				?>
			</td>
			<td style="width:100px;">
				<?php 
				if($row['Company']['business_type'] != 'personal') {			
					$no_of_days = (strtotime($row['Company']['subscription_end_date']) - strtotime(date('Y-m-d')))/(60*60*24);
					echo ($row['Company']['user_id'] == $row['User']['id']) ? date('d-m-Y', strtotime($row['Company']['subscription_end_date'])) : 'N/A';
					
					if($no_of_days<0) {
						$color = 'red';
						$no_of_days = 'Expired';
					}
					elseif($no_of_days<10) {
						$color = 'orange';
						$no_of_days = $no_of_days.' day(s) left';
					}
					else {
						$color = 'green';
						$no_of_days = $no_of_days.' day(s) left';
					}
					echo "<br><span style='color:$color; font-weight:bold;'>$no_of_days </span>";	
				}
				else {
					echo 'N/A';
				}
				?>
			</td>			
		</tr>
		<?php
	}
	?>
	
	</table>
	<?php
}
else {
	echo 'No Company Found <br><br>';
	echo $this->Html->link('Click here to add new company', array('controller'=>'companies', 'action'=>'add'));
}
?>