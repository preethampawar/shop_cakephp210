<style type="text/css">
#selectStoreDiv {
	font-size:120%;
}
</style>
<article>
	<header><h1>My Stores</h1></header>
	<p>
	<?php
	if($this->Session->read('manager') == '1') {
		echo $this->Html->link('+ Add New Store', array('controller'=>'stores', 'action'=>'add'));	
		echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		?>
        <a href="/users/add">+ Add New User</a>
        <?php
	}
		//echo $this->Html->link('Backup database', array('controller'=>'stores', 'action'=>'createbackup'));	
	?>
	</p>
<?php 
if(!empty($stores)) {
?><br>
<div id="selectStoreDiv">
	<h3>Select a store</h3>
	<table class='table table-striped'>
		<thead>
			<tr>
				<th style="width:30px;">Sl.No.</th>
				<th>
					Store
				</th>
				<?php
				if($this->Session->read('manager') == '1') {
				?>
				<th>User

					
				</th>
				<?php
				}
				?>
				<th>Status</th>
                <th>Expiry Date</th>
				<th>Created on</th>				
				<th style="width:200px; text-align:center;">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k=0;
			foreach($stores as $row) {
				$k++;
			?>
			<tr>
				<td><?php echo $k;?></td>
				<td>
					<?php 						
						echo $this->Html->link(strtoupper($row['Store']['name']), array('controller'=>'stores', 'action'=>'selectStore', $row['Store']['id']), array('title'=>'Select this store'));						
					?>
				</td>
				<?php
				if($this->Session->read('manager') == '1') {
				?>
				<td>
					<a href="/users/edit/<?php echo $row['Store']['user_id'];?>" >
                        <?php echo $userInfo[$row['Store']['user_id']];?>
                    </a>
				</td>
				<?php
				}
				?>
				<td>
                    <?php
                    $status = 'active';
                    if ($row['Store']['active']) {
                        if ($row['Store']['name'] != 'test') {
                            // check for expiry
                            $storeExpiredOn = $row['Store']['expiry_date'];
                            $unixTimeStoreExpiry = strtotime($storeExpiredOn);
                            $unixTimeNow = strtotime("now");
                            if ($unixTimeNow > $unixTimeStoreExpiry) {
                                $status = 'expired';
                            }
                        }

                    } else {
                        $status = 'inactive';
                    }

                    if($status == 'active') echo '<span class="text-success"><b>Active</b></span>';
                    if($status == 'inactive') echo '<span class="text-info"><b>Inactive</b></span>';
                    if($status == 'expired') echo '<span class="text-danger"><b>Expired</b></span>';
                    ?>
				</td>
                <td><?php echo $row['Store']['expiry_date'] ? date('d-m-Y', strtotime($row['Store']['expiry_date'])) : '-'; ?></td>
				<td><?php echo date('d-m-Y', strtotime($row['Store']['created']));?></td>	
				
				
				<td style="text-align:center;"> 
					<?php 
					if(!$row['Store']['show_brands_in_products']) {
					?>
					<form method="post" style="" name="showbrandsinproducts_<?php echo $row['Store']['id'];?>" id="showbrandsinproducts_<?php echo $row['Store']['id'];?>" action="<?php echo $this->Html->url("/stores/showbrandsinproducts/".$row['Store']['id']);?>">
						<a href="javascript:return false;" onclick="if (confirm('Enabling this feature will show brands along with products. \n\nAre you sure you want to enable it?')) { $('#showbrandsinproducts_<?php echo $row['Store']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-warning">
							Brands In Products - Disabled
						</a>
					</form>
					<?php 
					} else {
					?>
					<form method="post" style="" name="hidebrandsinproducts_<?php echo $row['Store']['id'];?>" id="hidebrandsinproducts_<?php echo $row['Store']['id'];?>" action="<?php echo $this->Html->url("/stores/hidebrandsinproducts/".$row['Store']['id']);?>">
						<a href="javascript:return false;" onclick="if (confirm('Enabling this feature will hide brands in products. \n\nAre you sure you want to disable it?')) { $('#hidebrandsinproducts_<?php echo $row['Store']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-primary">
							Brands In Products - Enabled
						</a>
					</form>
					<?php
					}
					?>
					<br>
					
					<?php 
					if(!$row['Store']['show_brands_in_reports']) {
					?>
					<form method="post" style="" name="showbrandsinreports_<?php echo $row['Store']['id'];?>" id="showbrandsinreports_<?php echo $row['Store']['id'];?>" action="<?php echo $this->Html->url("/stores/showbrandsinreports/".$row['Store']['id']);?>">
						<a href="javascript:return false;" onclick="if (confirm('Enabling this feature will show brands along with products. \n\nAre you sure you want to enable it?')) { $('#showbrandsinreports_<?php echo $row['Store']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-warning">
							Brands In Reports - Disabled
						</a>
					</form>
					<?php 
					} else {
					?>
					<form method="post" style="" name="hidebrandsinreports_<?php echo $row['Store']['id'];?>" id="hidebrandsinreports_<?php echo $row['Store']['id'];?>" action="<?php echo $this->Html->url("/stores/hidebrandsinreports/".$row['Store']['id']);?>">
						<a href="javascript:return false;" onclick="if (confirm('Enabling this feature will hide brands in products. \n\nAre you sure you want to disable it?')) { $('#hidebrandsinreports_<?php echo $row['Store']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-sm btn-block btn-primary">
							Brands In Reports - Enabled
						</a>
					</form>
					<?php
					}
					?>
					<br>
					<?php
					if($this->Session->read('manager') == '1') {
					?>
						<form method="post" style="" name="sales_<?php echo $row['Store']['id'];?>" id="sales_<?php echo $row['Store']['id'];?>" action="<?php echo $this->Html->url("/stores/delete/".$row['Store']['id']);?>">
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php echo $this->Html->link('Edit', array('controller'=>'stores', 'action'=>'edit', $row['Store']['id']), array('title'=>'Edit '.$row['Store']['name']));	?>
						&nbsp;&nbsp;|&nbsp;&nbsp;
							<!-- <input type="submit" value="Remove" name="Remove" onclick="if (confirm('Are you sure you want to delete this product - <?php echo $row['Store']['name'];?> from the list?')) { $('#sales_<?php echo $row['Store']['id'];?>').submit(); } event.returnValue = false; return false;">  -->
							
							<a href="javascript:return false;" onclick="if (confirm('All Sales, Purchases, Expenses/Income, Products, Employees, etc. related to this Store will be deleted. \nThis action is irreversable..\n\nAre you sure you want to delete this store - <?php echo $row['Store']['name'];?> from the list?')) { $('#sales_<?php echo $row['Store']['id'];?>').submit(); } event.returnValue = false; return false;">Delete Store</a>
						</form>
							
						<?php //echo $this->Form->postLink('Delete Store', array('controller'=>'stores', 'action'=>'delete', $row['Store']['id']), array('title'=>'Delete '.$row['Store']['name']), 'You are about to remove all the data related to this Store.\nAll Sales, Purchases, Expenses/Income, Products, Employees, etc. related to this Store will be deleted. \nThis action is irreversable.\n\nAre you sure you want to delete this Store?');	?>
					<?php
					}
					?>
				</td>	
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<?php
}
else {
?>
	<p>No Stores Found</p>
<?php
}
?>
	
</article>