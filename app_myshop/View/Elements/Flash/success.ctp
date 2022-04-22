<div aria-live="polite" aria-atomic="true" class="position-relative">
	<div class="toast-container fixed-top end-0 p-2 mt-5" style="left: auto">
		<div id="<?php echo h($key) ?>Message" class="toast toast-php text-white bg-primary border-white" role="alert"
			 aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="1500">
			<div class="d-flex align-items-center justify-content-between">
				<div class="toast-body">
					<span class="fa fa-check-circle"></span>
					<?= $message ?>
				</div>
				<button type="button" class="btn-close btn-close-white ml-auto me-2" data-bs-dismiss="toast"
						aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>
