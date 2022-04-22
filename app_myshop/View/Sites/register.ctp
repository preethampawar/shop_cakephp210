<?php $this->set('loadVueJs', true); ?>
<div id="siteRegister">
	<?php echo $this->Form->create(); ?>
	<h1 class="mb-3">Create your online business store</h1>

	<div class="mb-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Business Title			
		</label>
		<input
			type="text"
			name="data[Site][title]"
			class="form-control"
			id="SiteTitle"
			placeholder="Enter your business name"
			maxlength="55"
			required
			autofocus>
	</div>

	<div class="mb-3">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Caption (optional)
		</label>
		<input
			type="text"
			name="data[Site][caption]"
			class="form-control"
			id="SiteCaption"
			placeholder="Enter your business caption"
			maxlength="150">
	</div>

	<div class="mb-4">
		<label for="exampleFormControlInput1" class="form-label font-weight-bold">
			Business name in URL
		</label>
		<input
			v-model="siteName"
			type="text"
			name="data[Site][name]"
			class="form-control mb-1"
			id="SiteName"
			placeholder="Enter one word name for your business (min 3 characters)"
			minlength="3"
			maxlength="20"
			required>

		<div v-if="siteName.length > 2" class="alert alert-warning py-1 px-2 small" role="alert">
			<div>Your Business URL will be:</div>
			<div class="font-weight-bold">
				http://{{siteName}}.<?php echo Configure::read('Domain'); ?>
			</div>
		</div>
	</div>

	<div class="mb-3">
		<button type="submit" class="btn btn-md btn-primary">Create Online Store</button>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<script>
	var app = new Vue({
		el: '#siteRegister',
		data: {
			siteName: ''
		},
		watch: {
			siteName: function (val) {
				let siteName = val.trim(); // no space between words are allowed

				if (siteName.length > 0) {
					siteName = siteName.replace(/[^a-z0-9]/gi, ''); // allow only alphanumeric chars
				}

				this.siteName = siteName.toLowerCase();
			}
		},
	})
</script>


