<section>
	<article>
		<header><h2>Pages</h2></header>
		<p>
			<?php echo $this->Html->link('+ Add New Page', '/admin/contents/add', ['escape' => false, 'class' => '']); ?>
		</p>
		<div class="pagesContent">
			<?php
			if (!empty($contents) or ($this->Session->read('Site.show_landing_page'))) {
				$i = 1;
				?>
				<table class="table">
					<thead>
					<tr>
						<th style="width:30px">Sl.No.</th>
						<th>Page Title</th>
						<th style="width:100px">&nbsp;</th>
						<th style="width:100px">&nbsp;</th>
						<th style="width:100px">Status</th>
						<th style="width:100px">Actions</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if ($this->Session->read('Site.show_landing_page')) {
						?>
						<tr>
							<td style="text-align:center;"><?php echo $i; ?>.</td>
							<td>
								<?php echo $this->Html->link("<strong>Landing Page</strong>", '/admin/contents/editLandingPage/', ['escape' => false, 'style' => 'text-decoration:none;']); ?>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td style="text-align:center;">
								<?php
								/*
								if(isset($landingPageContent['Content']['id'])) {
									if($landingPageContent['Content']['active']) {
										echo $this->Html->link('Active', '/admin/contents/activate/'.$landingPageContent['Content']['id'].'/false', array('escape'=>false, 'style'=>'color:green'), 'Are you sure you want to deactivate landing page? Deactivating will hide this page from public.');
									}
									else
									{
										echo $this->Html->link('Inactive', '/admin/contents/activate/'.$landingPageContent['Content']['id'].'/true', array('escape'=>false, 'style'=>'color:red;'), 'Are you sure you want to activate landing page?');
									}
								}
								*/
								?>
							</td>
							<td style="text-align:center;">
								<?php echo $this->Html->link('Images', '/admin/images/manageLandingPageImages/', ['title' => 'Manage Landing Page Images']); ?>
								&nbsp;|&nbsp;
								<?php
								echo $this->Html->link('Edit', '/admin/contents/editLandingPage/', ['escape' => false, 'style' => 'text-decoration:none;', 'title' => 'Edit page']);
								?>
							</td>
						</tr>
						<?php
						$i++;
					}
					?>

					<?php
					foreach ($contents as $row) {

						$contentID = $row['Content']['id'];
						$contentTitle = $row['Content']['title'];
						// $contentCreated = $row['Content']['created'];
						$contentActive = $row['Content']['active'];
						$topNavMenu = ($row['Content']['top_nav_menu']) ? 'Top Nav' : '-';
						$footerMenu = ($row['Content']['footer_menu']) ? 'Footer' : '-';
						$class = ($contentActive) ? 'colorGreen' : 'colorRed';
						?>
						<tr>
							<td style="text-align:center;"><?php echo $i; ?>.</td>
							<td>
								<?php
								echo $this->Html->link("<strong>$contentTitle</strong>", '/admin/contents/edit/' . $contentID, ['escape' => false, 'style' => 'text-decoration:none;']);
								?>
							</td>
							<td style="text-align:center;"><?php echo $topNavMenu; ?></td>
							<td style="text-align:center;"><?php echo $footerMenu; ?></td>
							<td style="text-align:center;" class="<?php echo $class; ?>">
								<?php
								if ($contentActive) {
									echo $this->Html->link('Active', '/admin/contents/activate/' . $contentID . '/false', ['escape' => false, 'style' => 'color:green'], 'Are you sure you want to deactivate this page? Deactivating will hide this page from public.');
								} else {
									echo $this->Html->link('Inactive', '/admin/contents/activate/' . $contentID . '/true', ['escape' => false, 'style' => 'color:red;'], 'Are you sure you want to activate this page?');
								}
								?>
							</td>

							<td style="text-align:center;">
								<?php
								echo $this->Html->link('Images', '/admin/images/manageCustomPageImages/' . $contentID, ['title' => 'Manage ' . $contentTitle . ' Page Images']);
								echo '&nbsp;|&nbsp';
								echo $this->Html->link('Edit', '/admin/contents/edit/' . $contentID, ['escape' => false, 'style' => 'text-decoration:none;', 'title' => 'Edit page']);
								echo '&nbsp;|&nbsp;';
								echo '&nbsp;';
								echo $this->Html->link('X', '/admin/contents/delete/' . $contentID, ['escape' => false, 'style' => 'color:red', 'title' => 'Delete Page']);
								echo '&nbsp;&nbsp;';
								?>
							</td>
						</tr>
						<?php
						$i++;
					}
					?>
					</tbody>
				</table>
				<?php
			} else {
				echo "No pages found";
			}
			?>
		</div>
	</article>
</section>

