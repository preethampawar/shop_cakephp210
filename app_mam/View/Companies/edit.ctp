<div style="width:500px; margin:auto;">
<?php echo $this->element('message');?>
<h1>Edit Company :: <?php echo $companyInfo['Company']['title'];?></h1><br>
<?php 
echo $this->Form->create('Company');
echo $this->Form->input('title', array('label'=>'Name', 'required'=>true));
echo $this->Form->input('commercial', array('label'=>'For Commercial Use', 'required'=>false));
echo $this->Form->input('active', array('label'=>'Active', 'required'=>false));
echo $this->Form->submit('Save Changes');
echo $this->Form->end();								
?>
</div>