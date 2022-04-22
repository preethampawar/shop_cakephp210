<h1>Customer Reviews & Testimonials</h1>

<div class="list-group mt-4">
<?php
if ($testimonials) {
	$i = 0;
	foreach($testimonials as $row) {
		$i++;
		$title = $row['Testimonial']['title'];
		$customerName = $row['Testimonial']['customer_name'];
		$url = $row['Testimonial']['url'];
	?>
		<div class="p-3 border-start border-warning border-5 my-3">
			<div>
				<i class="fa fa-quote-left small text-orange me-2"></i>
				<?php
				if($url) {
					?>
						<a href="<?= $url ?>" class="text-decoration-none"><?= $title ?></a>
					<?php
				} else {
					?>
					<?= $title ?>
					<?php
				}
				?>
				<i class="fa fa-quote-right small text-orange ms-2"></i>
			</div>
			<div class="text-start text-orange fst-italic small mt-3"> - <?= $customerName ?></div>
		</div>
	<?php
	}
} else {
	?>
	<div class="p-2 border-start border-warning border-4">No reviews or testimonials found.</div>
	<?php
}
?>
</div>

<br><br>
