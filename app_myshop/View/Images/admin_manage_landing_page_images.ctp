<?php echo $this->element('message'); ?>
<div id="manageImages">
	<section>
		<p><?php echo $this->Html->link('&laquo; Back', '/admin/contents/', ['escape' => false]); ?>    </p>
		<br>
		<header>
			<h2>Manage Images: Landing Page</h2>
		</header>
		<div style="width:600px;">
			<?php
			echo $this->Form->create(null, ['type' => 'file']);
			echo $this->Form->input('Image.file', ['type' => 'file', 'label' => 'Select Image']);
			echo '<br>';
			echo '<br>';
			echo $this->Form->input('Image.highlight', ['label' => 'Highlight Image']);
			echo '<br>';
			echo $this->Form->input('Image.caption', ['label' => 'Caption']);
			echo '<br>';
			echo $this->Form->submit('Submit &nbsp;&raquo;', ['escape' => false]);
			echo $this->Form->end();
			?>
		</div>
	</section>
	<br>
	<hr>
	<br>
	<section>
		<header><h2>Landing Page: Images List</h2></header>
		<div>
			<?php
			if (!empty($contentImages)) {
				?>
				<table class='table'>
					<thead>
					<tr>
						<th style='width:30px;'>Sl.No.</th>
						<th style='width:150px;'>Image</th>
						<th>Caption</th>
						<th style='width:130px;'>Actions</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$i = 0;
					foreach ($contentImages as $row) {
						$i++;
						$imageID = $row['Image']['id'];
						$imageCaption = $row['Image']['caption'];
						$imageHighlight = $row['Image']['highlight'];
						$contentID = $contentInfo['Content']['id'];
						$imageCaptionSlug = Inflector::slug($imageCaption, '-');
						?>
						<tr>
							<td><?php echo $i; ?>.</td>
							<td>
								<?php echo $this->Img->showImage('img/images/' . $imageID, ['height' => '150', 'width' => '150', 'type' => 'crop', 'quality' => '75', 'filename' => $imageCaptionSlug], ['style' => '', 'alt' => '']); ?>
							</td>
							<td style="vertical-align:top; padding-left:20px;">
								<p><strong>Caption:</strong></p>
								<p><?php //echo $imageCaption;?>
									<?php
									echo $this->Form->create(null, ['action' => 'updateCaption', 'method' => 'post']);
									echo $this->Form->input('Image.id', ['type' => 'hidden', 'value' => $row['Image']['id']]);
									echo $this->Form->input('Image.caption', ['label' => '', 'value' => $row['Image']['caption']]);
									echo '<br>';
									echo $this->Form->submit('Update Caption', ['escape' => false]);
									echo $this->Form->end();
									?>
								</p>
							</td>
							<td style="text-align:center;">
								<?php
								if (!$imageHighlight) {
									echo $this->Html->link('Highlight', '/admin/images/highlightContentImage/' . $imageID . '/' . $contentID);
									echo '&nbsp;|&nbsp;';
								}
								echo $this->Html->link('Delete', '/admin/images/deleteImage/' . $imageID, [], 'Are you sure you want to delete this image?');
								?>
							</td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
				<?php
			} else {
				echo 'No Images Found';
			}

			?>
		</div>
	</section>
</div>
