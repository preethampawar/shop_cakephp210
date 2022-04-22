<?php
App::uses('AppHelper', 'Vie/Helper');

class ImgHelper extends AppHelper
{
	var $helpers = ['Html', 'Session'];
	var $cacheDir = 'img/imagecache/';
	var $noImage = 'noimage.jpg';
	var $noImagePath = 'img/noimage.jpg';

	function showImage($image = null, $options = [], $htmlattributes = [], $returnUrl = false)
	{
		// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
		if ($image = $this->checkImage($image)) {
			if (isset($options['type']) and ($options['type'] == 'original')) {
				if ($returnUrl) {
					return $this->Html->url('/' . $image);
				} else {
					$path = explode('img/', $image);
					$path = isset($path[1]) ? $path[1] : $path[0];
					if (!isset($htmlattributes['alt'])) {
						$htmlattributes['alt'] = '';
					}
					return $this->Html->image($path, $htmlattributes);
				}
			} else {
				$type = (isset($options['type']) and !empty($options['type'])) ? $options['type'] : 'auto';
				$height = (isset($options['height']) and !empty($options['height'])) ? $options['height'] : '500';
				$width = (isset($options['width']) and !empty($options['width'])) ? $options['width'] : '500';
				$quality = (isset($options['quality']) and !empty($options['quality'])) ? $options['quality'] : '90';
				$filename = (isset($options['filename']) and !empty($options['filename'])) ? $options['filename'] : '';

				$link = $this->resize($image, $height, $width, $type, $quality, $filename);
				$cachepath = explode('img/', $link);
				$cachepath = isset($cachepath[1]) ? $cachepath[1] : $cachepath[0];

				if ($returnUrl) {
					return $this->Html->url('/' . $link);
				} else {
					return ($cachepath) ? $this->Html->image($cachepath, $htmlattributes) : $this->Html->image($this->noimage, $htmlattributes);
				}
			}
		} else {
			if ($returnUrl) {
				return $this->Html->url('/' . $this->noImagePath);
			} else {
				return $this->Html->image($this->noImage, $htmlattributes);
			}
		}
	}

	function checkImage($image)
	{

		if (empty($image)) {
			return false;
		}

		$noImage = false;
		if ($image) {
			if (substr($image, 0, 1) == '/') {
				$image = substr($image, 1);
			}

			if (file_exists($image)) {
				if (is_file($image)) {
					if (!($size = getimagesize($image))) {
						$noImage = true;
					}
				} else {
					$noImage = true;
				}
			} else {
				$noImage = true;
			}
		}
		return ($noImage) ? false : $image;
	}

	function resize($image, $height, $width, $type, $quality = '90', $filename = '')
	{
		if ($this->checkImage($image)) {
			if (trim($filename)) {
				$filename = strtolower(trim($filename));
			}
			$imageFilename = basename($image);
			$tmp = explode('.', $imageFilename);
			$imageid = $tmp[0];
			$site_id = $this->Session->read('Site.id');
			$cacheFilename = $tmp[0] . '_' . $site_id . '_' . $height . 'x' . $width . '_' . $type . '_q' . $quality . '_' . $filename;
			$cacheFile = $this->cacheDir . $cacheFilename;

			// If there is no extension for image then add extension
			if (!isset($tmp[1])) {
				$info = getimagesize($image);
				$imageType = 'jpg';
				if ($info) {
					switch ($info[2]) {
						case IMAGETYPE_PNG:
							$imageType = 'png';
							break;
						case IMAGETYPE_JPEG:
							$imageType = 'jpg';
							break;
						case IMAGETYPE_GIF:
							$imageType = 'gif';
							break;
					}
				}
				$cacheFile = $cacheFile . '.' . $imageType;
			}

			if (!file_exists($cacheFile)) {
				App::import('Vendor', 'Resize');
				$this->Resize = new Resize($image);
				$this->Resize->resizeImage($width, $height, $type);
				$this->Resize->saveImage($cacheFile, $quality);
			}
			return $cacheFile;
		}
		return false;
	}
}

?>
