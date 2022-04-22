<div class="Title">Categories Menu</div>
<div class="roundedCorner defaultBackground">
<?php
if(!empty($categories))
{
	foreach($categories as $id=>$category)
	{
		$categoryNameUrl = str_replace(' ', '-', html_entity_decode($category, ENT_QUOTES));
		$pCName = $category;
		if(strlen($pCName) > 25) {
			$pCName = substr($pCName,0, 22).'...';
		}
	?>
	<div>
		<div id="leftCategoryLink<?php echo $id;?>" class="leftCategoryLinkDiv" onmouseover="showLeftCategoryLinkBackground(this.id)" onmouseout="resetLeftCategoryLinkBackground(this.id)">
		<?php
		$div='<div> '.$pCName.'</div>';	
		echo $html->link($div, '/plants/show/'.$id.'/'.$categoryNameUrl, array('style'=>'text-decoration:none;', 'title'=>''.$category, 'escape'=>false));		
		?>
		</div>
	</div>
	<?php
	}
}
else
{
	?>
	<div class="pagesContent">
		Categories not found
	</div>
	<?php
}
?>
</div>