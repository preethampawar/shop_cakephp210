<style type="text/css">
	#selectStoreDiv {
		font-size: 120%;
	}
</style>
<article>
	<header><h1><i class="fa fa-store"></i> My Stores</h1></header>
	<p>
		<?php
		if ($this->Session->read('manager') == '1') {
			echo $this->Html->link('+ Add New Store', ['controller' => 'stores', 'action' => 'add']);
			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
		}
		//echo $this->Html->link('Backup database', array('controller'=>'stores', 'action'=>'createbackup'));
		?>
	</p>
	<?php
	if (!empty($stores)) {
		?>
		<div id="selectStoreDiv">
			<table class='table table-lg'>
				<thead>
				<tr>
					<th>
						Store Name
					</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$k = 0;
				foreach ($stores as $row) {
					$k++;
					?>
					<tr>
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

							if($status == 'active') echo '<span class="text-success" title="Active"><b><i class="fa fa-circle"></i></b></span>';
							if($status == 'inactive') echo '<span class="text-info" title="Inactive"><b><i class="fa fa-circle"></i></b></span>';
							if($status == 'expired') echo '<span class="text-danger" title="Expired"><b><i class="fa fa-circle"></i></b></span>';
							?>
							&nbsp;
							<?php
							echo $this->Html->link(strtoupper($row['Store']['name']), ['controller' => 'stores', 'action' => 'selectStore', $row['Store']['id']], ['title' => 'Select this store']);
							?>
							<div class="mt-0 small text-muted ms-4">
								<span class="small btn btn-sm">[ Expiry Date: <?php echo $row['Store']['expiry_date'] ? date('d-m-Y', strtotime($row['Store']['expiry_date'])) : '-'; ?> ]</span>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
	} else {
		?>
		<p>No Stores Found</p>
		<?php
	}
	?>

</article>
