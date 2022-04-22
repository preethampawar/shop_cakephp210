<div class='content'>

	<h2><?php echo ucfirst($this->Session->read('Site.title')); ?> - Blog</h2>
	<br>
	<div>
		<?php
		if (!empty($blogs)) {
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

			?>
			<hr><br>
			<?php
			$i = 0;
			foreach ($blogs as $row) {
				$i++;
				$blogID = $row['Blog']['id'];
				$blogTitle = $row['Blog']['title'];
				$blogViews = ($row['Blog']['views']) ? $row['Blog']['views'] : 1;
				$blogTitleSlug = Inflector::slug($blogTitle, '-');

				$blogDesc = Sanitize::html($row['Blog']['description'], ['remove' => true]);
				$blogDesc = html_entity_decode($blogDesc);
				$blogDesc = $this->Text->truncate($blogDesc, 400);
				//$blogDesc = $this->Text->autoLinkUrls($blogDesc);
				// $blogTime = $this->Time->timeAgoInWords($row['Blog']['created'], array('format'=>'F jS, Y', 'end'=>'+1 days'));
				$blogTime = date('dS M Y', strtotime($row['Blog']['created']));

				?>
				<div>
					<div class="floatLeft" style="width:250px;">
						<div
							class=" "><?php echo $this->Html->link($blogTitle, '/blog/show/' . $blogID . '/' . $blogTitleSlug, ['title' => $blogTitle]); ?></div>
						<div style="font-style:normal; font-size:90%; ">
							<p>
								<time><?php echo $blogTime; ?></time>
							</p>
							Views: <?php echo $blogViews; ?>
						</div>
					</div>
					<div class="floatLeft" style="margin-left:20px; width:620px; text-align:justify;">
						<?php echo $blogDesc; ?><br>
						<span
							class="more floatRight"><?php echo $this->Html->link('Read more...', '/blog/show/' . $blogID . '/' . $blogTitleSlug, ['title' => $blogTitle]); ?></span>
					</div>
					<div style="clear:both;"></div>
				</div>
				<div style="border-bottom:1px dotted #aaa; margin:5px 0 10px 0; "></div>
				<?php
			}
			?>

			<?php
		} else {
			?>
			No posts found.
			<?php
		}
		?>
		<?php //debug($categoryProducts);?>
	</div>
</div>
