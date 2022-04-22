<?php
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$title_for_layout.doc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Quotation :: <?php echo $this->Session->read('Company.display_field'); ?>
	</title>
	<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true); ?>">

	<link rel="stylesheet" href="<?php echo $this->Html->url('/css/tailwindcss.css'); ?>">
	<link rel="stylesheet" href="<?php echo $this->Html->url('/bootstrap-3.3.7/dist/css/bootstrap.min.css'); ?>">

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
			<tr><td>Software Solutions</td><td>Mobile Solutions</td><td>Web Solutions</td></tr>
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
