<?php
$filename = Inflector::slug($filename, '-');
$imageUrl = $this->Html->url($this->Img->showImage('img/images/'.$image_id, array('height'=>$height,'width'=>$width,'type'=>$type, 'quality'=>$quality, 'filename'=>$filename), array('style'=>''), true), true);
$image_data = file_get_contents($imageUrl);
print($image_data);

print_r($imageUrl);


debug($image_data);
