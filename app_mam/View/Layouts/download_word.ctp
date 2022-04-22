<?php
$filename = 'quotation_no_'.$quotationID;
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$filename.doc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
	<?php echo $this->Html->charset(); ?>
	<title>
		Quotation :: <?php echo $this->Session->read('Company.display_field');?>
	</title>
	<link rel="icon" type="image/gif" href="<?php echo $this->Html->url('/img/stats.gif', true);?>">	
	<?php // echo $this->Html->css('print');?>		
	<style type="text/css">
	*
	{
		margin:0;
		padding:0;
		font-family:Arial;
		font-size:10pt;
		color:#000;
	}
	body
	{		
		font-family:Arial;
		font-size:10pt;
		margin:0;
		padding:0;
	}

	p
	{
		margin:0;
		padding:0;
	}

	#wrapper
	{
		width:180mm;
		margin:0 0mm;
	}

	.page
	{
		height:297mm;
		width:210mm;
		page-break-after:always;
	}

	table
	{	
		border-spacing:0;
		border-collapse: collapse; 

	}

	table td
	{	
		padding: 2mm;
	}

	.borderTable {
		padding:0mm;
	}
	table.borderTable{
		padding: 0mm;
		border-top:1px solid #ccc;
		border-left:1px solid #ccc;
	}
	table.borderTable th{
		border-bottom:1px solid #ccc;
		border-right:1px solid #ccc;
		padding: 1mm;
	}
	table.borderTable td{
		border-bottom:1px solid #ccc;
		border-right:1px solid #ccc;
		padding: 1mm;
	}

	.noBorder{
		border:0px solid #ccc;
	}
	.smallPadding{
		padding:1mm;
	}
	table.smallPadding{
		padding: 1mm;
	}
	table.smallPadding th{
		padding: 1mm;
	}
	table.smallPadding td{
		padding: 1mm;
	}

	table.heading
	{
		height:20mm;
	}

	h1.heading
	{
		font-size:14pt;
		color:#000;
		font-weight:normal;
	}

	h2.heading
	{
		font-size:9pt;
		color:#000;
		font-weight:normal;
	}

	hr
	{
		color:#ccc;
		background:#ccc;
	}

	#footer
	{
		width:180mm;
		margin:0 15mm;
		padding-bottom:3mm;
	}
	#footer table
	{
		width:100%;
		border-left: 1px solid #ccc;
		border-top: 1px solid #ccc;

		background:#eee;

		border-spacing:0;
		border-collapse: collapse;
	}
	#footer table td
	{
		width:25%;
		text-align:center;
		font-size:9pt;
		border-right: 1px solid #ccc;
		border-bottom: 1px solid #ccc;
	}	
	</style>
</head>
<body>
	<div id="wrapper">
		<?php echo $this->fetch('content'); ?>		
	</div>
	<br /><br />
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
