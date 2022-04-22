<?php
App::uses('Group', 'Model');
$this->Group = new Group;

App::uses('DataGroup', 'Model');
$this->DataGroup = new DataGroup;
$groupIDs = array();
if(!empty($dataID)) {
	$dataGroups = $this->DataGroup->find('all', array('conditions'=>array('DataGroup.data_id'=>$dataID), 'recursive'=>'-1'));
	if(!empty($dataGroups)) {
		foreach ($dataGroups as $row) {
			$groupIDs[$row['DataGroup']['group_id']] = $row['DataGroup']['group_id'];
		}
	}
}
else {
	if($this->Session->check('PrevDataGroups')) {
		$dataGroups = $this->Session->read('PrevDataGroups');
		if(!empty($dataGroups)) {
			foreach($dataGroups['id'] as $group_id) {
				if($group_id > 0) {
					$groupIDs[$group_id] = $group_id;
				}
			}
		}
	}
}

$conditions = array('Group.company_id'=>$this->Session->read('Company.id'), 'Group.active'=>'1');
$groups = $this->Group->find('list', array('conditions'=>$conditions, 'order'=>'Group.created DESC'));

if(!empty($groups)) {
	$i=0;
	foreach($groups as $id=>$name) {
		$checked = false;
		if(isset($groupIDs[$id])) {
			$checked = true;
		}
		echo $this->Form->input('DataGroup.id.'.$i, array('value'=>$id, 'type'=>'checkbox', 'label'=>$name, 'checked'=>$checked)).'';
		$i++;	
	}
}
else {
	echo '- No Group Found';
}

?>
