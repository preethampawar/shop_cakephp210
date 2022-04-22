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
			<table class='table table-striped table-lg small'>
				<thead>
				<tr>
					<th>
						Store Name
					</th>
					<th>Status</th>
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
							echo $this->Html->link(strtoupper($row['Store']['name']), ['controller' => 'stores', 'action' => 'selectStore', $row['Store']['id']], ['title' => 'Select this store']);
							?>
						</td>
						<td>
							<?php
							if ($row['Store']['active']) {

								if ($row['Store']['name'] == 'test') {
									echo 'Active';
								} else {
									// check for expiry
									$storeCreatedOn = $row['Store']['created'];
									$storeCreatedOn = date('Y-m-d', strtotime($storeCreatedOn));
									$unixTimeStoreExpiry = strtotime($storeCreatedOn . " +1 year +1 day");
									$unixTimeNow = strtotime("now");
									if ($unixTimeNow > $unixTimeStoreExpiry) {
										echo 'Expired';
									} else {
										echo 'Active';
									}
								}

							} else {
								echo 'Disabled';
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
	} else {
		?>
		<p>No Stores Found</p>
		<?php
	}
	?>

</article>
