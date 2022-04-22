<?php
App::uses('Content', 'Model');
$contentModel = new Content;
$pages = $contentModel->getTopNavContent();

if (!empty($pages)) {
	foreach ($pages as $row) {
		$contentID = $row['Content']['id'];
		$contentTitle = $row['Content']['title'];
		$contentTitleSlug = Inflector::slug($row['Content']['title'], '-');
		echo '<li id="content' . $contentID . '">';
		echo $this->Html->link($contentTitle, '/contents/show/' . $contentID . '/' . $contentTitleSlug, ['title' => $contentTitle]);
		echo '</li>';
	}
}
?>
