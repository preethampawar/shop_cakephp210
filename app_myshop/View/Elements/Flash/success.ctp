<div class="fixed-top" style="width:16rem; left: auto; margin-top: 8rem; margin-right: 0.5rem;">
	<div id="<?php echo h($key) ?>Message" class="toast text-white bg-primary border-white" role="alert"
		 aria-live="assertive" aria-atomic="true">
		<div class="d-flex align-items-center">
			<div class="toast-body">
				<span class="fa fa-check-circle"></span>
				<?= $message ?>
			</div>
			<button type="button" class="btn-close btn-close-white ml-auto mr-2" data-dismiss="toast"
					aria-label="Close"></button>
		</div>
	</div>
</div>
