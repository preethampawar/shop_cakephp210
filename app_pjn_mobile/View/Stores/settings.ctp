<a href="/stores/">&laquo; Back to stores list</a><br><br>

<h1>Settings - <?php echo $storeInfo['Store']['name']; ?></h1><br>

<?php
$settingsKeyId = [];
$settingsKeyValue = [];
if ($storeSettings) {
	foreach ($storeSettings as $row) {
		$settingsKeyId[$row['StoreSetting']['key']] = $row['StoreSetting']['id'];
		$settingsKeyValue[$row['StoreSetting']['key']] = $row['StoreSetting']['value'];
	}
}
?>

<?php
echo $this->Form->create();
?>
<table class="table table-striped table-condensed">
	<thead>
	<tr>
		<th>Sl No.</th>
		<th>Feature</th>
		<th>Description</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	foreach ($storeFields as $key => $row) {
		?>
		<tr>
			<td><?php echo $i; ?></td>
			<td>
				<?php
				$settingId = isset($settingsKeyId[$key]) ? $settingsKeyId[$key] : null;

				if ($row['type'] == 'checkbox') {
					$checked = $row['default'] ? 'checked = "checked"' : "";
					if (isset($settingsKeyId[$key])) {
						$checked = (isset($settingsKeyValue[$key]) && $settingsKeyValue[$key] == 1) ? 'checked = "checked"' : "";
					}
					?>
					<input type="hidden" name="<?php echo $i; ?>[StoreSetting][id]" value="<?php echo $settingId; ?>">
					<input type="hidden" name="<?php echo $i; ?>[StoreSetting][key]" value="<?php echo $key; ?>">
					<input type="hidden" name="<?php echo $i; ?>[StoreSetting][value]" value="0">
					<input type="checkbox" id="<?php echo $key; ?>" name="<?php echo $i; ?>[StoreSetting][value]"
						   value="1" <?php echo $checked; ?> >
					<input type="hidden" name="<?php echo $i; ?>[StoreSetting][name]"
						   value="<?php echo $row['name']; ?>">
					<input type="hidden" name="<?php echo $i; ?>[StoreSetting][description]"
						   value="<?php echo $row['description']; ?>">
					<?php
				}
				?>
				<label for="<?php echo $key; ?>"><?php echo $row['name']; ?></label>
			</td>
			<td><?php echo $row['description']; ?></td>
		</tr>
		<?php
		$i++;
	}
	?>
	</tbody>
</table>
<button type="submit" class="btn btn-purple btn-sm">Save Settings</button>
<?php echo $this->Form->end(); ?>
<br><br>
