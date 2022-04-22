<?php echo $this->element('group_paper_rates_menu');?>

<h1>Paper Rates</h1>


<div class="mt-3">
	<?php
	if (!empty($groupList)) {
	?>
	<div class="card bg-light mt-4">
		<div class="card-body">
			<div class="d-flex justify-content-between">
				<label class="form-label">Filter</label>
				<?php echo $this->Form->select('Group.id', $groupList, ['empty' => '- Select Group -', 'class' => 'form-control form-control-sm ms-2', 'onchange' => 'selectCategory(this)', 'default' => $groupId]); ?>


				<script type="text/javascript">
					function selectCategory(ele) {
						var catId = ele.value ?? '';
						window.location = '/GroupPaperRates/index/' + catId;
					}
				</script>

			</div>

		</div>
	</div>
		<br>
	<?php
	}
	?>

	<h6 class="mt-3">
		<?php
		if ($groupInfo) {
			echo 'Category "' . $groupInfo['Group']['name'] . '"';
			?>
			<span
					style="font-size:11px; font-style:italic;">[<?php echo $this->Html->link('Show all records', ['controller' => 'GroupPaperRates', 'action' => 'index'], ['title' => 'Show all category records']); ?>]</span>
			<?php
		} else {
			echo 'All Records';
		}
		?>
	</h6>

	<?php
	if ($paperRates) {
		?>

		<div class="table-responsive mt-3">
			<table class='table table-sm'>
			<thead>
			<tr>
				<th>#</th>
				<th>Date</th>
				<th>Group Name</th>
				<th>Rate</th>
				<th>Created On</th>				
			</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			foreach ($paperRates as $row) {
				$i++;
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td class="text-nowrap"><?php echo date('d-m-Y', strtotime($row['GroupPaperRate']['date'])); ?></td>
					<td class=""><?= $groupList[$row['GroupPaperRate']['group_id']] ?></td>
					<td><?= $row['GroupPaperRate']['rate'] ?></td>
					<td class="text-nowrap"><?php echo date('d-m-Y', strtotime($row['GroupPaperRate']['created'])); ?></td>
					<td class="text-end" style="width: 100px;">
						<form method="post" style=""
							  name="paper_rate_<?php echo $row['GroupPaperRate']['id']; ?>"
							  id="paper_rate_<?php echo $row['GroupPaperRate']['id']; ?>"
							  action="<?php echo $this->Html->url("/GroupPaperRates/remove/" . $row['GroupPaperRate']['id']); ?>">
							<a href="#" name="Remove"
							   onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#paper_rate_<?php echo $row['GroupPaperRate']['id']; ?>').submit(); } event.returnValue = false; return false;"
							   class="btn btn-danger btn-sm">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete
							</a>
						</form>

						<?php
						//echo $this->Form->postLink('Remove', array('controller'=>'GroupPaperRates', 'action'=>'remove', $row['GroupPaperRate']['id']), array('title'=>'Remove this record', 'class'=>'small button link red'), 'Are you sure you want to delete this record?');
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
		if (count($paperRates) > 10) {
			// prints X of Y, where X is current page and Y is number of pages
			echo 'Page ' . $this->Paginator->counter();
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';

			// Shows the next and previous links
			echo '&laquo;' . $this->Paginator->prev('Prev', null, null, ['class' => 'disabled']);
			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
			// Shows the page numbers
			echo $this->Paginator->numbers();

			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
			echo $this->Paginator->next('Next', null, null, ['class' => 'disabled']) . '&raquo;';
		}
		?>
	<?php } else { ?>
		<p class="text-muted small">No records found.</p>
	<?php
	}
	?>

</div>
