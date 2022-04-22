<?php
App::uses('Site', 'Model');

$this->set('enableTextEditor', true);
?>
<style type="text/css">
	.checkbox label {
		padding-left: 5px;
	}
</style>

<div>
	<h1>Store Settings</h1>

	<form action="/admin/sites/settings" id="SiteAdminEditForm" method="post" accept-charset="utf-8" ref="form" enctype="multipart/form-data">
		<div class="mt-0 d-flex justify-content-end align-items-center">
			<button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
			<a href="/admin/sites/home" class="btn btn-outline-warning btn-sm ms-3">Cancel</a>
		</div>

		<?php if ($this->Session->read('User.superadmin') == true) { ?>
		<div class="mt-4 alert alert-info">
			<h6>Super Admin Settings</h6>

			<div class="mt-3">
				<label class="form-check-label" for="SiteShoppingCart">Maximum Products Allowed</label>
				<?php
				$products_limit = [5 => 5, 10 => 10, 25 => 25, 50 => 50, 100 => 100, 500 => 500, 1000 => 1000, 5000 => 5000, 10000 => 10000];
				echo $this->Form->input(
					'Site.products_limit',
					[
						'type' => 'select',
						'label' => false,
						'options' => $products_limit,
						'class' => 'form-control form-control-sm',
					]
				);
				?>
			</div>
		</div>
		<?php } ?>

		<div class="mt-4">
			<div class="form-check form-switch">
				<input type="hidden" name="data[Site][show_banners]" value="0">
				<input
					type="checkbox"
					id="SiteShowBanners"
					name="data[Site][show_banners]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Site']['show_banners'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="SiteShowBanners">Enable Banners Slideshow</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Site][show_testimonials]" value="0">
				<input
					type="checkbox"
					id="SiteShowTestimonials"
					name="data[Site][show_testimonials]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Site']['show_testimonials'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="SiteShowTestimonials">Enable Testimonials Slideshow</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Site][shopping_cart]" value="0">
				<input
					type="checkbox"
					id="SiteShoppingCart"
					name="data[Site][shopping_cart]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Site']['shopping_cart'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="SiteShoppingCart">Enable Shopping Cart</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Site][show_promo_codes]" value="0">
				<input
					type="checkbox"
					id="SiteShowPromoCodes"
					name="data[Site][show_promo_codes]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Site']['show_promo_codes'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="SiteShowPromoCodes">Enable Promo Codes</label>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Site][sms_notifications]" value="0">
				<input
					type="checkbox"
					id="SiteSmsNotifications"
					name="data[Site][sms_notifications]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Site']['sms_notifications'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="SiteSmsNotifications">Enable SMS Notifications</label>

					<?php
					if(empty(Configure::read('Sms')) || empty(Configure::read('SmsProvider'))) {
						?>
						<div class="small text-danger mb-2">*SMS Gateway is not configured for this store. SMS notifications cannot be sent.</div>
						<?php
					} else {
					?>
						<span class="small text-success mb-2">(<?= Configure::read('SmsProvider') ?>)</span>
						<?php
					}
					?>
			</div>
			<div class="form-check form-switch">
				<input type="hidden" name="data[Site][under_maintenance]" value="0">
				<input
					type="checkbox"
					id="SiteUnderMaintenance"
					name="data[Site][under_maintenance]"
					value="1"
					class="form-check-input"
					<?php echo $this->data['Site']['under_maintenance'] ? 'checked' : null; ?>
				>
				<label class="form-check-label" for="SiteUnderMaintenance">Enable Maintenance Mode</label>
			</div>

			<div class="mt-3">
				<a href="/admin/sites/clearCache" class="btn btn-sm btn-outline-danger">Clear Store Info Cache</a>
			</div>
		</div>

		<div class="mt-2">
			<div>

				<hr class="mt-4">
				<div class="text-start mt-4">
					<h5>Store Configuration</h5>
				</div>
				<div class="ps-3">
					<div class="mb-4 card card-body">

						<label for="SiteTitle" class="form-label">Upload Logo</label>
						<input
								type="file"
								id="SiteLogo"
								name="data[Store][logo]"
								class="form-control form-control-sm"
								placeholder="Upload Store Logo"
						>
						<span class="text-muted small">*Best Size: 125 x 75 pixels (width x height)</span>

						<?php
						if (isset($this->data['Site']['logo']) && !empty(trim($this->data['Site']['logo']))) {
						?>
						<div class="mt-3">
							<b>Store Logo</b>
							<input
									type="file"
									id="SiteLogo"
									name="data[Store][logo]"
									class="form-control form-control-sm d-none"
									placeholder="Upload Store Logo"
							>
							<img class="border d-block" src="<?= $this->Html->url('/'.$this->data['Site']['logo'], true); ?>">
							<button
								type="button"
								class="btn btn-outline-danger btn-sm mt-2"
								onclick="showConfirmPopup(
										'/admin/sites/deleteFile/<?= base64_encode($this->data['Site']['logo']) ?>',
										'Delete Logo',
										'Are you sure you want to remove this Logo?')"
							>
								<i class="fa fa-times"></i> Delete Logo
							</button>
						</div>
						<?php
						}
						?>
						<table class="w-100">
							<tr>
								<td class="text-center px-5">
									Logo Width<br>
									<input
										type="number"
										id="SiteLogoWidth"
										name="data[Site][logo_width]"
										class="form-control form-control-sm"
										placeholder="Enter width"
										min="10"
										max="800"
										value="<?php echo $this->data['Site']['logo_width']; ?>"
									>
								</td>
								<td class="text-center px-5">
									Logo Height<br>
									<input
										type="number"
										id="SiteLogoHeight"
										name="data[Site][logo_height]"
										class="form-control form-control-sm"
										placeholder="Enter height"
										min="5"
										max="600"
										value="<?php echo $this->data['Site']['logo_height']; ?>"
									>
								</td>
							</tr>
						</table>
					</div>

					<div class="mb-4">
						<label for="SiteTitle" class="form-label">Store Title</label>
						<input
								type="text"
								id="SiteTitle"
								name="data[Site][title]"
								value="<?php echo $this->data['Site']['title']; ?>"
								class="form-control form-control-sm"
								placeholder="Enter Store Title"
								minlength="3"
								maxlength="50"
								required
						>
					</div>

					<div class="mb-4">
						<label for="SiteCaption" class="form-label">Caption</label>
						<input
								type="text"
								id="SiteCaption"
								name="data[Site][caption]"
								value="<?php echo $this->data['Site']['caption']; ?>"
								class="form-control form-control-sm"
								placeholder="Enter Store Caption"
								minlength="3"
								maxlength="50"
								required
						>
					</div>
					<div class="mb-4">
						<label for="SiteShippingCharges" class="form-label">Shipping Charges</label>

						<input
								type="number"
								id="SiteShippingCharges"
								name="data[Site][shipping_charges]"
								value="<?php echo $this->data['Site']['shipping_charges']; ?>"
								class="form-control form-control-sm"
								placeholder="Enter Shipping/Delivery Charges"
								min="0"
								max="10000"
								required
						>
						<span class="text-muted small">Note: This amount is set as default delivery charges for all the customers.</span>
					</div>
					<div class="mb-4">
						<label for="SiteFreeShippingMinAmount" class="form-label">Free Shipping On Min. Order Amount</label>
						<input
								type="number"
								id="SiteFreeShippingMinAmount"
								name="data[Site][free_shipping_min_amount]"
								value="<?php echo $this->data['Site']['free_shipping_min_amount']; ?>"
								class="form-control form-control-sm"
								placeholder="Enter Free Shipping Min Order Amount"
								min="0"
								max="100000"
								required
						>
						<span class="text-muted small">Note: Free shipping is applied to the customers when the shopping cart value exceeds this amount value.</span>
					</div>
					<div class="mb-4">
						<label for="SiteFromEmailAddress" class="form-label">Send Notification Emails To (Admin) </label>

						<input
								type="text"
								id="SiteFromEmailAddress"
								name="data[Site][seller_notification_email]"
								value="<?php echo $this->data['Site']['seller_notification_email']; ?>"
								class="form-control form-control-sm"
								placeholder="Enter Notification Email Address"
								required
						>
						<span class="text-muted small">Note: You can specify more than one email address separated by commas "<b>,</b>"</span> (<code>abc@gmail.com,xyz@gmail.com</code>).
					</div>
					<div class="mb-4">
						<label for="orderNotificationsMobileNo">Send Notification SMS To Mobile (Admin)</label>
						<input
								type="number"
								name="data[Site][notifications_mobile_no]"
								id="orderNotificationsMobileNo"
								class="form-control form-control-sm"
								value="<?php echo $this->data['Site']['notifications_mobile_no']; ?>"
								min="6000000000"
								max="9999999999"
								placeholder="Enter 10 digit mobile no."
								>
						<span class="text-muted small">Note: When SMS Notifications is enabled, all admin notification SMS will be sent to this number. Ex: When an order is created.</span>
					</div>
					<div class="mb-4">
						<label for="SiteDefaultCustomerNotificationEmail" class="form-label">Default Customer Notification Email Address</label>

						<input
							type="text"
							id="SiteDefaultCustomerNotificationEmail"
							name="data[Site][default_customer_notification_email]"
							value="<?php echo $this->data['Site']['default_customer_notification_email']; ?>"
							class="form-control form-control-sm"
							placeholder="Enter Customer Order Email Address"
							required
						>
						<span class="text-muted small">Note: If the customer doesn't specify email in registration or order placement, then this email address will be used as default to receive orders or notifications on behalf of customers.</span>.
					</div>

					<div class="mb-4">
						<label for="SiteTheme" class="form-label">Theme</label>
						<?php
						echo $this->Form->input('Site.theme', [
								'type' => 'select',
								'label' => false,
								'options' => Site::THEME_OPTIONS,
								'default' => Site::THEME_LIGHT,
								'class' => 'form-select form-select-sm'
						]);
						?>
					</div>
					<div class="mb-4 d-none">
						<label for="SiteThemeCss" class="form-label">Theme CSS</label>
						<textarea
								id="SiteThemeCss"
								name="data[Site][theme_css]"
								class="form-control form-control-sm"
								placeholder="Enter only CSS Code"
								rows="4"
						><?php echo $this->data['Site']['theme_css']; ?></textarea>
					</div>

					<?php
					/*
					?>
					<div class="mb-4">
						<label for="StoreSupportEmailAddress" class="form-label">Store Support Email </label>

						<input
								type="email"
								id="StoreSupportEmailAddress"
								name="data[Site][seller_support_email]"
								value="<?php echo $this->data['Site']['seller_support_email']; ?>"
								class="form-control form-control-sm"
								placeholder="Enter Store Support Email Address"
								required
						>
					</div>
					<div class="mb-4">
						<label for="NoreplyEmailAddress" class="form-label">Noreply Email</label>

						<input
								type="email"
								id="NoreplyEmailAddress"
								name="data[Site][seller_noreply_email]"
								value="<?php echo $this->data['Site']['seller_noreply_email']; ?>"
								class="form-control form-control-sm"
								placeholder="Enter Noreply Email Address"
								required
						>
					</div>
					<?php
					*/
					?>
				</div>

				<hr class="mt-5">
				<div class="text-start mt-4">
					<h5>SEO & Analytics</h5>
				</div>
				<div class="ps-3 rounded">
					<div class="mb-4">
						<label for="SiteMetaKeywords" class="form-label">Meta Keywords (SEO)</label>
						<textarea
								id="SiteMetaKeywords"
								name="data[Site][meta_keywords]"
								class="form-control form-control-sm"
								placeholder="Enter meta keywords"
								rows="2"
						><?php echo $this->data['Site']['meta_keywords']; ?></textarea>
						<div class="small text-muted">Note: Enter 5 to 10 unique keywords separated by commas. Do not enter any special chars.</div>
					</div>
					<div class="mb-4">
						<label for="SiteMetaDesc" class="form-label">Meta Description (SEO)</label>
						<textarea
								id="SiteMetaDesc"
								name="data[Site][meta_description]"
								class="form-control form-control-sm"
								placeholder="Enter short description of the store"
								rows="2"
						><?php echo $this->data['Site']['meta_description']; ?></textarea>
						<div class="small text-muted">Note: Enter short description of this store. Preferably, a very short paragraph without any special chars.</div>
					</div>
					<div class="mb-4">
						<label for="SiteAnalyticsCode" class="form-label">Analytics (Javascript code)</label>
						<textarea
								id="SiteAnalyticsCode"
								name="data[Site][analytics_code]"
								class="form-control form-control-sm"
								placeholder="Enter Analytics Code"
								rows="4"
						><?php echo $this->data['Site']['analytics_code']; ?></textarea>
					</div>
					<div class="mb-4">
						<label for="SiteMapCode" class="form-label">Embed Map (HTML/Javascript code)</label>
						<textarea
								id="SiteMapCode"
								name="data[Site][embed_map]"
								class="form-control form-control-sm"
								placeholder="Enter Map Code"
								rows="4"
						><?php echo $this->data['Site']['embed_map']; ?></textarea>
					</div>
				</div>


				<hr class="mt-5">
				<div class="text-start mt-4">
					<h5>Pages</h5>
				</div>
				<div class="ps-3">
					<div class="mb-4">
						<label for="SiteDescription" class="form-label">About Us</label>
						<textarea
							id="SiteDescription"
							name="data[Site][description]"
							class="form-control form-control-sm tinymce"
							placeholder="Enter Site description"
						><?php echo $this->data['Site']['description']; ?></textarea>
					</div>
					<div class="mb-4">
						<label for="SiteContactInfo" class="form-label">Contact Us</label>
						<textarea
							id="SiteContactInfo"
							name="data[Site][contact_info]"
							class="form-control form-control-sm tinymce"
							placeholder="Enter Contact Information"
						><?php echo $this->data['Site']['contact_info']; ?></textarea>
					</div>
					<div class="mb-4">
						<label for="SitePaymentInfo" class="form-label">Payment Information</label>
						<textarea
							id="SitePaymentInfo"
							name="data[Site][payment_info]"
							class="form-control form-control-sm tinymce"
							placeholder="Enter Payment Related Information"
						><?php echo $this->data['Site']['payment_info']; ?></textarea>
					</div>
					<div class="mb-4">
						<label for="SiteTos" class="form-label">Terms of Service</label>
						<textarea
							id="SiteTos"
							name="data[Site][tos]"
							class="form-control form-control-sm tinymce"
							placeholder="Enter Terms of Service"
						><?php echo $this->data['Site']['tos']; ?></textarea>
					</div>
					<div class="mb-4">
						<label for="SitePrivacyPolicy" class="form-label">Privacy Policy</label>
						<textarea
								id="SitePrivacyPolicy"
								name="data[Site][privacy_policy]"
								class="form-control form-control-sm tinymce"
								placeholder="Enter Privacy Policy"
						><?php echo $this->data['Site']['privacy_policy']; ?></textarea>
					</div>
					<div class="mb-4">
						<label for="SiteFooterScripts" class="form-label">Footer Scripts (HTML/Javascript code)</label>
						<textarea
								id="SiteFooterScripts"
								name="data[Site][footer_scripts]"
								class="form-control form-control-sm"
								placeholder="Enter HTML/Javascript code"
								rows="4"
						><?php echo $this->data['Site']['footer_scripts']; ?></textarea>
					</div>
				</div>

			</div>
			<br>
			<div class="my-3 py-3 d-inline">

				<button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
				<a href="/admin/sites/home" class="btn btn-outline-warning btn-sm ms-3">Cancel</a>
			</div>
		</div>

		<?php
		echo $this->Form->end();
		?>
</div>
<br><br>

