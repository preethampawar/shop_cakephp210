<?php echo $this->element('message');?>
<div>
<h1>Register your Business/Personal Account</h1><br>
<?php 
echo $this->Form->create('Company');
?>
	<div class="input text required" style="width:300px;"><label for="CompanyTitle">Name of your Company/Organization</label>
		<?php echo $this->Form->input('title', array('label'=>false, 'div'=>false, 'required'=>true));?>
	</div>
<?php
//echo $this->Form->input('title', array('label'=>'Name of Your Company/Organization', 'required'=>true));
echo '<br>';

$business_accounts = Configure::read('BusinessAccounts');
if(!$showPersonalAccount)
{
	$business_accounts = Configure::read('BusinessAccounts');
	unset($business_accounts['personal']);
	//$business_accounts = Configure::write('BusinessAccounts', array('general'=>'General', 'inventory'=>'Inventory Management'));
}

$options = $business_accounts;
$attributes=array('legend'=>false,'label'=>false, 'div'=>false, 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;', 'escape'=>false, 'style'=>'float:none;', 'required'=>true);
echo '<div class="input text required"><label>Account Type</label>';							
echo $this->Form->radio('business_type',$options, $attributes);
echo '</div>';
//echo '<br>';

echo $this->Form->submit('Create Account &nbsp;&raquo;', array('escape'=>false));
echo $this->Form->end();								
?>
</div>
<br><br>

<div>
<b>Account Type:</b><br>
<b>- Personal</b>:  Personal Account is a free account. You can record and manage all your Income and Expenses.<br>
<b>- General</b>: In General Account you can record and manage all Sales, Purchases and Cash transactions.<br>
<b>- Inventory Management</b>: You can record and manage all Sales, Purchases and Cash transactions. Track Inventory and manage Stock of selected products/items.<br>
<b>- Wine Store</b>: This is an exclusive account for Wine stores. Here one can manage all Sales, Purchases and Cash transactions. Track Inventory, Manage Stock of selected products/items and can update available/remaining stock for the day.<br>
</div>
<br><br>

<div class="note">
<b>Note:</b> <br>
- All account's except Personal account are trial accounts. <br>
- A trial account is valid for a period of 1month from the date of registration of your business account. You need to renew the subscription to continue using the business account.<br>
- For more details check <?php echo $this->Html->link('pricing information', '/pricing');?>. <br>
</div>
