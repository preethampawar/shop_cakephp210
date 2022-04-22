<article>
	<header><h1>Database Backups</h1></header>	
	<?php 
	if(isset($files) and !empty($files)) {
	?><br>
		<h3>List of backup files</h3>
		<table class='table' style="width:600px;">
			<thead>
				<tr>
					<th style="width:30px;">Sl.No.</th>
					<th>Filename</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$k=0;
				foreach($files as $file) {
					$k++;
				?>
				<tr>
					<td><?php echo $k;?></td>
					<td>
						<?php 						
							echo $this->Html->link($file, array('controller'=>'stores', 'action'=>'downloadfile', $file), array('title'=>'Download backup file: '.$file));						
						?>
					</td>					
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	<?php
	}
	else {
	?>
	<p>No backup files found.</p>
	<?php
	}
	?>	
</article>