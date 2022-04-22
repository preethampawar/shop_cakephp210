<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->Session->read('Company.name'); ?>
	</title>
	<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true); ?>">
	<?php echo $this->Html->css('print'); ?>
</head>
<body>
<div id="wrapper">
	<?php echo $this->fetch('content'); ?>
</div>
<br/><br/>
<!--
<htmlpagefooter name="footer">
	<hr />
	<div id="footer">
		<table>
			<tr><td>MyAccountmanager.in</td><td>Web Solutions</td></tr>
		</table>
	</div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />
-->
</body>
</html>
<?php
exit;
?>
