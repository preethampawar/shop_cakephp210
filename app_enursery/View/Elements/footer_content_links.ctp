<?php
App::uses('Content', 'Model');
$contentModel = new Content;
$pages = $contentModel->getFooterNavContent();

if(!empty($pages)) {
	foreach($pages as $row) {
		$contentID = $row['Content']['id'];
		$contentTitle = $row['Content']['title'];
		$contentTitleSlug = Inflector::slug($row['Content']['title'], '-');
		echo '<li>';
		echo $this->Html->link($contentTitle, '/contents/show/'.$contentID.'/'.$contentTitleSlug, array('title'=>$contentTitle));
		echo '</li>';
	}
}
?>