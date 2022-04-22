<div class="container-fluid mb-4">
	<div class="row row-cols-1  row-cols-md-2 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-2 justify-content-center">
		<div class="col small">
			<div class="card card-progressbar text-center bg-light rounded p-2 border">

				<div class="p-2 bg-grey border rounded"><h6 class="m-0">Order No. #33</h6></div>

				<!-- progressbar -->
				<ul id="progressbar" class="mt-4 ps-0 small">
					<li class="active small" id="new"><span><?= Order::ORDER_STATUS_NEW ?></span></li>
					<li class="active small" id="confirmed"><span><?= Order::ORDER_STATUS_CONFIRMED ?></span></li>
					<li class="active small" id="shipped"><span><?= Order::ORDER_STATUS_SHIPPED ?></span></li>
					<li class=" small" id="delivered"><span><?= Order::ORDER_STATUS_DELIVERED ?></span></li>
				</ul>
			</div>
		</div>
		<div class="col">
			<div class="card-progressbar text-center border rounded bg-light px-2 pt-3">
				<h5 id="heading">Status of your Order No. #33</h5>
				<!-- progressbar -->
				<ul id="progressbar" class="mt-4 ps-0 small">
					<li class="active" id="new"><strong><?= Order::ORDER_STATUS_NEW ?></strong></li>
					<li class="active" id="confirmed"><strong><?= Order::ORDER_STATUS_CONFIRMED ?></strong></li>
					<li class="active" id="shipped"><strong><?= Order::ORDER_STATUS_SHIPPED ?></strong></li>
					<li class="" id="delivered"><strong><?= Order::ORDER_STATUS_DELIVERED ?></strong></li>
				</ul>
			</div>
		</div>
		<div class="col">
			<div class="card-progressbar text-center border rounded bg-light px-2 pt-3">
				<h5 id="heading">Status of your Order No. #33</h5>
				<!-- progressbar -->
				<ul id="progressbar" class="mt-4 ps-0 small">
					<li class="active" id="new"><strong><?= Order::ORDER_STATUS_NEW ?></strong></li>
					<li class="active" id="confirmed"><strong><?= Order::ORDER_STATUS_CONFIRMED ?></strong></li>
					<li class="active" id="shipped"><strong><?= Order::ORDER_STATUS_SHIPPED ?></strong></li>
					<li class="" id="delivered"><strong><?= Order::ORDER_STATUS_DELIVERED ?></strong></li>
				</ul>
			</div>
		</div>
		<div class="col">
			<div class="card-progressbar text-center border rounded bg-light px-2 pt-3">
				<h6 id="heading">Status of your Order No. #33</h6>
				<!-- progressbar -->
				<ul id="progressbar" class="mt-4 ps-0 small">
					<li class="active" id="new"><strong><?= Order::ORDER_STATUS_NEW ?></strong></li>
					<li class="active" id="confirmed"><strong><?= Order::ORDER_STATUS_CONFIRMED ?></strong></li>
					<li class="active" id="shipped"><strong><?= Order::ORDER_STATUS_SHIPPED ?></strong></li>
					<li class="" id="delivered"><strong><?= Order::ORDER_STATUS_DELIVERED ?></strong></li>
				</ul>
			</div>
		</div>
	</div>
</div>
